@extends('layouts.app')

@section('title', 'فئات المنتدى')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">فئات المنتدى</h1>
        <a href="{{ route('forum.index') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة إلى المنتدى
        </a>
    </div>

    <p class="text-gray-600 mb-8">
        اختر إحدى الفئات التالية للاطلاع على المواضيع المتعلقة بها أو للمشاركة في النقاشات.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <a href="{{ route('forum.categories.show', $category->slug) }}" class="block p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    @if($category->icon)
                        <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <i class="{{ $category->icon }} text-xl"></i>
                        </div>
                    @else
                        <div class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                    @endif
                    <div class="mr-4">
                        <h3 class="text-xl font-medium text-gray-900">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="mt-1 text-gray-600">{{ Str::limit($category->description, 100) }}</p>
                        @endif
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <div class="flex items-center ml-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>{{ $category->topics->count() }} موضوع</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 p-8 bg-gray-50 rounded-lg text-center">
                <p class="text-gray-500">لا توجد فئات متاحة حالياً.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection