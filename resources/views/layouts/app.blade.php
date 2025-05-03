<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'مجتمع الذكاء الصنعي التعليمي') }} - @yield('title', 'الرئيسية')</title>

    <!-- الخطوط -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- النمط -->
    @vite('resources/css/app.css')
    
    <!-- النمط المخصص -->
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <div id="app" class="min-h-screen flex flex-col">
        <!-- شريط التنقل -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- شعار الموقع -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="text-blue-600 font-bold text-xl">
                                مجتمع الذكاء الصنعي التعليمي
                            </a>
                        </div>

                        <!-- روابط التنقل -->
                        <div class="hidden sm:mr-6 sm:flex sm:space-x-8 sm:space-x-reverse">
                            <a href="{{ route('forum.index') }}" class="border-transparent text-gray-500 hover:border-blue-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                المنتدى التعليمي
                            </a>
                            <a href="{{ route('competitions.index') }}" class="border-transparent text-gray-500 hover:border-blue-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                المنافسات
                            </a>
                            <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:border-blue-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                لوحة التحكم
                            </a>

                            {{-- @if(Auth::user()?->isJudge() || Auth::user()?->isAdmin()) --}}
            
                                <a class="border-transparent text-gray-500 hover:border-blue-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"  href="{{ route('judge.dashboard') }}">
                                    <span>لوحة التحكيم</span>
                                
                                </a>
                
                            {{-- @endif --}}
            
                        {{-- @if(Auth::user()?->isAdmin()) --}}
                            <!-- روابط للمدراء فقط -->
                       
                                <a href="{{ route('admin.dashboard') }}" class="border-transparent text-gray-500 hover:border-blue-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    <span>لوحة الإدارة</span>
                                </a>
                        {{-- @endif --}}
                        </div>
                    </div>

                    <!-- مساحة المستخدم -->
                    <div class="hidden sm:flex sm:items-center sm:mr-6">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">تسجيل الدخول</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">إنشاء حساب</a>
                        @else
                            <div class="mr-3 relative">
                                <div>
                                    <button type="button" class="flex items-center max-w-xs text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">افتح قائمة المستخدم</span>
                                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" alt="{{ Auth::user()->name }}">
                                        <span class="mr-2">{{ Auth::user()->name }}</span>
                                    </button>
                                </div>
                                <div class="hidden origin-top-right absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu">
                                    <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">الملف الشخصي</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">إعدادات الحساب</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                            تسجيل الخروج
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <!-- زر القائمة للجوال -->
                    <div class="flex items-center sm:hidden">
                        <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                            <span class="sr-only">افتح القائمة الرئيسية</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- قائمة الجوال -->
            <div class="hidden sm:hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('forum.index') }}" class="bg-blue-50 border-blue-500 text-blue-700 block pr-3 pl-4 py-2 border-r-4 text-base font-medium">المنتدى التعليمي</a>
                    <a href="{{ route('competitions.index') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pr-3 pl-4 py-2 border-r-4 text-base font-medium">المنافسات</a>
                    <a href="{{ route('home') }}" class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700 block pr-3 pl-4 py-2 border-r-4 text-base font-medium">لوحة التحكم</a>
                </div>
                
                @auth
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" alt="{{ Auth::user()->name }}">
                        </div>
                        <div class="mr-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">الملف الشخصي</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">إعدادات الحساب</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-right px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
                @endauth
            </div>
        </nav>

        <!-- المحتوى الرئيسي -->
        <main class="py-6 flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>

        <!-- التذييل -->
        <footer class="bg-white mt-auto border-t border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="mt-8 md:mt-0 md:order-1">
                        <p class="text-center text-base text-gray-400">
                            &copy; {{ date('Y') }} مجتمع الذكاء الصنعي التعليمي. جميع الحقوق محفوظة.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- سكريبتات الجافاسكريبت -->
    <script>
        // قائمة المستخدم
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        
        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });
        }
        
        // قائمة الجوال
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>