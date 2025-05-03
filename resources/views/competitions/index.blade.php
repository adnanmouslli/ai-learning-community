@extends('layouts.app')

@section('title', 'المنافسات')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">المنافسات والتحديات</h1>
    </div>

    <p class="text-gray-600 mb-8">
        انضم إلى منافساتنا في مجال الذكاء الصنعي، حيث يمكنك تطبيق معرفتك وتحسين مهاراتك من خلال حل مشاكل حقيقية في مجال تصنيف الصور، تحليل النصوص، والتنبؤ بالبيانات.
    </p>

    <!-- قسم البحث -->
    <div class="mb-8">
        <form action="{{ route('competitions.index') }}" method="GET" class="flex w-full">
            <input type="text" name="search" placeholder="ابحث في المنافسات..." value="{{ request('search') }}" class="flex-grow px-4 py-2 border border-gray-300 rounded-r-md focus:ring-green-500 focus:border-green-500">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-l-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <!-- تصفية المنافسات -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex">
                <a href="{{ route('competitions.index') }}" class="{{ !request('status') ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    الكل
                </a>
                <a href="{{ route('competitions.index', ['status' => 'active']) }}" class="{{ request('status') == 'active' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8">
                    المنافسات الجارية
                </a>
                <a href="{{ route('competitions.index', ['status' => 'upcoming']) }}" class="{{ request('status') == 'upcoming' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8">
                    المنافسات القادمة
                </a>
                <a href="{{ route('competitions.index', ['status' => 'completed']) }}" class="{{ request('status') == 'completed' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm mr-8">
                    المنافسات المنتهية
                </a>
            </nav>
        </div>
    </div>

    <!-- المنافسات المميزة -->
    @if($featuredCompetitions->count() > 0 && !request('status') && !request('search'))
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">المنافسات المميزة</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($featuredCompetitions as $competition)
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-md overflow-hidden">
                        <div class="p-6 text-white">
                            <h3 class="text-xl font-bold mb-2">{{ $competition->title }}</h3>
                            <p class="mb-4 opacity-90">{{ Str::limit($competition->description, 120) }}</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-200 text-blue-800">
                                        {{ __('competitions.status.' . $competition->status) }}
                                    </span>
                                    <span class="text-sm opacity-80 mt-2 block">
                                        @if($competition->status == 'upcoming')
                                        <span>تبدأ في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                                        @elseif($competition->status == 'active')
                                        <span>تنتهي في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                                        @else
                                        <span>انتهت في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                                        @endif
                                    </span>
                                </div>
                                <a href="{{ route('competitions.show', $competition->slug) }}" class="inline-flex items-center px-3 py-1.5 border border-white border-opacity-60 rounded-md text-sm font-medium text-white hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                                    التفاصيل
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- قائمة المنافسات -->
    <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            @if(request('status') == 'active')
                المنافسات الجارية
            @elseif(request('status') == 'upcoming')
                المنافسات القادمة
            @elseif(request('status') == 'completed')
                المنافسات المنتهية
            @else
                جميع المنافسات
            @endif
            
            @if(request('search'))
                - نتائج البحث عن "{{ request('search') }}"
            @endif
        </h2>
        
        <div class="grid grid-cols-1 gap-4">
            @forelse($competitions as $competition)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="sm:flex sm:items-start sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('competitions.show', $competition->slug) }}" class="hover:text-blue-600">
                                        {{ $competition->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 mb-4">{{ Str::limit($competition->description, 180) }}</p>
                                
                                <div class="flex flex-wrap items-center text-sm text-gray-500 gap-4">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        @if($competition->status == 'upcoming')
                                        <span>تبدأ في{{ \Carbon\Carbon::parse($competition->start_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                                        @elseif($competition->status == 'active')
                                            <span>
                                                <span>بدأت{{ \Carbon\Carbon::parse($competition->start_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                                                <span>وتنتهي {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                                            </span>
                                        @else
                                        <span>انتهت في{{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span>{{ $competition->submissions->count() }} مشاركة</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 sm:mt-0 sm:flex sm:flex-col sm:items-end">
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
                                
                                <div class="mt-4 flex space-x-2 space-x-reverse">
                                    <a href="{{ route('competitions.show', $competition->slug) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        التفاصيل
                                    </a>
                                    @if($competition->status == 'active')
                                        <a href="{{ route('competitions.leaderboard', $competition->slug) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            المتصدرون
                                        </a>
                                        @auth
                                            <a href="{{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                شارك الآن
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}?redirect={{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                تسجيل الدخول للمشاركة
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-200 rounded-lg p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500">
                        @if(request('search'))
                            لم يتم العثور على نتائج تطابق بحثك. جرب كلمات مفتاحية أخرى.
                        @else
                            لا توجد منافسات 
                            @if(request('status') == 'active')
                                جارية
                            @elseif(request('status') == 'upcoming')
                                قادمة
                            @elseif(request('status') == 'completed')
                                منتهية
                            @endif
                            حالياً.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-6">
            {{ $competitions->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection