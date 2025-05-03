@extends('layouts.app')

@section('title', 'إعادة تعيين كلمة المرور')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- رأس البطاقة -->
            <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-red-500 text-white text-center">
                <h2 class="text-xl font-bold">{{ __('إعادة تعيين كلمة المرور') }}</h2>
            </div>

            <!-- محتوى البطاقة -->
            <div class="p-6">
                @if (session('status'))
                    <div class="mb-4 text-sm bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    <p class="text-gray-600 text-sm">أدخل بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيين كلمة المرور.</p>
                    
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
                    
                    <!-- زر إرسال رابط إعادة تعيين كلمة المرور -->
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            {{ __('إرسال رابط إعادة تعيين كلمة المرور') }}
                        </button>
                    </div>
                    
                    <!-- العودة إلى صفحة تسجيل الدخول -->
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            <span class="inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </span>
                            {{ __('العودة إلى صفحة تسجيل الدخول') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection