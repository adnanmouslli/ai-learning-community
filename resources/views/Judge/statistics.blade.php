@extends('layouts.app')

@section('title', 'إحصائيات المحكم')

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
                        <span class="text-sm font-medium text-gray-500">إحصائيات التقييم</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">إحصائيات التقييم</h1>
        <a href="{{ route('judge.dashboard') }}" class="bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
            العودة للوحة المحكم
        </a>
    </div>
    
    <!-- نظرة عامة -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-blue-50 border-b border-blue-100">
                <h2 class="text-lg font-medium text-gray-900">ملخص التقييمات</h2>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="block text-sm text-gray-500">إجمالي التقييمات</span>
                        <span class="text-2xl font-bold text-gray-900">{{ $totalEvaluations }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">متوسط الدرجات</span>
                        <span class="text-2xl font-bold 
                            @if($averageScore >= 80)
                                text-green-600
                            @elseif($averageScore >= 60)
                                text-blue-600
                            @elseif($averageScore >= 40)
                                text-yellow-600
                            @else
                                text-red-600
                            @endif
                        ">
                            {{ $averageScore ? round($averageScore, 2) : 'لم يتم التقييم بعد' }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">التقييمات في الأسبوع الماضي</h3>
                    <div class="h-48 relative">
                        <!-- يمكننا استبدال هذا بمكتبة رسم بياني حقيقية مثل Chart.js أو ApexCharts -->
                        <div class="flex items-end justify-between h-40 mt-2">
                            @foreach($lastWeekEvaluations as $day)
                                <div class="w-full mx-1 flex flex-col items-center">
                                    <div class="h-{{ min($day['count'] * 10, 40) }} bg-blue-500 w-full rounded-t"></div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $day['date'] }}</div>
                                    <div class="text-xs font-medium">{{ $day['count'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="p-4 bg-purple-50 border-b border-purple-100">
                <h2 class="text-lg font-medium text-gray-900">التقييمات حسب المنافسة</h2>
            </div>
            <div class="p-6">
                @if(count($evaluationsByCompetition) > 0)
                    <div class="space-y-4">
                        @foreach($evaluationsByCompetition as $item)
                            <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $item->competition->title }}</span>
                                    <span class="text-sm font-medium text-blue-600">{{ $item->count }} تقييم</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ min(($item->count / $totalEvaluations) * 100, 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-gray-500">لم تقم بأي تقييمات بعد.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- توزيع الدرجات -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-4 bg-green-50 border-b border-green-100">
            <h2 class="text-lg font-medium text-gray-900">توزيع الدرجات</h2>
        </div>
        <div class="p-6">
            @php
                $scoreDistribution = [
                    'excellent' => \App\Models\CompetitionSubmission::where('evaluated_by', Auth::id())
                        ->whereNotNull('final_score')
                        ->where('final_score', '>=', 80)
                        ->count(),
                    'good' => \App\Models\CompetitionSubmission::where('evaluated_by', Auth::id())
                        ->whereNotNull('final_score')
                        ->where('final_score', '>=', 60)
                        ->where('final_score', '<', 80)
                        ->count(),
                    'average' => \App\Models\CompetitionSubmission::where('evaluated_by', Auth::id())
                        ->whereNotNull('final_score')
                        ->where('final_score', '>=', 40)
                        ->where('final_score', '<', 60)
                        ->count(),
                    'below_average' => \App\Models\CompetitionSubmission::where('evaluated_by', Auth::id())
                        ->whereNotNull('final_score')
                        ->where('final_score', '<', 40)
                        ->count()
                ];
            @endphp
            
            @if($totalEvaluations > 0)
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <div class="text-xl font-bold text-green-600">{{ $scoreDistribution['excellent'] }}</div>
                        <div class="text-sm text-gray-500">ممتاز (80-100)</div>
                        <div class="text-xs text-gray-500 mt-1">{{ round(($scoreDistribution['excellent'] / $totalEvaluations) * 100) }}%</div>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <div class="text-xl font-bold text-blue-600">{{ $scoreDistribution['good'] }}</div>
                        <div class="text-sm text-gray-500">جيد (60-79)</div>
                        <div class="text-xs text-gray-500 mt-1">{{ round(($scoreDistribution['good'] / $totalEvaluations) * 100) }}%</div>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg p-4 text-center">
                        <div class="text-xl font-bold text-yellow-600">{{ $scoreDistribution['average'] }}</div>
                        <div class="text-sm text-gray-500">متوسط (40-59)</div>
                        <div class="text-xs text-gray-500 mt-1">{{ round(($scoreDistribution['average'] / $totalEvaluations) * 100) }}%</div>
                    </div>
                    
                    <div class="bg-red-50 rounded-lg p-4 text-center">
                        <div class="text-xl font-bold text-red-600">{{ $scoreDistribution['below_average'] }}</div>
                        <div class="text-sm text-gray-500">ضعيف (0-39)</div>
                        <div class="text-xs text-gray-500 mt-1">{{ round(($scoreDistribution['below_average'] / $totalEvaluations) * 100) }}%</div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <div class="w-full h-4 bg-gray-200 rounded-full overflow-hidden">
                        @if($totalEvaluations > 0)
                            <div class="flex h-full">
                                <div class="bg-green-500 h-full" style="width: {{ ($scoreDistribution['excellent'] / $totalEvaluations) * 100 }}%"></div>
                                <div class="bg-blue-500 h-full" style="width: {{ ($scoreDistribution['good'] / $totalEvaluations) * 100 }}%"></div>
                                <div class="bg-yellow-500 h-full" style="width: {{ ($scoreDistribution['average'] / $totalEvaluations) * 100 }}%"></div>
                                <div class="bg-red-500 h-full" style="width: {{ ($scoreDistribution['below_average'] / $totalEvaluations) * 100 }}%"></div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
            <div class="flex flex-col items-center justify-center py-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <p class="text-gray-500">لم تقم بأي تقييمات بعد.</p>
            </div>
        @endif
    </div>
</div>
</div>
@endsection