<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JudgeController extends Controller
{
    /**
     * عرض لوحة المحكمين الرئيسية
     */
    public function dashboard()
    {
        // عدد المشاركات بانتظار التقييم
        $pendingSubmissions = CompetitionSubmission::whereNull('final_score')->count();
        
        // عدد التقييمات المكتملة
        $completedEvaluations = CompetitionSubmission::whereNotNull('final_score')
            ->where('evaluated_by', Auth::id())
            ->count();
        
        // عدد المنافسات النشطة
        $activeCompetitions = Competition::where('status', 'active')->count();
        
        // قائمة بأحدث المشاركات بانتظار التقييم
        $pendingSubmissionsList = CompetitionSubmission::with(['user', 'competition'])
            ->whereNull('final_score')
            ->latest()
            ->take(5)
            ->get();
        
        // قائمة بالمنافسات النشطة
        $activeCompetitionsList = Competition::where('status', 'active')
            ->withCount(['submissions as pending_submissions_count' => function($query) {
                $query->whereNull('final_score');
            }])
            ->latest()
            ->take(4)
            ->get();
        
        return view('judge.dashboard', compact(
            'pendingSubmissions',
            'completedEvaluations',
            'activeCompetitions',
            'pendingSubmissionsList',
            'activeCompetitionsList'
        ));
    }
    
    /**
     * عرض قائمة المشاركات بانتظار التقييم
     */
    public function pendingSubmissions()
    {
        $submissions = CompetitionSubmission::with(['user', 'competition'])
            ->whereNull('final_score')
            ->latest()
            ->paginate(15);
        
        return view('judge.submissions.pending', compact('submissions'));
    }
    
    /**
     * عرض قائمة المشاركات التي تم تقييمها
     */
    public function evaluatedSubmissions()
    {
        $submissions = CompetitionSubmission::with(['user', 'competition'])
            ->whereNotNull('final_score')
            ->where('evaluated_by', Auth::id())
            ->latest()
            ->paginate(15);
        
        return view('judge.submissions.evaluated', compact('submissions'));
    }
    
    /**
     * عرض مشاركات منافسة محددة
     */
    public function competitionSubmissions($id)
    {
        $competition = Competition::findOrFail($id);
        
        $pendingSubmissions = $competition->submissions()
            ->with('user')
            ->whereNull('final_score')
            ->latest()
            ->paginate(10, ['*'], 'pending_page');
            
        $evaluatedSubmissions = $competition->submissions()
            ->with('user')
            ->whereNotNull('final_score')
            ->latest()
            ->paginate(10, ['*'], 'evaluated_page');
            
        return view('judge.competitions.submissions', compact(
            'competition',
            'pendingSubmissions',
            'evaluatedSubmissions'
        ));
    }
    
    /**
     * عرض إحصائيات المحكم
     */
    public function statistics()
    {
        $user = Auth::user();
        
        // إجمالي التقييمات
        $totalEvaluations = CompetitionSubmission::where('evaluated_by', $user->id)
            ->count();
        
        // متوسط الدرجات
        $averageScore = CompetitionSubmission::where('evaluated_by', $user->id)
            ->whereNotNull('final_score')
            ->avg('final_score');
        
        // التقييمات حسب المنافسة
        $evaluationsByCompetition = CompetitionSubmission::where('evaluated_by', $user->id)
            ->whereNotNull('final_score')
            ->select('competition_id')
            ->selectRaw('COUNT(*) as count')
            ->with('competition:id,title')
            ->groupBy('competition_id')
            ->get();
        
        // التقييمات في الأيام السبعة الماضية
        $lastWeekEvaluations = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = CompetitionSubmission::where('evaluated_by', $user->id)
                ->whereNotNull('final_score')
                ->whereDate('evaluated_at', $date)
                ->count();
                
            $lastWeekEvaluations[] = [
                'date' => now()->subDays($i)->format('d/m'),
                'count' => $count
            ];
        }
        
        return view('judge.statistics', compact(
            'totalEvaluations',
            'averageScore',
            'evaluationsByCompetition',
            'lastWeekEvaluations'
        ));
    }
}