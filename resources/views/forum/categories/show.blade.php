@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
            <p class="text-gray-600 mt-1">{{ $category->description }}</p>
        </div>
        @auth
            <a href="{{ route('forum.topics.create') }}?category={{ $category->id }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                موضوع جديد في هذه الفئة
            </a>
        @else
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                تسجيل الدخول للمشاركة
            </a>
        @endauth
    </div>

    <div class="mb-4 flex justify-between items-center">
        <div class="flex space-x-2 space-x-reverse">
            <a href="{{ route('forum.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                الرئيسية
            </a>
            <span class="text-gray-400 mx-2">/</span>
            <a href="{{ route('forum.categories.index') }}" class="text-gray-500 hover:text-gray-700">
                الفئات
            </a>
            <span class="text-gray-400 mx-2">/</span>
            <span class="text-gray-700">{{ $category->name }}</span>
        </div>
        
        <div>
            <label for="sort" class="text-gray-600 ml-2">ترتيب حسب:</label>
            <select id="sort" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" onchange="window.location.href='?sort='+this.value">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                <option value="most_viewed" {{ request('sort') == 'most_viewed' ? 'selected' : '' }}>الأكثر مشاهدة</option>
                <option value="most_replied" {{ request('sort') == 'most_replied' ? 'selected' : '' }}>الأكثر ردودًا</option>
            </select>
        </div>
    </div>

    <!-- المواضيع -->
    <div class="bg-gray-50 rounded-lg overflow-hidden shadow">
        <ul class="divide-y divide-gray-200">
            @forelse($topics as $topic)
                <li class="hover:bg-gray-100 transition-colors">
                    <a href="{{ route('forum.topics.show', $topic->slug) }}" class="block p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($topic->user->name) }}&background=0D8ABC&color=fff" alt="{{ $topic->user->name }}">
                            </div>
                            <div class="mr-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $topic->title }}</h3>
                                    <div class="flex space-x-2 space-x-reverse">
                                        @if($topic->is_pinned)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                مثبت
                                            </span>
                                        @endif
                                        @if($topic->is_solved)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                تم الحل
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <span>
                                        بواسطة <span class="font-medium text-gray-900">{{ $topic->user->name }}</span>
                                    </span>
                                    <span class="mx-2">&middot;</span>
                                    <span>{{ $topic->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <p class="text-gray-600 line-clamp-1">
                                        {{ Str::limit(strip_tags($topic->content), 150) }}
                                    </p>
                                    <div class="flex items-center space-x-4 space-x-reverse text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>{{ $topic->views }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                            </svg>
                                            <span>{{ $topic->posts->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li class="p-8 text-center">
                    <p class="text-gray-500">لا توجد مواضيع في هذه الفئة بعد. كن أول من يبدأ النقاش!</p>
                    @auth
                        <a href="{{ route('forum.topics.create') }}?category={{ $category->id }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            إنشاء موضوع جديد
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            تسجيل الدخول للمشاركة
                        </a>
                    @endauth
                </li>
            @endforelse
        </ul>
    </div>
    
    <!-- ترقيم الصفحات -->
    <div class="mt-6">
        {{ $topics->appends(request()->query())->links() }}
    </div>
</div>
@endsection