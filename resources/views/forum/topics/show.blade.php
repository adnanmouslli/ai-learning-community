@extends('layouts.app')

@section('title', $topic->title)

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
            <li class="inline-flex items-center">
                <a href="{{ route('forum.categories.show', $topic->category->slug) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                    <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    {{ $topic->category->name }}
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="ml-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-gray-500 truncate max-w-xs">{{ $topic->title }}</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<!-- موضوع المنتدى -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
    <!-- رأس الموضوع -->
    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline">
            <h1 class="text-2xl font-bold text-gray-900">{{ $topic->title }}</h1>
            <div class="flex space-x-2 space-x-reverse mt-2 sm:mt-0">
                @if($topic->is_pinned)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        مثبت
                    </span>
                @endif
                @if($topic->is_solved)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        تم الحل
                    </span>
                @endif
            </div>
        </div>
        <div class="mt-2 flex items-center text-sm text-gray-500">
            <span>
                بواسطة <a href="{{ route('profile.show', $topic->user->username) }}" class="font-medium text-blue-600 hover:text-blue-800">{{ $topic->user->name }}</a>
            </span>
            <span class="mx-2">&middot;</span>
            <span>{{ $topic->created_at->format('d/m/Y H:i') }}</span>
            <span class="mx-2">&middot;</span>
            <span>في <a href="{{ route('forum.categories.show', $topic->category->slug) }}" class="font-medium text-blue-600 hover:text-blue-800">{{ $topic->category->name }}</a></span>
            <span class="mx-2">&middot;</span>
            <span>{{ $topic->views }} مشاهدة</span>
        </div>
    </div>

    <!-- محتوى الموضوع -->
    <div class="p-6">
        <div class="flex">
            <!-- معلومات الكاتب -->
            <div class="hidden md:block flex-shrink-0 ml-6">
                <div class="flex flex-col items-center">
                    <img class="h-16 w-16 rounded-full mb-2" src="https://ui-avatars.com/api/?name={{ urlencode($topic->user->name) }}&background=0D8ABC&color=fff" alt="{{ $topic->user->name }}">
                    <a href="{{ route('profile.show', $topic->user->username) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">{{ $topic->user->name }}</a>
                    <div class="text-xs text-gray-500 mt-1">{{ $topic->user->posts->count() }} مشاركة</div>
                    <div class="text-xs text-gray-500">{{ $topic->user->rank ?? 'مبتدئ' }}</div>
                </div>
            </div>

            <!-- نص الموضوع -->
            <div class="flex-1">
                <div class="prose prose-blue max-w-none">
                    {!! $topic->content !!}
                </div>

                <!-- أزرار التحكم بالموضوع -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center space-x-4 space-x-reverse text-sm">
                        @auth
                            @if(auth()->user()->id === $topic->user_id)
                                
                                <form action="{{ route('forum.topics.destroy', $topic->slug) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 flex items-center" onclick="return confirm('هل أنت متأكد من حذف الموضوع؟')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                    <div class="text-sm text-gray-500">
                        آخر تحديث: {{ $topic->updated_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الردود على الموضوع -->
<div class="mb-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">الردود ({{ $topic->posts->count() }})</h2>
    
    @forelse($topic->posts as $post)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4" id="post-{{ $post->id }}">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-3 flex items-center justify-between">
                <div class="flex items-center text-sm">
                    <span>
                        بواسطة <a href="{{ route('profile.show', $post->user->username) }}" class="font-medium text-blue-600 hover:text-blue-800">{{ $post->user->name }}</a>
                    </span>
                    <span class="mx-2">&middot;</span>
                    <span>{{ $post->created_at->format('d/m/Y H:i') }}</span>
                    
                    @if($post->is_solution)
                        <span class="mr-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            الحل
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center space-x-2 space-x-reverse">
                    @auth
                        <form action="{{ route('forum.posts.upvote', $post->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>
                            </button>
                        </form>
                        
                        <span class="text-sm text-gray-600">{{ $post->upvotes - $post->downvotes }}</span>
                        
                        <form action="{{ route('forum.posts.downvote', $post->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <span class="text-sm text-gray-600">{{ $post->upvotes - $post->downvotes }} تقييم</span>
                    @endauth
                </div>
            </div>
            
            <div class="p-6">
                <div class="flex">
                    <!-- معلومات الكاتب -->
                    <div class="hidden md:block flex-shrink-0 ml-6">
                        <div class="flex flex-col items-center">
                            <img class="h-12 w-12 rounded-full mb-2" src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=0D8ABC&color=fff" alt="{{ $post->user->name }}">
                            <a href="{{ route('profile.show', $post->user->username) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">{{ $post->user->name }}</a>
                            <div class="text-xs text-gray-500 mt-1">{{ $post->user->posts->count() }} مشاركة</div>
                        </div>
                    </div>

                    <!-- نص الرد -->
                    <div class="flex-1">
                        <div class="prose prose-blue max-w-none">
                            {!! $post->content !!}
                        </div>

                        <!-- أزرار التحكم بالرد -->
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center space-x-4 space-x-reverse text-sm">
                                @auth
                                    @if(auth()->user()->id === $topic->user_id && !$topic->is_solved)
                                        <form action="{{ route('forum.posts.mark-solution', $post->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                تحديد كحل
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(auth()->user()->id === $post->user_id)
                                        <a href="#" class="text-gray-600 hover:text-gray-900 flex items-center edit-post" data-id="{{ $post->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            تعديل
                                        </a>
                                        <form action="{{ route('forum.posts.destroy', $post->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 flex items-center" onclick="return confirm('هل أنت متأكد من حذف الرد؟')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                حذف
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="#" class="text-blue-600 hover:text-blue-800 flex items-center reply-to-post" data-id="{{ $post->id }}" data-username="{{ $post->user->name }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        رد
                                    </a>
                                @endauth
                            </div>
                            
                            @if($post->updated_at->gt($post->created_at))
                                <div class="text-xs text-gray-500">
                                    تم التعديل: {{ $post->updated_at->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- الردود على هذا المنشور -->
                        @if($post->replies->count() > 0)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">الردود على هذا المنشور ({{ $post->replies->count() }})</h4>
                                <div class="space-y-4">
                                    @foreach($post->replies as $reply)
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center mb-2">
                                                <img class="h-6 w-6 rounded-full ml-2" src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=0D8ABC&color=fff" alt="{{ $reply->user->name }}">
                                                <a href="{{ route('profile.show', $reply->user->username) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">{{ $reply->user->name }}</a>
                                                <span class="mx-2 text-xs text-gray-500">&middot;</span>
                                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="prose prose-sm prose-blue max-w-none">
                                                {!! $reply->content !!}
                                            </div>
                                            
                                            @auth
                                                @if(auth()->user()->id === $reply->user_id)
                                                    <div class="mt-2 flex items-center space-x-4 space-x-reverse text-xs">
                                                        <a href="#" class="text-gray-600 hover:text-gray-900 flex items-center edit-reply" data-id="{{ $reply->id }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                            تعديل
                                                        </a>
                                                        <form action="{{ route('forum.replies.destroy', $reply->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 flex items-center" onclick="return confirm('هل أنت متأكد من حذف هذا الرد؟')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                حذف
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg p-6 text-center shadow-sm">
            <p class="text-gray-500">لا توجد ردود بعد. كن أول من يشارك في هذا الموضوع!</p>
        </div>
    @endforelse
</div>

<!-- نموذج إضافة رد جديد -->
@auth
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">إضافة رد</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('forum.posts.store', $topic->slug) }}" method="POST" id="reply-form">
                @csrf
                <div class="mb-4">
                    <textarea id="content" name="content" rows="5" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="اكتب ردك هنا..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 transition">
                        نشر الرد
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    <div class="bg-white rounded-lg p-6 text-center shadow-sm">
        <p class="text-gray-600">يرجى <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">تسجيل الدخول</a> للمشاركة في هذا الموضوع.</p>
    </div>
@endauth

<!-- نماذج التعديل والرد المخفية -->
<div id="edit-post-form-container" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">تعديل الرد</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500" id="close-edit-post-form">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="" method="POST" id="edit-post-form">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div class="mb-4">
                    <textarea id="edit-post-content" name="content" rows="5" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 transition">
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="edit-reply-form-container" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">تعديل الرد</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500" id="close-edit-reply-form">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="" method="POST" id="edit-reply-form">
            @csrf
            @method('PUT')
            <div class="p-6">
                <div class="mb-4">
                    <textarea id="edit-reply-content" name="content" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-200 transition">
                        حفظ التعديلات
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // تحديث عداد المشاهدات
    document.addEventListener('DOMContentLoaded', function () {
        // النماذج المختلفة
        const editPostFormContainer = document.getElementById('edit-post-form-container');
        const editPostForm = document.getElementById('edit-post-form');
        const editPostContent = document.getElementById('edit-post-content');
        const closeEditPostForm = document.getElementById('close-edit-post-form');
        
        const editReplyFormContainer = document.getElementById('edit-reply-form-container');
        const editReplyForm = document.getElementById('edit-reply-form');
        const editReplyContent = document.getElementById('edit-reply-content');
        const closeEditReplyForm = document.getElementById('close-edit-reply-form');
        
        // أحداث تعديل المنشور
        document.querySelectorAll('.edit-post').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const postId = this.getAttribute('data-id');
                
                // استرجاع بيانات المنشور وملء النموذج
                // في النسخة النهائية يمكن استخدام طلب AJAX لاسترجاع المحتوى
                
                editPostForm.action = `/forum/posts/${postId}`;
                editPostFormContainer.classList.remove('hidden');
            });
        });
        
        if (closeEditPostForm) {
            closeEditPostForm.addEventListener('click', function () {
                editPostFormContainer.classList.add('hidden');
            });
        }
        
        // أحداث تعديل الرد
        document.querySelectorAll('.edit-reply').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const replyId = this.getAttribute('data-id');
                
                // استرجاع بيانات الرد وملء النموذج
                // في النسخة النهائية يمكن استخدام طلب AJAX لاسترجاع المحتوى
                
                editReplyForm.action = `/forum/replies/${replyId}`;
                editReplyFormContainer.classList.remove('hidden');
            });
        });
        
        if (closeEditReplyForm) {
            closeEditReplyForm.addEventListener('click', function () {
                editReplyFormContainer.classList.add('hidden');
            });
        }
        
        // أحداث الرد على منشور
        document.querySelectorAll('.reply-to-post').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const postId = this.getAttribute('data-id');
                const username = this.getAttribute('data-username');
                
                const content = document.getElementById('content');
                content.focus();
                content.value = `@${username} `;
            });
        });
    });
</script>
@endsection