@extends('layouts.app')

@section('title', 'تقييم مشاركات - ' . $competition->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('judge.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                        <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        لوحة المحكم
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500">تقييم مشاركات {{ $competition->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">تقييم مشاركات - {{ $competition->title }}</h1>
        <a href="{{ route('judge.dashboard') }}" class="bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
            العودة للوحة المحكم
        </a>
    </div>
    
    <!-- معلومات المنافسة -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <span class="block text-sm text-gray-500">حالة المنافسة</span>
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
                <span class="block text-sm text-gray-500">تاريخ البدء والانتهاء</span>
                <span class="text-sm font-medium" dir="ltr">
                    {{ $competition->start_date->format('Y-m-d') }} - {{ $competition->end_date->format('Y-m-d') }}
                </span>
            </div>
            
            <div>
                <span class="block text-sm text-gray-500">عدد المشاركات</span>
                <span class="text-sm font-medium">
                    {{ $pendingSubmissions->total() }} بانتظار التقييم | {{ $evaluatedSubmissions->total() }} تم تقييمها
                </span>
            </div>
        </div>
    </div>
    
    <!-- تبويب المشاركات -->
    <div x-data="{ tab: 'pending' }">
        <div class="bg-white rounded-lg shadow-sm mb-4">
            <div class="flex border-b">
                <button @click="tab = 'pending'" 
                    :class="{ 'border-b-2 border-blue-500 text-blue-600': tab === 'pending', 'text-gray-500': tab !== 'pending' }" 
                    class="py-4 px-6 font-medium text-sm focus:outline-none">
                    المشاركات بانتظار التقييم 
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs ml-2 bg-blue-100 text-blue-800 rounded-full">
                        {{ $pendingSubmissions->total() }}
                    </span>
                </button>
                
                <button @click="tab = 'evaluated'" 
                    :class="{ 'border-b-2 border-green-500 text-green-600': tab === 'evaluated', 'text-gray-500': tab !== 'evaluated' }" 
                    class="py-4 px-6 font-medium text-sm focus:outline-none">
                    المشاركات المقيّمة 
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs ml-2 bg-green-100 text-green-800 rounded-full">
                        {{ $evaluatedSubmissions->total() }}
                    </span>
                </button>
            </div>
        </div>
        
        <div x-show="tab === 'pending'" class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($pendingSubmissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المشاركة
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المستخدم
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    تاريخ التقديم
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendingSubmissions as $submission)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $submission->title }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($submission->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->user->name }}">
                                            </div>
                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ '@' . $submission->user->username }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                        {{ $submission->created_at->format('Y-m-d H:i') }}
                                        <div class="text-xs text-gray-500">{{ $submission->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('competitions.submissions.show', $submission->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            تقييم
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pendingSubmissions->appends(['evaluated_page' => $evaluatedSubmissions->currentPage()])->links() }}
                </div>
            @else
                <div class="p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-gray-500">لا توجد مشاركات بانتظار التقييم حالياً.</p>
                </div>
            @endif
        </div>
        
        <div x-show="tab === 'evaluated'" class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($evaluatedSubmissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المشاركة
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    المستخدم
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    النتيجة
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    تاريخ التقييم
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    الإجراءات
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($evaluatedSubmissions as $submission)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $submission->title }}</div>
                                        <div class="text-xs text-gray-500">{{ Str::limit($submission->description, 50) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->user->name }}">
                                            </div>
                                            <div class="mr-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ '@' . $submission->user->username }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium
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
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            الدقة: {{ round($submission->accuracy_score, 2) }} | الأداء: {{ round($submission->performance_score, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                        {{ $submission->evaluated_at ? \Carbon\Carbon::parse($submission->evaluated_at)->format('Y-m-d H:i') : '-' }}
                                        <div class="text-xs text-gray-500">{{ $submission->evaluated_at ? \Carbon\Carbon::parse($submission->evaluated_at)->diffForHumans() : '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('competitions.submissions.show', $submission->id) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            عرض
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $evaluatedSubmissions->appends(['pending_page' => $pendingSubmissions->currentPage()])->links() }}
                </div>
            @else
                <div class="p-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2 text-gray-500">لا توجد مشاركات تم تقييمها بعد.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection