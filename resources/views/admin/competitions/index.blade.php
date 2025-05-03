@extends('layouts.app')

@section('title', 'إدارة المنافسات')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">إدارة المنافسات</h1>
        <a href="{{ route('admin.competitions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            إضافة منافسة جديدة
        </a>
    </div>
    
    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">تصفية المنافسات</h2>
        </div>
        <div class="p-4">
            <form action="{{ route('admin.competitions.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="w-full md:w-64">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                    <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>قادم</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                    </select>
                </div>
                
                <div class="w-full md:w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">بحث</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="اسم المنافسة">
                </div>
                
                <div class="w-full md:w-64">
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                    <select name="sort" id="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>الأحدث أولاً</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                        <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>أبجدياً (أ-ي)</option>
                        <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>أبجدياً (ي-أ)</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        بحث
                    </button>
                    
                    <a href="{{ route('admin.competitions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-2">
                        إعادة ضبط
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Table of Competitions -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($competitions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                اسم المنافسة
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ البدء والانتهاء
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المشاركات
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ الإنشاء
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($competitions as $competition)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $competition->title }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($competition->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($competition->status == 'active')
                                            bg-green-100 text-green-800
                                        @elseif($competition->status == 'upcoming')
                                            bg-blue-100 text-blue-800
                                        @elseif($competition->status == 'completed')
                                            bg-gray-100 text-gray-800
                                        @else
                                            bg-yellow-100 text-yellow-800
                                        @endif
                                    ">
                                        {{ __('competitions.status.' . $competition->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                    <div>{{ $competition->start_date->format('Y-m-d') }}</div>
                                    <div>{{ $competition->end_date->format('Y-m-d') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $competition->submissions_count ?? 0 }} مشاركة</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $competition->pending_submissions_count ?? 0 }} بانتظار التقييم
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                    {{ $competition->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    <div class="flex items-center justify-center space-x-3 space-x-reverse">
                                        <a href="{{ route('admin.competitions.edit', $competition->id) }}" class="text-blue-600 hover:text-blue-900" title="تعديل">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.competitions.submissions', $competition->id) }}" class="text-green-600 hover:text-green-900" title="عرض المشاركات">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </a>
                                        
                                        @if($competition->status != 'active')
                                            <form action="{{ route('admin.competitions.status', $competition->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" class="text-green-600 hover:text-green-900" title="تنشيط">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.competitions.status', $competition->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="إنهاء">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.competitions.destroy', $competition->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنافسة?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="حذف">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $competitions->withQueryString()->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 text-gray-500">لا توجد منافسات متاحة. قم بإنشاء منافسة جديدة.</p>
            </div>
        @endif
    </div>
    </div>
    @endsection