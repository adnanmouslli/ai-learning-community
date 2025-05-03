@extends('layouts.app')

@section('title', 'المشاركات بانتظار التقييم')

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
                        <span class="text-sm font-medium text-gray-500">المشاركات بانتظار التقييم</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">المشاركات بانتظار التقييم</h1>
        <a href="{{ route('judge.dashboard') }}" class="bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
            العودة للوحة المحكم
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Filter Form -->
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form action="{{ route('judge.submissions.pending') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="w-full md:w-64">
                    <label for="competition" class="block text-sm font-medium text-gray-700 mb-1">المنافسة</label>
                    <select name="competition" id="competition" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">جميع المنافسات</option>
                        @foreach(\App\Models\Competition::where('status', 'active')->get() as $comp)
                            <option value="{{ $comp->id }}" {{ request('competition') == $comp->id ? 'selected' : '' }}>
                                {{ $comp->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full md:w-48">
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                    <select name="order" id="order" class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="latest" {{ request('order', 'latest') == 'latest' ? 'selected' : '' }}>الأحدث أولاً</option>
                        <option value="oldest" {{ request('order') == 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 py-2 px-4 rounded-lg text-white text-sm hover:bg-blue-700">
                        تصفية
                    </button>
                    
                    <a href="{{ route('judge.submissions.pending') }}" class="mr-2 bg-gray-200 py-2 px-4 rounded-lg text-gray-700 text-sm hover:bg-gray-300">
                        إعادة ضبط
                    </a>
                </div>
            </form>
        </div>
        
        @if($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المشاركة
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المستخدم
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                المنافسة
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                تاريخ التقديم
                            </th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $submission)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->title }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($submission->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($submission->user->name) }}&background=0D8ABC&color=fff" alt="{{ $submission->user->name }}">
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $submission->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ '@' . $submission->user->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $submission->competition->title }}</div>
                                    <div class="text-xs text-gray-500">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('competitions.status.' . $submission->competition->status) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" dir="ltr">
                                    {{ $submission->created_at->format('Y-m-d H:i') }}
                                    <div class="text-xs text-gray-500">{{ $submission->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('competitions.submissions.show', $submission->id) }}" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        تقييم
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $submissions->withQueryString()->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="mt-2 text-gray-500">لا توجد مشاركات بانتظار التقييم.</p>
            </div>
        @endif
    </div>
</div>
@endsection