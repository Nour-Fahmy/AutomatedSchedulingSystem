@extends('layouts.layout')

@section('title', 'Forum - AlignUp')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Forum Discussions</h1>
                <p class="text-xl text-gray-600">Join conversations and get help from the community</p>
            </div>
            <a href="{{ route('forum.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold">
                <i class="fas fa-plus-circle mr-2"></i>New Thread
            </a>
        </div>

        <!-- Filter by Service -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('forum.index') }}" class="flex items-center gap-4">
                <label for="service_id" class="text-gray-700 font-medium">Filter by Service:</label>
                <select name="service_id" id="service_id" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="">All Services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Threads List -->
        @if($threads->count() > 0)
            <div class="space-y-4">
                @foreach($threads as $thread)
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <a href="{{ route('forum.show', $thread->id) }}" class="text-xl font-bold text-indigo-600 hover:text-indigo-800">
                                        {{ $thread->title }}
                                    </a>
                                    @if($thread->is_locked)
                                        <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-semibold">
                                            <i class="fas fa-lock mr-1"></i>Locked
                                        </span>
                                    @endif
                                    @if($thread->service)
                                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs font-semibold">
                                            {{ $thread->service->name }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $thread->creator->name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $thread->created_at->diffForHumans() }}
                                    </span>
                                    <span>
                                        <i class="fas fa-comments mr-1"></i>
                                        {{ $thread->posts->count() }} {{ Str::plural('reply', $thread->posts->count()) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $threads->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No threads yet</h3>
                <p class="text-gray-600 mb-6">Be the first to start a discussion!</p>
                <a href="{{ route('forum.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold inline-block">
                    <i class="fas fa-plus-circle mr-2"></i>Create First Thread
                </a>
            </div>
        @endif
    </div>
</div>
@endsection


