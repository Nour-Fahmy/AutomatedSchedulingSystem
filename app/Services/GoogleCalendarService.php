<?php

namespace App\Services;

// ✅ Imports for Google API
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;
use Google\Service\Calendar\EventReminder;
use Google\Service\Calendar\EventReminders;

class GoogleCalendarService
{
    /**
     * Get authenticated Google Client for a user
     */
    private function getClientForUser(User $user): ?Client
    {
        if (!$user->google_token) {
            return null;
        }

        $client = new Client();
        $client->setApplicationName('AlignUp');
        $client->setScopes(Calendar::CALENDAR);
        $client->setAuthConfig(storage_path('app/google_credentials.json'));
        $client->setAccessType('offline');
        $client->setRedirectUri(route('google.callback'));

        $token = json_decode($user->google_token, true);
        $client->setAccessToken($token);

        // Refresh token if expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $newToken = $client->getAccessToken();
                
                // Update stored token
                $user->update([
                    'google_token' => json_encode($newToken),
                ]);
            } else {
                // Token expired and no refresh token available
                return null;
            }
        }

        return $client;
    }

    /**
     * Create a Google Calendar event for a user with notifications
     */
    public function createEventForUser(Appointment $appointment, User $user): ?string
    {
        $client = $this->getClientForUser($user);
        if (!$client) {
            return null;
        }

        try {
            $service = new Calendar($client);

            // Determine the other party
            $otherParty = $user->id === $appointment->student_id 
                ? $appointment->faculty 
                : $appointment->student;

            // Create event
            $event = new Event();
            $event->setSummary('Appointment: ' . $appointment->service->name);
            $event->setDescription(
                "Appointment Details:\n" .
                "Service: " . $appointment->service->name . "\n" .
                ($appointment->reason ? "Reason: " . $appointment->reason . "\n" : "") .
                "With: " . $otherParty->name . " (" . $otherParty->email . ")"
            );

            // Set start time
            $start = new EventDateTime();
            $start->setDateTime(date('c', strtotime($appointment->start_at)));
            $start->setTimeZone(config('app.timezone', 'UTC'));
            $event->setStart($start);

            // Set end time
            $end = new EventDateTime();
            $end->setDateTime(date('c', strtotime($appointment->end_at)));
            $end->setTimeZone(config('app.timezone', 'UTC'));
            $event->setEnd($end);

            // Add attendees (both student and faculty)
            $attendees = [
                ['email' => $appointment->student->email],
                ['email' => $appointment->faculty->email],
            ];
            $event->setAttendees($attendees);

            // Set up reminders/notifications
            // 1 day before (1440 minutes) and 15 minutes before
            $reminder = new EventReminder();
            $reminder->setMethod('email');
            $reminder->setMinutes(1440); // 1 day before

            $reminder2 = new EventReminder();
            $reminder2->setMethod('email');
            $reminder2->setMinutes(15); // 15 minutes before

            $reminder3 = new EventReminder();
            $reminder3->setMethod('popup');
            $reminder3->setMinutes(15); // 15 minutes before (popup notification)

            $reminders = new EventReminders();
            $reminders->setUseDefault(false);
            $reminders->setOverrides([$reminder, $reminder2, $reminder3]);
            $event->setReminders($reminders);

            // Send notifications to attendees
            $event->setSendUpdates('all');

            // Insert event
            $createdEvent = $service->events->insert('primary', $event);

            return $createdEvent->getId();
        } catch (\Exception $e) {
            Log::error('Failed to create Google Calendar event: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create events for both student and faculty
     * Creates event in the primary user's calendar (student preferred) and invites the other party
     */
    public function createEvent(Appointment $appointment): ?string
    {
        // Load relationships
        $appointment->load(['student', 'faculty', 'service']);

        // Prioritize student's calendar, fallback to faculty's
        $primaryUser = $appointment->student->google_token 
            ? $appointment->student 
            : ($appointment->faculty->google_token ? $appointment->faculty : null);

        if (!$primaryUser) {
            // Neither user has Google Calendar connected
            return null;
        }

        // Create event in primary user's calendar (both will be notified via attendees)
        return $this->createEventForUser($appointment, $primaryUser);
    }

    /**
     * Delete a Google Calendar event
     */
    public function deleteEvent(User $user, string $eventId): bool
    {
        $client = $this->getClientForUser($user);
        if (!$client) {
            return false;
        }

        try {
            $service = new Calendar($client);
            $service->events->delete('primary', $eventId);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete Google Calendar event: ' . $e->getMessage());
            return false;
        }
    }
}
