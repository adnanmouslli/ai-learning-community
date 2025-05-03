<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * إنشاء مثيل جديد للمتحكم
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض لوحة التحكم
     */
    public function index()
    {
        $user =  User::find(Auth::user()->getAuthIdentifier());    
        
        // إحصائيات المستخدم
        $topicsCount = $user->forumTopics()->count();
        $postsCount = $user->forumPosts()->count();
        $submissionsCount = $user->competitionSubmissions()->count();
        
        // المنافسات النشطة
        $activeCompetitions = Competition::where('status', 'active')
            ->latest()
            ->take(5)
            ->get();
        
        // آخر المواضيع
        $recentTopics = $user->forumTopics()
            ->latest()
            ->take(5)
            ->get();
        
        // أفضل المستخدمين
        $topUsers = User::orderBy('points', 'desc')
            ->take(5)
            ->get();
        
        return view('dashboard', compact(
            'user',
            'topicsCount',
            'postsCount',
            'submissionsCount',
            'activeCompetitions',
            'recentTopics',
            'topUsers'
        ));
    }
}