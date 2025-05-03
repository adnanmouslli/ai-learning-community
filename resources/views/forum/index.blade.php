@extends('layouts.app')

@section('title', 'المنتدى التعليمي')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">المنتدى التعليمي</h1>
        @auth
            <a href="{{ route('forum.topics.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                موضوع جديد
            </a>
        @else
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                تسجيل الدخول للمشاركة
            </a>
        @endauth
    </div>

    <p class="text-gray-600 mb-8">
        مرحباً بك في المنتدى التعليمي لمجتمع الذكاء الصنعي. هنا يمكنك المشاركة في النقاشات حول مختلف مواضيع الذكاء الصنعي، طرح الأسئلة، ومشاركة معرفتك مع الآخرين.
    </p>


    <!-- الفئات -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">الفئات</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($categories as $category)
                <a href="{{ route('forum.categories.show', $category->slug) }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    @if($category->icon)
                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                    @else
                        <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                    @endif
                    <div class="mr-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $category->topics->count() }} موضوع</p>
                    </div>
                </a>
            @empty
                <div class="col-span-3 p-4 bg-gray-50 rounded-lg text-center">
                    <p class="text-gray-500">لا توجد فئات بعد.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- آخر المواضيع -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-900">آخر المواضيع</h2>
            <a href="{{ route('forum.topics.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                عرض جميع المواضيع
            </a>
        </div>
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
                                        <span class="mx-2">&middot;</span>
                                        <span>في <span class="font-medium text-gray-900">{{ $topic->category->name }}</span></span>
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
                        <p class="text-gray-500">لا توجد مواضيع بعد. كن أول من يبدأ النقاش!</p>
                        @auth
                            <a href="{{ route('forum.topics.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
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
            {{ $topics->links() }}
        </div>
    </div>
</div>
@endsection