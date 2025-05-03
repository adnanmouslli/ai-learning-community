<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * عرض لوحة تحكم المدير
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // إحصائيات المستخدمين
        $usersCount = User::count();
        $judgesCount = User::where('is_judge', true)->count();
        
        // إحصائيات المنافسات
        $activeCompetitionsCount = Competition::where('status', 'active')->count();
        $totalCompetitionsCount = Competition::count();
        
        // إحصائيات المشاركات
        $pendingSubmissionsCount = CompetitionSubmission::whereNull('final_score')->count();
        $evaluatedSubmissionsCount = CompetitionSubmission::whereNotNull('final_score')->count();
        
        // أحدث المنافسات
        $latestCompetitions = Competition::latest()->take(5)->get();
        
        // أحدث المشاركات
        $latestSubmissions = CompetitionSubmission::with(['user', 'competition'])
            ->latest()
            ->take(10)
            ->get();
        
        // المستخدمين الجدد
        $newUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'usersCount',
            'judgesCount',
            'activeCompetitionsCount',
            'totalCompetitionsCount',
            'pendingSubmissionsCount',
            'evaluatedSubmissionsCount',
            'latestCompetitions',
            'latestSubmissions',
            'newUsers'
        ));
    }
}