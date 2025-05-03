@extends('layouts.app')

@section('title', 'تقييم المشاركات - ' . $competition->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                        <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        لوحة التحكم
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.competitions.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                        <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        المنافسات
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.competitions.show', $competition->id) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                        <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        {{ $competition->title }}
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500">تقييم المشاركات</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">تقييم المشاركات - {{ $competition->title }}</h1>
        <a href="{{ route('admin.competitions.show', $competition->id) }}" class="bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
            العودة للمنافسة
        </a>
    </div>
    
    <!-- معلومات المنافسة -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <span class="block text-sm text-gray-500">الحالة</span>
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
            </div>
            
            <div>
                <span class="block text-sm text-gray-500">تاريخ البدء</span>
                <span class="text-sm font-medium" dir="ltr">{{ $competition->start_date->format('Y-m-d H:i') }}</span>
            </div>
            
            <div>
                <span class="block text-sm text-gray-500">تاريخ الانتهاء</span>
                <span class="text-sm font-medium" dir="ltr">{{ $competition->end_date->format('Y-m-d H:i') }}</span>
            </div>
            
            <div>
                <span class="block text-sm text-gray-500">إجمالي المشاركات</span>
                <span class="text-sm font-medium">{{ $competition->submissions->count() }}</span>
            </div>
        </div>
    </div>
    
    <!-- تبويب المشاركات -->
    <div x-data="{ tab: 'pending' }">
        <!-- أزرار التبويب -->
        <div class="bg-white rounded-lg shadow-sm mb-4 border-b border-gray-200">
            <div class="flex">
                <button @click="tab = 'pending'" :class="{ 'border-b-2 border-green-500 text-green-600': tab === 'pending', 'text-gray-600': tab !== 'pending' }" class="py-4 px-6 font-medium text-sm focus:outline-none">
                    المشاركات بانتظار التقييم
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs bg-yellow-100 text-yellow-800 rounded-full mr-2">{{ $pendingSubmissions->count() }}</span>
                </button>
                
                <button @click="tab = 'evaluated'" :class="{ 'border-b-2 border-green-500 text-green-600': tab === 'evaluated', 'text-gray-600': tab !== 'evaluated' }" class="py-4 px-6 font-medium text-sm focus:outline-none">
                    المشاركات المقيمة
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs bg-green-100 text-green-800 rounded-full mr-2">{{ $evaluatedSubmissions->count() }}</span>
                </button>
            </div>
        </div>
        
        <!-- محتوى التبويب -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- المشاركات بانتظار التقييم -->
            <div x-show="tab === 'pending'">
                @if($pendingSubmissions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        المشاركة
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        المستخدم
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        تاريخ التقديم
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendingSubmissions as $submission)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $submission->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($submission->description, 60) ?: 'لا يوجد وصف' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->user->name }}">
                                                </div>
                                                <div class="mr-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $submission->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ '@' . $submission->user->username }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                            {{ $submission->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('competitions.submissions.show', $submission->id) }}" class="text-blue-600 hover:text-blue-900 ml-4">عرض وتقييم</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $pendingSubmissions->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center p-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-lg">لا توجد مشاركات بانتظار التقييم.</p>
                    </div>
                @endif
            </div>
            
            <!-- المشاركات المقيمة -->
            <div x-show="tab === 'evaluated'">
                @if($evaluatedSubmissions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        المشاركة
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        المستخدم
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        النتيجة النهائية
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        تاريخ التقييم
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                        الإجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($evaluatedSubmissions as $submission)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $submission->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($submission->description, 60) ?: 'لا يوجد وصف' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->user->name }}">
                                                </div>
                                                <div class="mr-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $submission->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ '@' . $submission->user->username }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="text-sm font-medium
                                                @if($submission->final_score >= 80)
                                                    text-green-600
                                                @elseif($submission->final_score >= 60)
                                                    text-blue-600
                                                @elseif($submission->final_score >= 40)
                                                    text-yellow-600
                                                @else
                                                    text-red-600
                                                @endif
                                            ">
                                                {{ round($submission->final_score, 2) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                            {{ $submission->evaluated_at ? \Carbon\Carbon::parse($submission->evaluated_at)->format('Y-m-d H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('competitions.submissions.show', $submission->id) }}" class="text-blue-600 hover:text-blue-900 ml-4">عرض</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $evaluatedSubmissions->links() }}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center p-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-500 text-lg">لا توجد مشاركات تم تقييمها.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection