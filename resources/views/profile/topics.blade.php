@extends('layouts.app')

@section('title', 'مواضيع ' . $user->name)

@section('content')
<!-- رأس الصفحة -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div class="flex items-center">
            <h1 class="text-xl font-semibold text-gray-900">مواضيع {{ $user->name }}</h1>
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
            {{-- <a href="{{ route('profile.posts', $user->username) }}" class="{{ request()->routeIs('profile.posts') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8">
                الردود
            </a> --}}
            <a href="{{ route('profile.competitions', $user->username) }}" class="{{ request()->routeIs('profile.competitions') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8">
                المنافسات
            </a>
        </nav>
    </div>
</div>

<!-- قائمة المواضيع -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">جميع المواضيع ({{ $topics->total() }})</h2>
    </div>

    @if($topics->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($topics as $topic)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-medium text-gray-900 mb-1">
                                <a href="{{ route('forum.topics.show', $topic->slug) }}" class="hover:text-blue-600">
                                    {{ $topic->title }}
                                </a>
                            </h3>
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <span>{{ $topic->created_at->format('d/m/Y H:i') }}</span>
                                <span class="mx-2">•</span>
                                <a href="{{ route('forum.categories.show', $topic->category->slug) }}" class="text-gray-600 hover:text-blue-600">
                                    {{ $topic->category->name }}
                                </a>
                            </div>
                            @if($topic->excerpt)
                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit(strip_tags($topic->excerpt), 150) }}</p>
                            @endif
                            <div class="flex items-center text-xs text-gray-500">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    <span>{{ $topic->posts_count ?? $topic->posts->count() }} رد</span>
                                </div>
                                <span class="mx-2">•</span>
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>{{ $topic->views }} مشاهدة</span>
                                </div>
                                
                                @if($topic->is_solved)
                                    <span class="mx-2">•</span>
                                    <div class="flex items-center text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>تم الحل</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- @if(auth()->id() === $user->id)
                            <div class="flex-shrink-0 mr-4 flex space-x-2 space-x-reverse">
                                <a href="{{ route('forum.topics.edit', $topic->slug) }}" class="inline-flex items-center p-1.5 text-gray-500 bg-white rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </div>
                        @endif --}}
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- الترقيم -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $topics->links() }}
        </div>
    @else
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
            <p class="text-gray-500 mb-4">لا توجد مواضيع بعد.</p>
            
            @if(auth()->id() === $user->id)
                <a href="{{ route('forum.topics.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    إنشاء موضوع جديد
                </a>
            @endif
        </div>
    @endif
</div>
@endsection