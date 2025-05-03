@extends('layouts.app')

@section('title', 'إنشاء حساب جديد')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- رأس البطاقة -->
            <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-blue-600 text-white text-center">
                <h2 class="text-xl font-bold">{{ __('إنشاء حساب جديد') }}</h2>
            </div>

            <!-- محتوى البطاقة -->
            <div class="p-6">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    
                    <!-- الاسم -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('الاسم الكامل') }}</label>
                        <input id="name" name="name" type="text" 
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" 
                            value="{{ old('name') }}" required autocomplete="name" autofocus>
                        
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- اسم المستخدم -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">{{ __('اسم المستخدم') }}</label>
                        <input id="username" name="username" type="text" 
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror" 
                            value="{{ old('username') }}" required autocomplete="username">
                        <p class="mt-1 text-xs text-gray-500">اسم المستخدم الخاص بك، سيظهر في الملف الشخصي وفي المنتدى</p>
                        
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- البريد الإلكتروني -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('البريد الإلكتروني') }}</label>
                        <input id="email" name="email" type="email" 
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                            value="{{ old('email') }}" required autocomplete="email">
                        
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- كلمة المرور -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('كلمة المرور') }}</label>
                        <input id="password" name="password" type="password" 
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" 
                            required autocomplete="new-password">
                        <p class="mt-1 text-xs text-gray-500">كلمة المرور يجب أن تكون على الأقل 8 أحرف</p>
                        
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- تأكيد كلمة المرور -->
                    <div>
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">{{ __('تأكيد كلمة المرور') }}</label>
                        <input id="password-confirm" name="password_confirmation" type="password" 
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                            required autocomplete="new-password">
                    </div>
                    
                    <!-- الموافقة على الشروط والأحكام -->
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                        </div>
                        <div class="mr-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">{{ __('أوافق على') }}</label>
                            <a href="#" class="text-blue-600 hover:text-blue-800">{{ __('الشروط والأحكام') }}</a>
                        </div>
                    </div>
                    
                    <!-- زر التسجيل -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            {{ __('إنشاء الحساب') }}
                        </button>
                    </div>
                    
                    <!-- رابط تسجيل الدخول -->
                    <div class="text-center text-sm">
                        <span class="text-gray-600">لديك حساب بالفعل؟</span>
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-800 mr-1">
                            {{ __('تسجيل الدخول') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection