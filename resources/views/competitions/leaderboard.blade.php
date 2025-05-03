@extends('layouts.app')

@section('title', 'لوحة المتصدرين - ' . $competition->title)

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
            <li class="inline-flex items-center">
                <a href="{{ route('competitions.show', $competition->slug) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    {{ $competition->title }}
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-500">لوحة المتصدرين</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
        <h1 class="text-xl font-semibold text-gray-900">لوحة المتصدرين - {{ $competition->title }}</h1>
        <a href="{{ route('competitions.show', $competition->slug) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
            العودة إلى المنافسة
        </a>
    </div>
    
    <div class="p-6">
        <!-- معلومات المسابقة -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-1">{{ $competition->title }}</h2>
                <div class="flex items-center text-sm text-gray-500">
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
                    <span class="mx-2">•</span>
                    @if($competition->status == 'active')
                    <span>ينتهي في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                    @elseif($competition->status == 'upcoming')
                    <span>يبدأ في {{ \Carbon\Carbon::parse($competition->start_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                    @else
                    <span>انتهت في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                    @endif
                    <span class="mx-2">•</span>
                    <span>{{ $competition->submissions->count() }} مشاركة</span>
                </div>
            </div>
            @if($competition->status == 'active')
                @auth
                    <a href="{{ route('competitions.submissions.create', $competition->slug) }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        شارك الآن
                    </a>
                @else
                    <a href="{{ route('login') }}?redirect={{ route('competitions.submissions.create', $competition->slug) }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        تسجيل الدخول للمشاركة
                    </a>
                @endauth
            @endif
        </div>

        <!-- قائمة المتصدرين -->
        @if($rankings->count() > 0)
            <div class="overflow-hidden border border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المركز
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المستخدم
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                أفضل درجة
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                آخر تحديث
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                عدد المشاركات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($rankings as $ranking)
                            <tr class="{{ auth()->check() && auth()->user()->id == $ranking->user_id ? 'bg-green-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-full {{ $ranking->rank <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }} text-sm font-medium">
                                            {{ $ranking->rank }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($ranking->user->name) }}&background=0D8ABC&color=fff" alt="{{ $ranking->user->name }}">
                                        </div>
                                        <div class="mr-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <a href="{{ route('profile.show', $ranking->user->username) }}" class="hover:text-blue-600">
                                                    {{ $ranking->user->name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ '@' . $ranking->user->username }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ round($ranking->score, 4) }}</div>
                                    <div class="text-xs text-gray-500">{{ $ranking->points_earned }} نقطة</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ranking->updated_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ranking->user->submissions?->where('competition_id', $competition->id)->count() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $rankings->links() }}
            </div>
        @else
            <div class="bg-white border border-gray-200 rounded-lg p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-500">لا توجد تقييمات بعد في هذه المنافسة.</p>
                @if($competition->status == 'active')
                    <div class="mt-4">
                        @auth
                            <a href="{{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                كن أول من يشارك
                            </a>
                        @else
                            <a href="{{ route('login') }}?redirect={{ route('competitions.submissions.create', $competition->slug) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                تسجيل الدخول للمشاركة
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection