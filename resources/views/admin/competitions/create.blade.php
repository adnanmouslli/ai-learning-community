@extends('layouts.app')

@section('title', 'إنشاء منافسة جديدة')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">إنشاء منافسة جديدة</h1>
        <a href="{{ route('admin.competitions.index') }}" class="bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
            العودة للمنافسات
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <form action="{{ route('admin.competitions.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- معلومات المنافسة الأساسية -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">المعلومات الأساسية</h3>
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">عنوان المنافسة*</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">وصف المنافسة*</label>
                        <textarea name="description" id="description" rows="5" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">نوع المنافسة*</label>
                        <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                            <option value="default" {{ old('type') == 'default' ? 'selected' : '' }}>عام</option>
                            <option value="image_classification" {{ old('type') == 'image_classification' ? 'selected' : '' }}>تصنيف الصور</option>
                            <option value="nlp" {{ old('type') == 'nlp' ? 'selected' : '' }}>معالجة اللغة الطبيعية</option>
                            <option value="tabular" {{ old('type') == 'tabular' ? 'selected' : '' }}>بيانات جدولية</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="rules" class="block text-sm font-medium text-gray-700 mb-1">قواعد المنافسة</label>
                        <textarea name="rules" id="rules" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('rules') }}</textarea>
                        @error('rules')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- معايير التقييم والبيانات -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">معايير التقييم والبيانات</h3>
                    
                    <div class="mb-4">
                        <label for="evaluation_criteria" class="block text-sm font-medium text-gray-700 mb-1">معايير التقييم*</label>
                        <textarea name="evaluation_criteria" id="evaluation_criteria" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>{{ old('evaluation_criteria') }}</textarea>
                        @error('evaluation_criteria')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="dataset_description" class="block text-sm font-medium text-gray-700 mb-1">وصف مجموعة البيانات</label>
                        <textarea name="dataset_description" id="dataset_description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('dataset_description') }}</textarea>
                        @error('dataset_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="dataset_file" class="block text-sm font-medium text-gray-700 mb-1">ملف البيانات</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="dataset_file_upload" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                        <span>رفع ملف</span>
                                        <input id="dataset_file_upload" name="dataset_file" type="file" class="sr-only">
                                    </label>
                                    <p class="pr-1">أو سحب وإفلات</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    ZIP, CSV, JSON (حجم أقصى 50MB)
                                </p>
                            </div>
                        </div>
                        @error('dataset_file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">التوقيت والإعدادات</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ البدء*</label>
                        <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">تاريخ الانتهاء*</label>
                        <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="max_daily_submissions" class="block text-sm font-medium text-gray-700 mb-1">الحد الأقصى للتقديمات اليومية*</label>
                        <input type="number" name="max_daily_submissions" id="max_daily_submissions" value="{{ old('max_daily_submissions', 5) }}" min="1" max="50" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                        @error('max_daily_submissions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4 flex items-start">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        </div>
                        <div class="mr-3 text-sm">
                            <label for="is_featured" class="font-medium text-gray-700">منافسة مميزة</label>
                            <p class="text-gray-500">ستظهر المنافسة في قسم المنافسات المميزة على الصفحة الرئيسية.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    إنشاء المنافسة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection