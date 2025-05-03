@extends('layouts.app')

@section('title', 'لوحة المحكمين')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">لوحة المحكم</h1>
    
    <!-- نظرة عامة -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-green-50 border-b border-green-100">
                <h2 class="text-lg font-medium text-gray-900">المشاركات بانتظار التقييم</h2>
            </div>
            <div class="p-6 flex flex-col items-center justify-center">
                <span class="text-3xl font-bold text-green-600">{{ $pendingSubmissions }}</span>
                <span class="text-sm text-gray-500 mt-2">مشاركة</span>
                <a href="{{ route('judge.submissions.pending') }}" class="mt-4 text-sm text-green-600 hover:underline">عرض المشاركات</a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-blue-50 border-b border-blue-100">
                <h2 class="text-lg font-medium text-gray-900">التقييمات المكتملة</h2>
            </div>
            <div class="p-6 flex flex-col items-center justify-center">
                <span class="text-3xl font-bold text-blue-600">{{ $completedEvaluations }}</span>
                <span class="text-sm text-gray-500 mt-2">تقييم</span>
                <a href="{{ route('judge.submissions.evaluated') }}" class="mt-4 text-sm text-blue-600 hover:underline">عرض التقييمات</a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-purple-50 border-b border-purple-100">
                <h2 class="text-lg font-medium text-gray-900">المنافسات النشطة</h2>
            </div>
            <div class="p-6 flex flex-col items-center justify-center">
                <span class="text-3xl font-bold text-purple-600">{{ $activeCompetitions }}</span>
                <span class="text-sm text-gray-500 mt-2">منافسة</span>
                <a href="{{ route('competitions.index', ['status' => 'active']) }}" class="mt-4 text-sm text-purple-600 hover:underline">عرض المنافسات</a>
            </div>
        </div>
    </div>
    
    <!-- المشاركات بانتظار التقييم -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">أحدث المشاركات بانتظار التقييم</h2>
            <a href="{{ route('judge.submissions.pending') }}" class="text-sm text-blue-600 hover:underline">عرض الكل</a>
        </div>
        
        @if($pendingSubmissionsList->count() > 0)
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
                                المنافسة
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
                        @foreach($pendingSubmissionsList as $submission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($submission->title, 30) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->user->name }}">
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ Str::limit($submission->competition->title, 25) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                    {{ $submission->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('competitions.submissions.show', $submission->id) }}" class="text-blue-600 hover:text-blue-900">تقييم</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center">
                <p class="text-gray-500">لا توجد مشاركات بانتظار التقييم حالياً.</p>
            </div>
        @endif
    </div>
    
    <!-- المنافسات النشطة -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">المنافسات النشطة</h2>
            <a href="{{ route('competitions.index', ['status' => 'active']) }}" class="text-sm text-blue-600 hover:underline">عرض الكل</a>
        </div>
        
        @if($activeCompetitionsList->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                @foreach($activeCompetitionsList as $competition)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-2">{{ $competition->title }}</h3>
                        <div class="text-sm text-gray-500 mb-3">
                            <div class="mb-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    جارية
                                </span>
                                <span class="mr-2">تنتهي في {{ $competition->end_date->diffForHumans() }}</span>
                            </div>
                            <div>{{ $competition->pending_submissions_count }} مشاركة بانتظار التقييم</div>
                        </div>
                        <div class="flex justify-end">
                            <a href="{{ route('judge.competitions.submissions', $competition->id) }}" class="text-sm text-blue-600 hover:underline">تقييم المشاركات</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center">
                <p class="text-gray-500">لا توجد منافسات نشطة حالياً.</p>
            </div>
        @endif
    </div>
</div>
@endsection