@extends('layouts.app')

@section('title', 'منافسات ' . $user->name)

@section('content')
<!-- رأس الصفحة -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div class="flex items-center">
            <h1 class="text-xl font-semibold text-gray-900">منافسات {{ $user->name }}</h1>
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

<!-- قائمة المنافسات -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">جميع المنافسات ({{ $rankings->total() }})</h2>
    </div>

    @if($rankings->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($rankings as $ranking)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center {{ $ranking->rank <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                <span class="text-xl font-bold">{{ $ranking->rank }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0 mr-4">
                            <h3 class="text-base font-medium text-gray-900 mb-1">
                                <a href="{{ route('competitions.show', $ranking->competition->slug) }}" class="hover:text-green-600">
                                    {{ $ranking->competition->title }}
                                </a>
                            </h3>
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($ranking->competition->status == 'active')
                                        bg-green-100 text-green-800
                                    @elseif($ranking->competition->status == 'upcoming')
                                        bg-blue-100 text-blue-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ __('competitions.status.' . $ranking->competition->status) ?? $ranking->competition->status }}
                                </span>
                                <span class="mx-2">•</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($ranking->competition->end_date)->format('d/m/Y') }}</span>
                            </div>
                            
                            <div class="mt-3">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ min(round($ranking->score), 100) }}%"></div>
                                    </div>
                                    <span class="mr-2 text-sm font-semibold text-gray-900">{{ round($ranking->score, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 mr-4">
                            <a href="{{ route('competitions.leaderboard', $ranking->competition->slug) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                المتصدرون
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- الترقيم -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $rankings->links() }}
        </div>
    @else
        <div class="p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
            </svg>
            <p class="text-gray-500">لم يشارك في أي منافسات بعد.</p>
            
            @if(auth()->id() === $user->id)
                <a href="{{ route('competitions.index') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    استعرض المنافسات
                </a>
            @endif
        </div>
    @endif
</div>
@endsection