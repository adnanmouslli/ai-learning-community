@extends('layouts.app')

@section('title', 'تفاصيل المشاركة - ' . $submission->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 space-x-reverse">
                @if(auth()->user()->isJudge() || auth()->user()->isAdmin())
                    <li class="inline-flex items-center">
                        <a href="{{ route('judge.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                            <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            لوحة المحكم
                        </a>
                    </li>
                    <li class="inline-flex items-center">
                        <a href="{{ route('judge.competitions.submissions', $submission->competition_id) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                            <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            مشاركات المنافسة
                        </a>
                    </li>
                @else
                    <li class="inline-flex items-center">
                        <a href="{{ route('competitions.show', $submission->competition->slug) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                            <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ $submission->competition->title }}
                        </a>
                    </li>
                @endif
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-gray-500">تفاصيل المشاركة</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">{{ $submission->title }}</h1>
        @if(auth()->user()->isJudge() || auth()->user()->isAdmin())
            <a href="{{ route('judge.competitions.submissions', $submission->competition_id) }}" class="bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
                العودة إلى المشاركات
            </a>
        @else
            <a href="{{ route('competitions.show', $submission->competition->slug) }}" class="bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
                العودة إلى المنافسة
            </a>
        @endif
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- تفاصيل المشاركة -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-900">تفاصيل المشاركة</h2>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 ml-2">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->user->name }}">
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- وصف المشاركة -->
                    <div class="mb-6">
                        <h3 class="text-base font-medium text-gray-900 mb-2">الوصف</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-line">
                            {{ $submission->description ?: 'لا يوجد وصف' }}
                        </div>
                    </div>
                    
                    <!-- ملف المشاركة -->
                    @if(isset($fileInfo))
                        <div class="mb-6">
                            <h3 class="text-base font-medium text-gray-900 mb-2">الملف المرفق</h3>
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-500 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div>
                                    <span class="block text-sm font-medium text-gray-900">{{ $fileInfo['name'] }}</span>
                                    <span class="block text-xs text-gray-500">{{ number_format($fileInfo['size'] / 1024, 2) }} كيلوبايت</span>
                                </div>
                                <a href="{{ $fileInfo['url'] }}" class="mr-auto inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" download>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    تنزيل الملف
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    <!-- رابط GitHub إذا وجد -->
                    @if($submission->github_url)
                        <div class="mb-6">
                            <h3 class="text-base font-medium text-gray-900 mb-2">رابط GitHub</h3>
                            <a href="{{ $submission->github_url }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                {{ $submission->github_url }}
                            </a>
                        </div>
                    @endif
                    
                    <!-- معلومات إضافية -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500">تاريخ التقديم</span>
                            <span class="font-medium text-gray-900" dir="ltr">{{ $submission->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                        
                        @if($submission->final_score)
                            <div>
                                <span class="block text-gray-500">تاريخ التقييم</span>
                                <span class="font-medium text-gray-900" dir="ltr">{{ \Carbon\Carbon::parse($submission->evaluated_at)->format('Y-m-d H:i:s') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- نموذج التقييم - للمحكمين فقط -->
            @if((auth()->user()->isJudge() || auth()->user()->isAdmin()) && !$submission->final_score)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">تقييم المشاركة</h2>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('competitions.submissions.evaluate', $submission->id) }}" method="POST" id="evaluationForm">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- تقييم دقة الحل -->
                                <div>
                                    <label for="accuracy_score" class="block text-sm font-medium text-gray-700 mb-1">دقة الحل (من 0 إلى 100)*</label>
                                    <div class="flex flex-col space-y-2">
                                        <input type="range" id="accuracy_score" name="accuracy_score" min="0" max="100" value="70" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="updateAccuracyValue(this.value)">
                                        <div class="flex justify-between text-xs text-gray-500">
                                            <span>0</span>
                                            <span>25</span>
                                            <span>50</span>
                                            <span>75</span>
                                            <span>100</span>
                                        </div>
                                        <div class="text-center font-medium text-gray-900" id="accuracy_display">70</div>
                                    </div>
                                    @error('accuracy_score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- تقييم أداء الحل -->
                                <div>
                                    <label for="performance_score" class="block text-sm font-medium text-gray-700 mb-1">أداء الحل (من 0 إلى 100)*</label>
                                    <div class="flex flex-col space-y-2">
                                        <input type="range" id="performance_score" name="performance_score" min="0" max="100" value="70" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer" oninput="updatePerformanceValue(this.value)">
                                        <div class="flex justify-between text-xs text-gray-500">
                                            <span>0</span>
                                            <span>25</span>
                                            <span>50</span>
                                            <span>75</span>
                                            <span>100</span>
                                        </div>
                                        <div class="text-center font-medium text-gray-900" id="performance_display">70</div>
                                    </div>
                                    {{-- @error('performance_score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror --}}
                                </div>
                                
                                <!-- النتيجة النهائية المحسوبة -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">النتيجة النهائية (حساب تلقائي)</label>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="text-center font-bold text-xl text-gray-900" id="final_score">70</div>
                                        <div class="text-center text-xs text-gray-500">60% من دقة الحل + 40% من أداء الحل</div>
                                    </div>
                                </div>
                                
                                <!-- ملاحظات وتعليقات -->
                                <div>
                                    <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">ملاحظات وتعليقات (اختياري)</label>
                                    <textarea id="feedback" name="feedback" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="أضف ملاحظاتك وتعليقاتك على الحل المقدم..."></textarea>
                                    {{-- @error('feedback')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror --}}
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    حفظ التقييم
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            
            <!-- عرض نتيجة التقييم - في حال تم التقييم -->
            @if($submission->final_score)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">نتيجة التقييم</h2>
                    </div>
                    
                    <div class="p-6">
                        <!-- النتيجة النهائية -->
                        <div class="flex justify-center mb-6">
                            <div class="w-32 h-32 rounded-full flex items-center justify-center border-8 
                                @if($submission->final_score >= 80)
                                    border-green-500 text-green-600
                                @elseif($submission->final_score >= 60)
                                    border-blue-500 text-blue-600
                                @elseif($submission->final_score >= 40)
                                    border-yellow-500 text-yellow-600
                                @else
                                    border-red-500 text-red-600
                                @endif">
                                <span class="text-3xl font-bold">{{ round($submission->final_score, 1) }}</span>
                            </div>
                        </div>
                        
                        <!-- تفاصيل التقييم -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <div class="block text-sm font-medium text-gray-700 mb-1">دقة الحل</div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $submission->accuracy_score }}%"></div>
                                </div>
                                <div class="text-center mt-1 text-sm">{{ round($submission->accuracy_score, 1) }}</div>
                            </div>
                            
                            <div>
                                <div class="block text-sm font-medium text-gray-700 mb-1">أداء الحل</div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $submission->performance_score }}%"></div>
                                </div>
                                <div class="text-center mt-1 text-sm">{{ round($submission->performance_score, 1) }}</div>
                            </div>
                        </div>
                        
                        <!-- ملاحظات المقيم -->
                        @if($submission->feedback)
                            <div>
                                <h3 class="text-base font-medium text-gray-900 mb-2">ملاحظات المقيم</h3>
                                <div class="bg-gray-50 p-4 rounded-lg text-sm text-gray-700 whitespace-pre-line">
                                    {{ $submission->feedback }}
                                </div>
                            </div>
                        @endif
                        
                        <!-- معلومات المقيم -->
                        @if($submission->evaluator)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->evaluator->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->evaluator->name }}">
                                    </div>
                                    <div class="mr-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $submission->evaluator->name }}</div>
                                        <div class="text-xs text-gray-500">تم التقييم {{ \Carbon\Carbon::parse($submission->evaluated_at)->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        
        <!-- الشريط الجانبي -->
        <div class="lg:col-span-1">
            <!-- معلومات المنافسة -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-900">المنافسة</h2>
                </div>
                
                <div class="p-6">
                    <div class="text-sm font-medium text-gray-900 mb-4">{{ $submission->competition->title }}</div>
                    
                    <div class="grid grid-cols-1 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500">حالة المنافسة</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($submission->competition->status == 'active')
                                    bg-green-100 text-green-800
                                @elseif($submission->competition->status == 'upcoming')
                                    bg-blue-100 text-blue-800
                                @elseif($submission->competition->status == 'completed')
                                    bg-gray-100 text-gray-800
                                @else
                                    bg-yellow-100 text-yellow-800
                                @endif
                            ">
                                {{ __('competitions.status.' . $submission->competition->status) }}
                            </span>
                        </div>
                        
                        <div>
                            <span class="block text-gray-500">تاريخ البدء</span>
                            <span class="font-medium text-gray-900" dir="ltr">{{ $submission->competition->start_date->format('Y-m-d') }}</span>
                        </div>
                        
                        <div>
                            <span class="block text-gray-500">تاريخ الانتهاء</span>
                            <span class="font-medium text-gray-900" dir="ltr">{{ $submission->competition->end_date->format('Y-m-d') }}</span>
                        </div>
                        
                        <div class="pt-4">
                            <a href="{{ route('competitions.show', $submission->competition->slug) }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                عرض صفحة المنافسة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- معلومات المستخدم -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-medium text-gray-900">معلومات المستخدم</h2>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->user->name }}">
                        </div>
                        <div class="mr-3">
                            <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ '@' . $submission->user->username }}</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500">تاريخ الانضمام</span>
                            <span class="font-medium text-gray-900" dir="ltr">{{ $submission->user->created_at->format('Y-m-d') }}</span>
                        </div>
                        
                        <div>
                            <span class="block text-gray-500">عدد المشاركات</span>
                            <span class="font-medium text-gray-900">{{ $submission->user->submissions->count() }}</span>
                        </div>
                        
                        <div>
                            <span class="block text-gray-500">النقاط</span>
                            <span class="font-medium text-gray-900">{{ $submission->user->points }}</span>
                        </div>
                        
                        <div class="pt-4">
                            <a href="{{ route('profile.show', $submission->user->username) }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                عرض الملف الشخصي
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript لحساب النتيجة النهائية -->
<script>
    function updateAccuracyValue(val) {
        document.getElementById('accuracy_display').textContent = val;
        calculateFinalScore();
    }
    
    function updatePerformanceValue(val) {
        document.getElementById('performance_display').textContent = val;
        calculateFinalScore();
    }
    
    function calculateFinalScore() {
        const accuracyScore = parseInt(document.getElementById('accuracy_score').value);
        const performanceScore = parseInt(document.getElementById('performance_score').value);
        
        // حساب النتيجة النهائية (60% دقة + 40% أداء)
        const finalScore = (accuracyScore * 0.6) + (performanceScore * 0.4);
        
        document.getElementById('final_score').textContent = Math.round(finalScore * 10) / 10;
    }
    
    // تهيئة النتيجة النهائية عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        calculateFinalScore();
    });
</script>
@endsection