@extends('layouts.app')

@section('title', 'ردود ' . $user->name)

@section('content')
<!-- رأس الصفحة -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div class="flex items-center">
            <h1 class="text-xl font-semibold text-gray-900">ردود {{ $user->name }}</h1>
        </div>
        <a href="{{ route('profile.show', $user->username) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للملف الشخصي
        </a>
    </div>
</div>

<!-- قائمة التبويب -->
<div class="mb-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex">
            <a href="{{ route('profile.show', $user->username) }}" class="{{ !request()->routeIs('profile.topics') && !request()->routeIs('profile.posts') && !request()->routeIs('profile.competitions') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                النشاط
            </a>
            <a href="{{ route('profile.topics', $user->username) }}" class="{{ request()->routeIs('profile.topics') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8">
                المواضيع
            </a>
            <a href="{{ route('profile.posts', $user->username) }}" class="{{ request()->routeIs('profile.posts') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8">
                الردود
            </a>
            <a href="{{ route('profile.competitions', $user->username) }}" class="{{ request()->routeIs('profile.competitions') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8">
                المنافسات
            </a>
        </nav>
    </div>
</div>

<!-- قائمة الردود -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">جميع الردود ({{ $posts->total() }})</h2>
    </div>

    @if($posts->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($posts as $post)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="mb-3">
                        <div class="flex items-center text-sm text-gray-500 mb-1">
                            <span>رد على
                                <a href="{{ route('forum.topics.show', $post->topic->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $post->topic->title }}
                                </a>
                            </span>
                        </div>
                        <div class="text-gray-600 text-sm">
                            <p class="mb-2">
                                {!! Str::limit(strip_tags($post->content), 300) !!}
                                @if(strlen(strip_tags($post->content)) > 300)
                                    <a href="{{ route('forum.topics.show', $post->topic->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        قراءة المزيد
                                    </a>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-xs text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $post->created_at->format('d/m/Y H:i') }}</span>
                            
                            <span class="mx-2">•</span>
                            <div class="flex items-center">
                                @if($post->upvotes - $post->downvotes > 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @elseif($post->upvotes - $post->downvotes < 0)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                    </svg>
                                @endif
                                <span>{{ $post->upvotes - $post->downvotes }} تقييم</span>
                            </div>
                            
                            @if($post->is_solution)
                                <span class="mx-2">•</span>
                                <div class="flex items-center text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>إجابة مقبولة</span>
                                </div>
                            @endif
                        </div>
                        
                        @if(auth()->id() === $user->id)
                            <div class="flex space-x-2 space-x-reverse">
                                <a href="{{ route('forum.topics.show', ['slug' => $post->topic->slug, 'post' => $post->id]) }}" class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200">
                                    الانتقال للرد
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- الترقيم -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $posts->links() }}
        </div>
    @else
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <p class="text-gray-500">لا توجد ردود بعد.</p>
        </div>
    @endif
</div>
@endsection