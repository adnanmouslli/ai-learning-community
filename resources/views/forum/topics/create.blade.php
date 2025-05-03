@extends('layouts.app')

@section('title', 'إنشاء موضوع جديد')

@section('content')
<div class="mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('forum.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    المنتدى
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-500">إنشاء موضوع جديد</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <h1 class="text-xl font-semibold text-gray-900">إنشاء موضوع جديد</h1>
    </div>
    <div class="p-6">
        <form action="{{ route('forum.topics.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700">عنوان الموضوع</label>
                <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('title') }}" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700">الفئة</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">-- اختر الفئة --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700">محتوى الموضوع</label>
                <textarea name="content" id="content" rows="10" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>{{ old('content') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">يمكنك استخدام تنسيق Markdown لتنسيق محتوى الموضوع.</p>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-between">
                <a href="{{ route('forum.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    إلغاء
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    نشر الموضوع
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // إضافة محرر نصوص متقدم (يمكن استخدام مكتبات مثل TinyMCE أو CKEditor)
    document.addEventListener('DOMContentLoaded', function() {
        // هنا يمكن إضافة كود تهيئة محرر النصوص المتقدم
    });
</script>
@endsection