@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">لوحة التحكم</h1>
    </div>

    <!-- ملخص الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-50 rounded-lg p-4 shadow-sm border border-blue-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-md bg-blue-200 text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                </div>
                <div class="mr-4">
                    <div class="text-sm font-medium text-gray-500">مواضيعي</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $topicsCount }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 rounded-lg p-4 shadow-sm border border-green-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-md bg-green-200 text-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <div class="mr-4">
                    <div class="text-sm font-medium text-gray-500">ردودي</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $postsCount }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-indigo-50 rounded-lg p-4 shadow-sm border border-indigo-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-md bg-indigo-200 text-indigo-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
                <div class="mr-4">
                    <div class="text-sm font-medium text-gray-500">مشاركاتي</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $submissionsCount }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-purple-50 rounded-lg p-4 shadow-sm border border-purple-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-md bg-purple-200 text-purple-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="mr-4">
                    <div class="text-sm font-medium text-gray-500">نقاطي</div>
                    <div class="text-xl font-semibold text-gray-900">{{ $user->points }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- المنافسات الجارية -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">المنافسات الجارية</h2>
                    <a href="{{ route('competitions.index', ['status' => 'active']) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                        عرض الكل
                    </a>
                </div>
                <div class="p-4">
                    @if($activeCompetitions->count() > 0)
                        <div class="space-y-3">
                            @foreach($activeCompetitions as $competition)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <h3 class="text-base font-medium text-gray-900 mb-1">
                                                <a href="{{ route('competitions.show', $competition->slug) }}" class="hover:text-green-600">
                                                    {{ $competition->title }}
                                                </a>
                                            </h3>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>تنتهي في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                            </div>
                                        </div>
                                        <div class="mt-2 sm:mt-0">
                                            <a href="{{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                شارك الآن
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                            <p class="text-gray-500">لا توجد منافسات جارية حالياً.</p>
                            <a href="{{ route('competitions.index') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                استعرض جميع المنافسات
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- آخر مواضيعي -->
            <div class="mt-6">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">آخر مواضيعي</h2>
                        <a href="{{ route('profile.topics', $user->username) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            عرض الكل
                        </a>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($recentTopics as $topic)
                            <div class="p-4 hover:bg-gray-50 transition-colors">
                                <h3 class="text-base font-medium text-gray-900 mb-1">
                                    <a href="{{ route('forum.topics.show', $topic->slug) }}" class="hover:text-blue-600">
                                        {{ $topic->title }}
                                    </a>
                                </h3>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>{{ $topic->created_at->diffForHumans() }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $topic->posts->count() }} رد</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $topic->views }} مشاهدة</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                                <p class="text-gray-500">لم تقم بإنشاء أي مواضيع بعد.</p>
                                <a href="{{ route('forum.topics.create') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    إنشاء موضوع جديد
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection