<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ForumService;

class ForumController extends Controller
{
    protected $forumService;

    public function __construct(ForumService $forumService)
    {
        $this->forumService = $forumService;
    }

    /**
     * Display a listing of forum threads
     */
    public function index(Request $request)
    {
        $serviceId = $request->has('service_id') && $request->service_id 
            ? (int) $request->service_id 
            : null;

        $data = $this->forumService->getIndexData($serviceId);

        return view('forum.index', $data);
    }

    /**
     * Show the form for creating a new thread
     */
    public function create()
    {
        $data = $this->forumService->getCreateFormData();
        
        return view('forum.create', $data);
    }

    /**
     * Store a newly created thread
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'service_id' => 'nullable|exists:services,id',
            'body' => 'required|string|min:10', // First post body
        ]);

        $thread = $this->forumService->createThread($validated);

        return redirect()->route('forum.show', $thread->id)
            ->with('success', 'Thread created successfully!');
    }

    /**
     * Display the specified thread with its posts
     */
    public function show($id)
    {
        $thread = $this->forumService->getThread($id);

        return view('forum.show', compact('thread'));
    }

    /**
     * Store a reply to a thread
     */
    public function reply(Request $request, $id)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:10',
            'is_answer' => 'nullable|boolean',
        ]);

        try {
            $this->forumService->addReply($id, $validated);

            return back()->with('success', 'Reply posted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
