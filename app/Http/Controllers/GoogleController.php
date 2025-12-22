<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Google\Service\Calendar\EventAttendee;

use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function redirect()
    {
        $credentialsPath = storage_path('app/google_credentials.json');
        
        // Check if credentials file exists
        if (!file_exists($credentialsPath)) {
            return redirect()->route('settings.index')
                ->withErrors(['google' => 'Google Calendar credentials file not found. Please contact administrator to set up Google Calendar integration.']);
        }

        try {
            $client = new Client();
            $client->setAuthConfig($credentialsPath);
            $client->setScopes(Calendar::CALENDAR);
            $client->setRedirectUri(route('google.callback'));
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');

            return redirect($client->createAuthUrl());
        } catch (\Exception $e) {
            Log::error('Google Calendar redirect error: ' . $e->getMessage());
            return redirect()->route('settings.index')
                ->withErrors(['google' => 'Failed to initialize Google Calendar: ' . $e->getMessage()]);
        }
    }

    public function callback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect()->route('settings.index')
                ->withErrors(['google' => 'Authorization failed. Please try again.']);
        }

        $credentialsPath = storage_path('app/google_credentials.json');
        
        if (!file_exists($credentialsPath)) {
            return redirect()->route('settings.index')
                ->withErrors(['google' => 'Google Calendar credentials file not found.']);
        }

        try {
            $client = new Client();
            $client->setAuthConfig($credentialsPath);
            $client->setRedirectUri(route('google.callback'));

            $token = $client->fetchAccessTokenWithAuthCode($request->code);

            if (isset($token['error'])) {
                return redirect()->route('settings.index')
                    ->withErrors(['google' => 'Failed to authenticate with Google: ' . $token['error']]);
            }

            // Store token for the authenticated user
            $user = auth()->user();
            if (!$user) {
                return redirect()->route('login')
                    ->withErrors(['google' => 'You must be logged in to connect Google Calendar.']);
            }

            $user->update([
                'google_token' => json_encode($token),
            ]);

            return redirect()->route('settings.index')
                ->with('status', 'Google Calendar connected successfully!');
        } catch (\Exception $e) {
            Log::error('Google Calendar callback error: ' . $e->getMessage());
            return redirect()->route('settings.index')
                ->withErrors(['google' => 'Failed to connect Google Calendar: ' . $e->getMessage()]);
        }
    }

    /**
     * Disconnect Google Calendar
     */
    public function disconnect()
    {
        $user = auth()->user();
        $user->update([
            'google_token' => null,
        ]);

        return redirect()->route('settings.index')
            ->with('status', 'Google Calendar disconnected successfully.');
    }
}
