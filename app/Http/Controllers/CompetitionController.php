<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetitionController extends Controller
{
    /**
     * عرض قائمة المنافسات
     */
    public function index(Request $request)
    {
        $query = Competition::query();
        
        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // تصفية حسب الحالة
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // المنافسات المميزة
        $featuredCompetitions = Competition::where('is_featured', true)
            ->where('status', 'active')
            ->take(2)
            ->get();
        
        $competitions = $query->latest()->paginate(10);
        
        return view('competitions.index', compact('competitions', 'featuredCompetitions'));
    }
    
    /**
     * عرض منافسة محددة
     */
    public function show($slug)
    {
        $competition = Competition::where('slug', $slug)
            ->with(['submissions', 'rankings' => function($query) {
                $query->with('user')->orderBy('rank');
            }])
            ->firstOrFail();
        
        // البيانات الخاصة بالمستخدم الحالي
        $userSubmissions = null;
        
        if (Auth::check()) {
            $userSubmissions = $competition->submissions()
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }
        
        return view('competitions.show', compact('competition', 'userSubmissions'));
    }
    
    /**
     * عرض لوحة المتصدرين للمنافسة
     */
    public function leaderboard($slug)
    {
        $competition = Competition::where('slug', $slug)
            ->with(['submissions'])
            ->firstOrFail();
        
        $rankings = CompetitionRanking::where('competition_id', $competition->id)
            ->with('user')
            ->orderBy('rank')
            ->paginate(20);
        
        return view('competitions.leaderboard', compact('competition', 'rankings'));
    }
}