<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ForumCategoryController;
use App\Http\Controllers\ForumTopicController;
use App\Http\Controllers\ForumPostController;
use App\Http\Controllers\ForumReplyController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\CompetitionSubmissionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;

// الصفحة الرئيسية
Route::get('/', function () {
    return view('welcome');
});

// مسارات المصادقة
Auth::routes();

// لوحة التحكم
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// المنتدى
Route::prefix('forum')->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('forum.index');
    
    // فئات المنتدى
    Route::get('/categories', [ForumCategoryController::class, 'index'])->name('forum.categories.index');
    Route::get('/categories/{category:slug}', [ForumCategoryController::class, 'show'])->name('forum.categories.show');
    
    // المواضيع
    Route::get('/topics', [ForumTopicController::class, 'index'])->name('forum.topics.index');
    Route::get('/topics/create', [ForumTopicController::class, 'create'])->name('forum.topics.create')->middleware('auth');
    Route::post('/topics', [ForumTopicController::class, 'store'])->name('forum.topics.store')->middleware('auth');
    Route::get('/topics/{topic:slug}', [ForumTopicController::class, 'show'])->name('forum.topics.show');
    Route::get('/topics/{topic:slug}/edit', [ForumTopicController::class, 'edit'])->name('forum.topics.edit')->middleware('auth');
    Route::put('/topics/{topic:slug}', [ForumTopicController::class, 'update'])->name('forum.topics.update')->middleware('auth');
    Route::delete('/topics/{topic:slug}', [ForumTopicController::class, 'destroy'])->name('forum.topics.destroy')->middleware('auth');
    

    // المشاركات
    Route::post('/topics/{topic:slug}/posts', [ForumPostController::class, 'store'])->name('forum.posts.store')->middleware('auth');
    Route::put('/posts/{post}', [ForumPostController::class, 'update'])->name('forum.posts.update')->middleware('auth');
    Route::delete('/posts/{post}', [ForumPostController::class, 'destroy'])->name('forum.posts.destroy')->middleware('auth');
    Route::post('/posts/{post}/upvote', [ForumPostController::class, 'upvote'])->name('forum.posts.upvote')->middleware('auth');
    Route::post('/posts/{post}/downvote', [ForumPostController::class, 'downvote'])->name('forum.posts.downvote')->middleware('auth');
    Route::post('/posts/{post}/mark-solution', [ForumPostController::class, 'markAsSolution'])->name('forum.posts.mark-solution')->middleware('auth');
    
    // الردود
    Route::post('/posts/{post}/replies', [ForumReplyController::class, 'store'])->name('forum.replies.store')->middleware('auth');
    Route::put('/replies/{reply}', [ForumReplyController::class, 'update'])->name('forum.replies.update')->middleware('auth');
    Route::delete('/replies/{reply}', [ForumReplyController::class, 'destroy'])->name('forum.replies.destroy')->middleware('auth');
    Route::post('/replies/{reply}/upvote', [ForumReplyController::class, 'upvote'])->name('forum.replies.upvote')->middleware('auth');
    Route::post('/replies/{reply}/downvote', [ForumReplyController::class, 'downvote'])->name('forum.replies.downvote')->middleware('auth');
});

    // المنافسات
    Route::prefix('competitions')->name('competitions.')->group(function () {
    Route::get('/', [CompetitionController::class, 'index'])->name('index');
    Route::get('/{slug}', [CompetitionController::class, 'show'])->name('show');
    Route::get('/{slug}/leaderboard', [CompetitionController::class, 'leaderboard'])->name('leaderboard');
    
    // Competition submissions routes
    Route::prefix('/{slug}/submissions')->name('submissions.')->middleware(['auth'])->group(function () {
        Route::get('/create', [CompetitionSubmissionController::class, 'create'])->name('create');
        Route::post('/', [CompetitionSubmissionController::class, 'store'])->name('store');
    });
    
    // Direct submission routes (not nested under competition slug)
    Route::prefix('submissions')->name('submissions.')->middleware(['auth'])->group(function () {
        Route::get('/{id}', [CompetitionSubmissionController::class, 'show'])->name('show');
        Route::post('/{id}/evaluate', [CompetitionSubmissionController::class, 'evaluate'])->name('evaluate');
    });
});

// الملف الشخصي
Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/{user:username}/topics', [ProfileController::class, 'topics'])->name('profile.topics');
    Route::get('/{user:username}/posts', [ProfileController::class, 'posts'])->name('profile.posts');
    Route::get('/{user:username}/competitions', [ProfileController::class, 'competitions'])->name('profile.competitions');
});



// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Competitions
    Route::resource('competitions', App\Http\Controllers\Admin\AdminCompetitionController::class);
    Route::get('competitions/{id}/submissions', [App\Http\Controllers\Admin\AdminCompetitionController::class, 'submissions'])->name('competitions.submissions');
    Route::post('competitions/{id}/status', [App\Http\Controllers\Admin\AdminCompetitionController::class, 'updateStatus'])->name('competitions.status');
    
    // Users
    Route::resource('users', App\Http\Controllers\Admin\AdminUserController::class);
    Route::post('users/{id}/remove-role', [App\Http\Controllers\Admin\AdminUserController::class, 'removeRole'])->name('users.remove-role');
    
    // Statistics
    Route::get('statistics', [App\Http\Controllers\Admin\AdminStatisticsController::class, 'index'])->name('statistics');
});


// Judge Routes
// Judge Routes
Route::prefix('judge')->name('judge.')->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Judge\JudgeController::class, 'dashboard'])->name('dashboard');
    
    // Submissions
    Route::get('submissions/pending', [App\Http\Controllers\Judge\JudgeController::class, 'pendingSubmissions'])->name('submissions.pending');
    Route::get('submissions/evaluated', [App\Http\Controllers\Judge\JudgeController::class, 'evaluatedSubmissions'])->name('submissions.evaluated');
    
    // Competition Submissions
    Route::get('competitions/{id}/submissions', [App\Http\Controllers\Judge\JudgeController::class, 'competitionSubmissions'])->name('competitions.submissions');
    
    // Statistics
    Route::get('statistics', [App\Http\Controllers\Judge\JudgeController::class, 'statistics'])->name('statistics');
});