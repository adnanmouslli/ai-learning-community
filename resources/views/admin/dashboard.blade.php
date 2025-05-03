@extends('layouts.app')

@section('title', 'لوحة تحكم المدير')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">لوحة تحكم المدير</h1>
    
    <!-- إحصائيات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-blue-50 border-b border-blue-100">
                <h2 class="text-lg font-medium text-gray-900">المستخدمين</h2>
            </div>
            <div class="p-6 flex flex-col items-center justify-center">
                <span class="text-3xl font-bold text-blue-600">{{ $usersCount }}</span>
                <div class="text-sm text-gray-500 mt-2">إجمالي المستخدمين</div>
                <div class="text-sm text-gray-500">{{ $judgesCount }} محكم</div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-green-50 border-b border-green-100">
                <h2 class="text-lg font-medium text-gray-900">المنافسات</h2>
            </div>
            <div class="p-6 flex flex-col items-center justify-center">
                <span class="text-3xl font-bold text-green-600">{{ $totalCompetitionsCount }}</span>
                <div class="text-sm text-gray-500 mt-2">إجمالي المنافسات</div>
                <div class="text-sm text-gray-500">{{ $activeCompetitionsCount }} منافسة نشطة</div>
                <a href="{{ route('admin.competitions.index') }}" class="mt-4 text-sm text-green-600 hover:underline">إدارة المنافسات</a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-purple-50 border-b border-purple-100">
                <h2 class="text-lg font-medium text-gray-900">المشاركات</h2>
            </div>
            <div class="p-6 flex flex-col items-center justify-center">
                <span class="text-3xl font-bold text-purple-600">{{ $pendingSubmissionsCount + $evaluatedSubmissionsCount }}</span>
                <div class="text-sm text-gray-500 mt-2">إجمالي المشاركات</div>
                <div class="text-sm text-gray-500">{{ $pendingSubmissionsCount }} بانتظار التقييم</div>
                <a href="{{ route('admin.competitions.index') }}" class="mt-4 text-sm text-purple-600 hover:underline">عرض المشاركات</a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-red-50 border-b border-red-100">
                <h2 class="text-lg font-medium text-gray-900">الإحصائيات</h2>
            </div>
            <div class="p-6 flex flex-col items-center justify-center">
                <span class="text-3xl font-bold text-red-600">{{ $evaluatedSubmissionsCount }}</span>
                <div class="text-sm text-gray-500 mt-2">التقييمات المكتملة</div>
                <div class="text-sm text-gray-500">{{ round(($evaluatedSubmissionsCount / ($pendingSubmissionsCount + $evaluatedSubmissionsCount)) * 100, 1) }}% نسبة الإنجاز</div>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- أحدث المنافسات -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">أحدث المنافسات</h2>
                <a href="{{ route('admin.competitions.index') }}" class="text-sm text-blue-600 hover:underline">عرض الكل</a>
            </div>
            
            @if($latestCompetitions->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($latestCompetitions as $competition)
                        <div class="p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ $competition->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-1">
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
                                        <span class="mr-2">{{ $competition->start_date->format('Y-m-d') }} - {{ $competition->end_date->format('Y-m-d') }}</span>
                                    </p>
                                </div>
                                <a href="{{ route('admin.competitions.edit', $competition->id) }}" class="text-sm text-blue-600 hover:underline">تعديل</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-gray-500">لا توجد منافسات حالياً</p>
                </div>
            @endif
        </div>
        
        <!-- أحدث المستخدمين -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">أحدث المستخدمين</h2>
            </div>
            
            @if($newUsers->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($newUsers as $user)
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff" alt="{{ $user->name }}">
                                </div>
                                <div class="mr-3">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $user->name }}</h3>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                                </div>
                                
                                <div class="mr-auto">
                                    @if($user->is_admin)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            مدير
                                        </span>
                                    @endif
                                    
                                    @if($user->is_judge)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            محكم
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-gray-500">لا يوجد مستخدمين جدد</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- أحدث المشاركات -->
    <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">أحدث المشاركات</h2>
        </div>
        
        @if($latestSubmissions->count() > 0)
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
                                التقييم
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
                        @foreach($latestSubmissions as $submission)
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
                                    <div class="text-sm text-gray-900">{{ $submission->competition->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($submission->competition->status == 'active')
                                                bg-green-100 text-green-800
                                            @elseif($submission->competition->status == 'upcoming')
                                                bg-blue-100 text-blue-800
                                            @else
                                                bg-gray-100 text-gray-800
                                            @endif
                                        ">
                                            {{ __('competitions.status.' . $submission->competition->status) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($submission->final_score)
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
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            بانتظار التقييم
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                    {{ $submission->created_at->format('Y-m-d H:i') }}
                                    <div class="text-xs text-gray-500">{{ $submission->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('competitions.submissions.show', $submission->id) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center">
                <p class="text-gray-500">لا توجد مشاركات حالياً</p>
            </div>
        @endif
    </div>
</div>
@endsection