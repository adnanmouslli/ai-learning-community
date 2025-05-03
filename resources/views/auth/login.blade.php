@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- رأس البطاقة -->
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-800 text-white text-center">
                <h2 class="text-xl font-bold">{{ __('تسجيل الدخول') }}</h2>
            </div>

            <!-- محتوى البطاقة -->
            <div class="p-6">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- البريد الإلكتروني -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('البريد الإلكتروني') }}</label>
                        <input id="email" name="email" type="email" 
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                        
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- كلمة المرور -->
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('كلمة المرور') }}</label>
                            
                        </div>
                        <input id="password" name="password" type="password" 
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" 
                            required autocomplete="current-password">
                        
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- تذكرني -->
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="mr-2 block text-sm text-gray-700">
                            {{ __('تذكرني') }}
                        </label>
                    </div>
                    
                    <!-- زر تسجيل الدخول -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('تسجيل الدخول') }}
                        </button>
                    </div>
                    
                    <!-- رابط التسجيل -->
                    <div class="text-center text-sm">
                        <span class="text-gray-600">ليس لديك حساب؟</span>
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-800 mr-1">
                            {{ __('إنشاء حساب جديد') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection