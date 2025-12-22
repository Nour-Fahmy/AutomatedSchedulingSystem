@extends('layouts.layout')

@section('title', $thread->title . ' - Forum - AlignUp')

@section('content')
<div class="bg-gradient-to-br from-indigo-50 to-blue-100 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('forum.index') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Back to Forum
            </a>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $thread->title }}</h1>
                        <div class="flex items-center gap-4 text-sm text-gray-600">
                            <span>
                                <i class="fas fa-user mr-1"></i>
                                {{ $thread->creator->name }}
                            </span>
                            <span>
                                <i class="fas fa-clock mr-1"></i>
                                {{ $thread->created_at->format('M d, Y \a\t g:i A') }}
                            </span>
                            @if($thread->service)
                                <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs font-semibold">
                                    {{ $thread->service->name }}
                                </span>
                            @endif
                            @if($thread->is_locked)
                                <span class="bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-semibold">
                                    <i class="fas fa-lock mr-1"></i>Locked
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts -->
        <div class="space-y-4 mb-8">
            @foreach($thread->posts as $post)
                <div class="bg-white rounded-xl shadow-lg p-6 {{ $post->is_answer ? 'border-l-4 border-green-500' : '' }}">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-gray-900">{{ $post->author->name }}</span>
                                    @if($post->is_answer)
                                        <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs font-semibold">
                                            <i class="fas fa-check-circle mr-1"></i>Answer
                                        </span>
                                    @endif
                                </div>
                                <span class="text-sm text-gray-500">
                                    {{ $post->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <div class="text-gray-700 whitespace-pre-wrap">{{ $post->body }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reply Form -->
        @if(!$thread->is_locked)
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Post a Reply</h2>
                <form action="{{ route('forum.reply', $thread->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <textarea name="body" rows="6" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 @error('body') border-red-500 @enderror"
                                  placeholder="Write your reply here...">{{ old('body') }}</textarea>
                        @error('body')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold">
                            <i class="fas fa-paper-plane mr-2"></i>Post Reply
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-gray-100 rounded-xl shadow-lg p-6 text-center">
                <i class="fas fa-lock text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600 font-semibold">This thread is locked and no longer accepts replies.</p>
            </div>
        @endif

        @if(session('success'))
            <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
    </div>
</div>
@endsection


