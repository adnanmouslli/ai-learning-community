@extends('layouts.app')

@section('title', 'الملف الشخصي - ' . $user->name)

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- غلاف الملف الشخصي -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-800 h-48 relative">
        <!-- صورة المستخدم -->
        <div class="absolute left-0 right-0 -bottom-16 flex justify-center">
            <img class="h-32 w-32 rounded-full border-4 border-white" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff&size=128" alt="{{ $user->name }}">
        </div>
    </div>
    
    <!-- معلومات المستخدم -->
    <div class="pt-20 px-6 pb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
        <p class="text-gray-500">{{ '@' . $user->username }}</p>
        
        @if($user->bio)
            <div class="mt-4 max-w-2xl mx-auto">
                <p class="text-gray-600">{{ $user->bio }}</p>
            </div>
        @endif
        
        <div class="mt-6 flex items-center justify-center space-x-6 space-x-reverse">
            <div class="text-center">
                <span class="block text-2xl font-bold text-gray-900">{{ $user->topics->count() }}</span>
                <span class="text-sm text-gray-500">موضوع</span>
            </div>
            <div class="text-center">
                <span class="block text-2xl font-bold text-gray-900">{{ $user->posts->count() }}</span>
                <span class="text-sm text-gray-500">رد</span>
            </div>
            <div class="text-center">
                <span class="block text-2xl font-bold text-gray-900">{{ $user->points }}</span>
                <span class="text-sm text-gray-500">نقطة</span>
            </div>
            <div class="text-center">
                <span class="block text-2xl font-bold text-gray-900">{{ $user->competitionRankings->count() }}</span>
                <span class="text-sm text-gray-500">منافسة</span>
            </div>
        </div>
        
        {{-- @if(auth()->id() === $user->id)
            <div class="mt-6">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    تعديل الملف الشخصي
                </a>
            </div>
        @endif --}}
    </div>
</div>

<!-- قائمة التبويب -->
<div class="mt-8">
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

<!-- محتوى النشاط -->
<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- آخر المواضيع -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">آخر المواضيع</h2>
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
                    <div class="p-6 text-center">
                        <p class="text-gray-500">لا توجد مواضيع بعد.</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- آخر الردود -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">آخر الردود</h2>
                <a href="{{ route('profile.posts', $user->username) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    عرض الكل
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentPosts as $post)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center text-sm text-gray-500 mb-1">
                            <span>رد على
                                <a href="{{ route('forum.topics.show', $post->topic->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $post->topic->title }}
                                </a>
                            </span>
                        </div>
                        <div class="text-gray-600 text-sm">
                            {!! Str::limit(strip_tags($post->content), 150) !!}
                        </div>
                        <div class="flex items-center text-xs text-gray-500 mt-2">
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                            <span class="mx-2">•</span>
                            <span>{{ $post->upvotes - $post->downvotes }} تقييم</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center">
                        <p class="text-gray-500">لا توجد ردود بعد.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="space-y-6">
        <!-- معلومات المستخدم -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">معلومات المستخدم</h2>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">تاريخ الانضمام</dt>
                        <dd class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y') }}</dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">الرتبة</dt>
                        <dd class="text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $user->rank ?? 'مبتدئ' }}
                            </span>
                        </dd>
                    </div>
                    
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">النقاط</dt>
                        <dd class="text-sm text-gray-900">{{ $user->points }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        
        <!-- المنافسات -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">أفضل المنافسات</h2>
                <a href="{{ route('profile.competitions', $user->username) }}" class="text-sm text-green-600 hover:text-green-800 font-medium">
                    عرض الكل
                </a>
            </div>
            <div class="p-6">
                @if($topCompetitions->count() > 0)
                    <ul class="space-y-4">
                        @foreach($topCompetitions as $ranking)
                            <li class="flex items-center space-x-4 space-x-reverse">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full {{ $ranking->rank <= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }} text-sm font-medium">
                                    {{ $ranking->rank }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('competitions.show', $ranking->competition->slug) }}" class="text-sm font-medium text-gray-900 hover:text-green-600">
                                        {{ $ranking->competition->title }}
                                    </a>
                                    <p class="text-xs text-gray-500">
                                        {{ $ranking->score }} نقطة
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center py-4">
                        <p class="text-sm text-gray-500">لم يشارك في أي منافسات بعد.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection