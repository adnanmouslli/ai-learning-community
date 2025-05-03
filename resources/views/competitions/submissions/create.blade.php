@extends('layouts.app')

@section('title', 'تقديم مشاركة جديدة - ' . $competition->title)

@section('content')
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('competitions.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    المنافسات
                </a>
            </li>
            <li class="inline-flex items-center">
                <a href="{{ route('competitions.show', $competition->slug) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    {{ $competition->title }}
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-500">تقديم مشاركة جديدة</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h1 class="text-xl font-semibold text-gray-900">تقديم مشاركة جديدة - {{ $competition->title }}</h1>
    </div>
    
    <div class="p-6">
        @if($dailySubmissionsCount >= $competition->max_daily_submissions)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="mr-3">
                        <p class="text-sm text-yellow-700">
                            لقد وصلت للحد الأقصى للتقديمات اليومية ({{ $competition->max_daily_submissions }}). يمكنك تقديم المزيد من المشاركات غداً.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('competitions.submissions.store', $competition->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">عنوان المشاركة*</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" required value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">وصف المشاركة</label>
                    <textarea name="description" id="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">يمكنك وصف النهج الذي اتبعته والتقنيات المستخدمة.</p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="file_upload" class="block text-sm font-medium text-gray-700 mb-1">ملف المشاركة*</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md relative">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex flex-col text-sm text-gray-600">
                                    <label for="file_upload" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                        <span>رفع ملف</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2" id="file-name-display">
                                        ZIP, CSV, JSON, IPYNB حجم أقصى 10MB
                                    </p>
                                    <!-- تصحيح اسم الحقل ليتطابق مع الكنترولر -->
                                    <input id="file_upload" name="file" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".zip,.csv,.json,.ipynb">
                                </div>
                            </div>
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="github_url" class="block text-sm font-medium text-gray-700 mb-1">رابط GitHub (اختياري)</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="url" name="github_url" id="github_url" class="block w-full pr-10 border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500" placeholder="https://github.com/username/repo" value="{{ old('github_url') }}">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">إذا كان لديك مستودع GitHub للمشروع، يمكنك مشاركة الرابط هنا.</p>
                        @error('github_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">تفاصيل المنافسة</h3>
                    
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div class="col-span-1">
                                <dt class="text-sm font-medium text-gray-500">حالة المنافسة</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ __('competitions.status.' . $competition->status) }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div class="col-span-1">
                                <dt class="text-sm font-medium text-gray-500">تاريخ الانتهاء</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($competition->end_date)->format('d/m/Y H:i') }}</dd>
                            </div>
                            
                            <div class="col-span-1">
                                <dt class="text-sm font-medium text-gray-500">التقديمات اليومية المتبقية</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $competition->max_daily_submissions - $dailySubmissionsCount }} من {{ $competition->max_daily_submissions }}</dd>
                            </div>
                            
                            <div class="col-span-1">
                                <dt class="text-sm font-medium text-gray-500">مجموع التقديمات</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $userSubmissions->count() }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" required>
                        </div>
                        <div class="mr-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">الموافقة على الشروط</label>
                            <p class="text-gray-500">أقر بأن هذا العمل من إنتاجي الخاص وأنه يتوافق مع <a href="#" class="text-green-600 hover:text-green-500">شروط وأحكام المنافسة</a>.</p>
                        </div>
                    </div>
                    @error('terms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('competitions.show', $competition->slug) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    العودة
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" {{ $dailySubmissionsCount >= $competition->max_daily_submissions ? 'disabled' : '' }}>
                    تقديم المشاركة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// تحسين الكود الحالي للتحقق من الملف
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file_upload');
    const fileNameDisplay = document.getElementById('file-name-display');
    const form = document.querySelector('form');
    
    if (fileInput && fileNameDisplay) {
        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const fileName = file.name;
                const fileSize = (file.size / (1024 * 1024)).toFixed(2); // تحويل إلى ميجابايت
                fileNameDisplay.textContent = `${fileName} (${fileSize} MB)`;
                
                // التحقق من حجم الملف
                if (file.size > 10 * 1024 * 1024) { // 10 ميجابايت
                    alert('حجم الملف كبير جداً. يجب أن يكون أقل من 10 ميجابايت.');
                    fileInput.value = ''; // إفراغ الملف
                    fileNameDisplay.textContent = 'ZIP, CSV, JSON, IPYNB حجم أقصى 10MB';
                }
            } else {
                fileNameDisplay.textContent = 'ZIP, CSV, JSON, IPYNB حجم أقصى 10MB';
            }
        });
    }
    
    // تحسين منطق التحقق قبل الإرسال
    if (form) {
        form.addEventListener('submit', function(e) {
            const termsCheckbox = document.getElementById('terms');
            
            // تجنب إيقاف النموذج إذا كان الملف موجوداً
            if (!fileInput || fileInput.files.length === 0) {
                e.preventDefault();
                alert('يرجى اختيار ملف للمشاركة');
                return false;
            }
            
            // التحقق من الموافقة على الشروط
            if (!termsCheckbox || !termsCheckbox.checked) {
                e.preventDefault();
                alert('يرجى الموافقة على شروط المشاركة');
                return false;
            }
            
            // يمكن إضافة المزيد من التحققات هنا...
            
            // السماح بالإرسال إذا اجتاز كل الفحوصات
            return true;
        });
    }
});
</script>
@endsection