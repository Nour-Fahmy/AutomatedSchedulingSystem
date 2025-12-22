<?php

namespace App\Services;

use App\Models\ForumThread;
use App\Models\ForumPost;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ForumService
{
    /**
     * Get paginated forum threads with optional service filter
     */
    public function getThreads(?int $serviceId = null, int $perPage = 15)
    {
        $query = ForumThread::with(['creator', 'service', 'posts'])
            ->orderBy('created_at', 'desc');

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get active services for forum
     */
    public function getActiveServices()
    {
        return Service::where('is_active', true)->get();
    }

    /**
     * Get a single thread with its posts
     */
    public function getThread(int $id): ForumThread
    {
        return ForumThread::with(['creator', 'service', 'posts.author'])
            ->findOrFail($id);
    }

    /**
     * Create a new forum thread with initial post
     */
    public function createThread(array $data): ForumThread
    {
        $thread = ForumThread::create([
            'title' => $data['title'],
            'service_id' => $data['service_id'] ?? null,
            'created_by' => Auth::id(),
            'is_locked' => false,
        ]);

        // Create the first post (OP)
        ForumPost::create([
            'thread_id' => $thread->id,
            'posted_by' => Auth::id(),
            'body' => $data['body'],
            'is_answer' => false,
        ]);

        return $thread;
    }

    /**
     * Add a reply to a thread
     */
    public function addReply(int $threadId, array $data): ForumPost
    {
        $thread = ForumThread::findOrFail($threadId);

        // Check if thread is locked
        if ($thread->is_locked) {
            throw new \Exception('This thread is locked and cannot be replied to.');
        }

        return ForumPost::create([
            'thread_id' => $thread->id,
            'posted_by' => Auth::id(),
            'body' => $data['body'],
            'is_answer' => $data['is_answer'] ?? false,
        ]);
    }

    /**
     * Get data for forum index view
     */
    public function getIndexData(?int $serviceId = null): array
    {
        return [
            'threads' => $this->getThreads($serviceId),
            'services' => $this->getActiveServices(),
        ];
    }

    /**
     * Get data for forum create view
     */
    public function getCreateFormData(): array
    {
        return [
            'services' => $this->getActiveServices(),
        ];
    }
}


