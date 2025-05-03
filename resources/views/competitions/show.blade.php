@extends('layouts.app')

@section('title', $competition->title)

@section('content')
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('competitions.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    المنافسات
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-500 truncate max-w-xs">{{ $competition->title }}</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<!-- رأس المنافسة -->
<div class="bg-gradient-to-r from-green-600 to-blue-600 rounded-lg shadow-lg overflow-hidden mb-8">
    <div class="px-6 py-8 text-white">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between">
            <div class="mb-6 md:mb-0">
                <h1 class="text-3xl font-bold mb-2">{{ $competition->title }}</h1>
                <div class="flex items-center text-white text-opacity-90 mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white bg-opacity-20 mr-2">
                        {{ __('competitions.status.' . $competition->status) }}
                    </span>
                    <span class="mr-2">•</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    @if($competition->status == 'upcoming')
                        <span>تبدأ في {{ \Carbon\Carbon::parse($competition->start_date)->diffForHumans() }}</span>
                    @elseif($competition->status == 'active')
                        <span>تنتهي في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>
                    @else
                        <span>انتهت في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>
                    @endif
                    <span class="mr-2">•</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>{{ $competition->submissions->count() }} مشاركة</span>
                </div>
                <p class="text-white text-opacity-90">{{ Str::limit(strip_tags($competition->description), 200) }}</p>
            </div>
            <div class="flex flex-col space-y-2">
                <a href="{{ route('competitions.leaderboard', $competition->slug) }}" class="inline-flex items-center justify-center px-4 py-2 border border-white border-opacity-60 rounded-md text-sm font-medium text-white hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    المتصدرون
                </a>
                @if($competition->status == 'active')
                    @auth
                        <a href="{{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            شارك الآن
                        </a>
                    @else
                        <a href="{{ route('login') }}?redirect={{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            تسجيل الدخول للمشاركة
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</div>

<!-- محتوى المنافسة -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- القسم الرئيسي -->
    <div class="lg:col-span-2 space-y-6">
        <!-- تفاصيل المنافسة -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">تفاصيل المنافسة</h2>
            </div>
            <div class="p-6">
                <div class="prose prose-green max-w-none">
                    {!! $competition->description !!}
                </div>
            </div>
        </div>

        <!-- قواعد المنافسة -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">قواعد المنافسة</h2>
            </div>
            <div class="p-6">
                <div class="prose prose-green max-w-none">
                    {!! $competition->rules !!}
                </div>
            </div>
        </div>

        <!-- معايير التقييم -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">معايير التقييم</h2>
            </div>
            <div class="p-6">
                <div class="prose prose-green max-w-none">
                    {!! $competition->evaluation_criteria !!}
                </div>
            </div>
        </div>

        <!-- البيانات المستخدمة -->
        @if($competition->dataset_description)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">البيانات المستخدمة</h2>
                </div>
                <div class="p-6">
                    <div class="prose prose-green max-w-none mb-4">
                        {!! $competition->dataset_description !!}
                    </div>
                    
                    @if($competition->dataset_url)
                        <a href="{{ $competition->dataset_url }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            تنزيل البيانات
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- الشريط الجانبي -->
    <div class="space-y-6">
        <!-- معلومات المنافسة -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">معلومات المنافسة</h2>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">الحالة</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($competition->status == 'active')
                                    bg-green-100 text-green-800
                                @elseif($competition->status == 'upcoming')
                                    bg-blue-100 text-blue-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif
                            ">
                                {{ __('competitions.status.' . $competition->status) }}
                            </span>
                        </dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">تاريخ البدء</dt>
                        <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($competition->start_date)->format('d/m/Y') }}</dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">تاريخ الانتهاء</dt>
                        <dd class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($competition->end_date)->format('d/m/Y') }}</dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">عدد المشاركات</dt>
                        <dd class="text-sm text-gray-900">{{ $competition->submissions->count() }} مشاركة</dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">الحد الأقصى للتقديمات اليومية</dt>
                        <dd class="text-sm text-gray-900">{{ $competition->max_daily_submissions }} تقديمات</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- أفضل المشاركين -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">أفضل المشاركين</h2>
            </div>
            <div class="p-6">
                @if($competition->rankings && $competition->rankings->count() > 0)
                    <ul class="space-y-4">
                        @foreach($competition->rankings->sortBy('rank')->take(5) as $ranking)
                            <li class="flex items-center space-x-4 space-x-reverse">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full {{ $ranking->rank <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }} text-sm font-medium">
                                    {{ $ranking->rank }}
                                </span>
                                <div class="flex-shrink-0">
                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($ranking->user->name) }}&background=0D8ABC&color=fff" alt="{{ $ranking->user->name }}">
                                </div>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('profile.show', $ranking->user->username) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                        {{ $ranking->user->name }}
                                    </a>
                                </div>
                                <div class="flex-shrink-0 text-sm font-medium text-gray-900">
                                    {{ round($ranking->score, 4) }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-4 text-center">
                        <a href="{{ route('competitions.leaderboard', $competition->slug) }}" class="text-sm font-medium text-green-600 hover:text-green-800">
                            عرض لوحة المتصدرين كاملة
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">لا توجد تقييمات بعد</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- تقديماتي -->
        @auth
            @if(isset($userSubmissions) && $userSubmissions->count() > 0)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">تقديماتي</h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            @foreach($userSubmissions as $submission)
                                <li class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $submission->title }}</h4>
                                        <span class="text-xs text-gray-500">{{ $submission->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="text-sm">
                                            @if($submission->final_score !== null)
                                                <span class="font-medium text-gray-900">الدرجة: {{ round($submission->final_score, 4) }}</span>
                                            @else
                                                <span class="text-yellow-600">قيد التقييم</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('competitions.submissions.show', $submission->id) }}" class="text-sm text-green-600 hover:text-green-800">
                                            التفاصيل
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if($competition->status == 'active')
                            @php
                                $dailySubmissionsCount = auth()->user()->submissionsToday($competition);
                            @endphp
                            @if($dailySubmissionsCount < $competition->max_daily_submissions)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        تقديم مشاركة جديدة
                                    </a>
                                </div>
                            @else
                                <div class="mt-4 text-center">
                                    <p class="text-sm text-gray-500">لقد وصلت للحد الأقصى للتقديمات اليومية ({{ $competition->max_daily_submissions }})</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @elseif($competition->status == 'active')
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">مشاركاتي</h2>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-sm text-gray-500 mb-4">لم تقم بتقديم أي مشاركات في هذه المنافسة بعد.</p>
                        <a href="{{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            تقديم مشاركة جديدة
                        </a>
                    </div>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection