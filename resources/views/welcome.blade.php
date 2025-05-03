@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
<div class="py-12">
    <!-- بانر الترحيب -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-800 rounded-xl shadow-xl overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-white">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl">
                        مجتمع الذكاء الصنعي التعليمي
                    </h1>
                    <p class="mt-6 text-xl">
                        منصة تفاعلية لتعلم وممارسة مهارات الذكاء الصنعي من خلال المنتديات التعليمية والمنافسات العملية.
                    </p>
                    <div class="mt-10 flex space-x-4 space-x-reverse">
                        <a href="{{ route('register') }}" class="bg-white text-blue-700 hover:bg-blue-50 px-6 py-3 rounded-md text-base font-medium shadow-sm">
                            انضم إلينا الآن
                        </a>
                        <a href="{{ route('forum.index') }}" class="bg-blue-500 bg-opacity-20 hover:bg-opacity-30 text-white px-6 py-3 rounded-md text-base font-medium">
                            استكشف المنتدى
                        </a>
                    </div>
                </div>
                <div class="mt-12 lg:mt-0 flex justify-end">
                    <div class="w-full max-w-md">
                        <svg class="w-full" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- أيقونة توضيحية للذكاء الصنعي -->
                            <rect width="400" height="300" rx="10" fill="white" fill-opacity="0.1"/>
                            <circle cx="200" cy="150" r="80" stroke="white" stroke-width="8" stroke-opacity="0.8"/>
                            <path d="M150 110 L250 110 L250 190 L150 190 Z" stroke="white" stroke-width="6" stroke-opacity="0.7"/>
                            <path d="M180 80 L180 220" stroke="white" stroke-width="6" stroke-opacity="0.6"/>
                            <path d="M220 80 L220 220" stroke="white" stroke-width="6" stroke-opacity="0.6"/>
                            <path d="M130 140 L270 140" stroke="white" stroke-width="6" stroke-opacity="0.6"/>
                            <path d="M130 160 L270 160" stroke="white" stroke-width="6" stroke-opacity="0.6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الميزات الرئيسية -->
    <div class="mt-16">
        <h2 class="text-3xl font-bold text-center text-gray-900">ميزات المنصة</h2>
        <div class="mt-12 grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            <!-- المنتدى التعليمي -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">المنتدى التعليمي</h3>
                <p class="text-gray-600">
                    منتدى للنقاش حول مواضيع الذكاء الصنعي مثل الشبكات العصبية، التعلم العميق، وتحسين النماذج. يمكن للطلاب طرح الأسئلة والإجابة على أسئلة الآخرين.
                </p>
                <a href="{{ route('forum.index') }}" class="mt-4 inline-flex items-center text-blue-600 font-medium hover:text-blue-800">
                    استكشف المنتدى
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <!-- المنافسات -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">المنافسات والتحديات</h3>
                <p class="text-gray-600">
                    مسابقات أسبوعية وشهرية يتم فيها طرح مشكلة ذكاء صنعي لحلها. يمكن أن تكون التحديات تصنيف صور، تحليل نصوص، أو التنبؤ بالبيانات.
                </p>
                <a href="{{ route('competitions.index') }}" class="mt-4 inline-flex items-center text-green-600 font-medium hover:text-green-800">
                    استعرض المنافسات
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            <!-- لوحة المتصدرين -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">لوحة المتصدرين</h3>
                <p class="text-gray-600">
                    تتبع تقدمك وقارن أدائك مع الآخرين من خلال لوحة المتصدرين. اكتسب النقاط من خلال المشاركة في المنتدى وحل التحديات.
                </p>
                <a href="{{ route('home') }}" class="mt-4 inline-flex items-center text-purple-600 font-medium hover:text-purple-800">
                    عرض لوحة المتصدرين
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- الإحصائيات -->
    <div class="mt-16 bg-gray-50 rounded-xl shadow-inner p-8">
        <h2 class="text-3xl font-bold text-center text-gray-900">نمو مجتمعنا</h2>
        <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        عدد الأعضاء
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-blue-600">
                        {{ App\Models\User::count() }}+
                    </dd>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        مواضيع المنتدى
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-green-600">
                        {{ App\Models\ForumTopic::count() }}+
                    </dd>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        المنافسات
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-purple-600">
                        {{ App\Models\Competition::count() }}+
                    </dd>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center">
                    <dt class="text-sm font-medium text-gray-500 truncate">
                        المشاركات
                    </dt>
                    <dd class="mt-1 text-3xl font-semibold text-indigo-600">
                        {{ App\Models\ForumPost::count() + App\Models\CompetitionSubmission::count() }}+
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- آخر المواضيع والمنافسات -->
    <div class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- آخر المواضيع -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">آخر المواضيع</h3>
                <a href="{{ route('forum.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    عرض الكل
                </a>
            </div>
            <div class="space-y-4">
                @forelse(App\Models\ForumTopic::latest()->take(5)->get() as $topic)
                    <a href="{{ route('forum.topics.show', $topic->slug) }}" class="block bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                        <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $topic->title }}</h4>
                        <div class="flex items-center text-sm text-gray-500">
                            <span>{{ $topic->user->name }}</span>
                            <span class="mx-2">&middot;</span>
                            <span>{{ $topic->created_at->diffForHumans() }}</span>
                            <span class="mx-2">&middot;</span>
                            <span>{{ $topic->posts->count() }} ردود</span>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-lg p-6 text-center shadow">
                        <p class="text-gray-500">لا توجد مواضيع بعد. كن أول من يبدأ النقاش!</p>
                        <a href="{{ route('forum.topics.create') }}" class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            إنشاء موضوع جديد
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- آخر المنافسات -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900">المنافسات الجارية</h3>
                <a href="{{ route('competitions.index') }}" class="text-green-600 hover:text-green-800 font-medium">
                    عرض الكل
                </a>
            </div>
            <div class="space-y-4">
                @forelse(App\Models\Competition::where('status', 'active')->latest()->take(3)->get() as $competition)
                    <a href="{{ route('competitions.show', $competition->slug) }}" class="block bg-white rounded-lg p-4 shadow hover:shadow-md transition-shadow">
                        <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $competition->title }}</h4>
                        <div class="mt-2 flex justify-between items-center">
                            <div class="flex items-center text-sm text-gray-500">
                                <span>تنتهي في {{ \Carbon\Carbon::parse($competition->end_date)->diffForHumans() }}</span>                                <span class="mx-2">&middot;</span>
                                <span>{{ $competition->submissions->count() }} مشاركة</span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                جارية
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-lg p-6 text-center shadow">
                        <p class="text-gray-500">لا توجد منافسات جارية حالياً. ترقب المنافسات القادمة!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection