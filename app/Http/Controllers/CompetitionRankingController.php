<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionRanking;
use App\Models\CompetitionSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompetitionRankingController extends Controller
{
    /**
     * عرض صفحة المتصدرين لمنافسة محددة
     */
    public function index($slug)
    {
        $competition = Competition::where('slug', $slug)
            ->firstOrFail();
        
        $rankings = CompetitionRanking::where('competition_id', $competition->id)
            ->with('user')
            ->orderBy('rank')
            ->paginate(20);
        
        return view('competitions.rankings.index', compact('competition', 'rankings'));
    }
    
    /**
     * تحديث جميع التصنيفات لمنافسة محددة
     */
    public function updateRankings($competitionId)
    {   
        $user = User::find(Auth::user()->getAuthIdentifier());
        // التحقق من صلاحية المستخدم (للمشرفين فقط)
        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'غير مصرح لك بتحديث التصنيفات');
        }
        
        try {
            $competition = Competition::findOrFail($competitionId);
            
            // الحصول على أفضل تقديم لكل مستخدم
            $users = User::whereHas('submissions', function($query) use ($competitionId) {
                $query->where('competition_id', $competitionId)
                      ->whereNotNull('final_score');
            })->get();
            
            foreach ($users as $user) {
                // العثور على أفضل تقديم للمستخدم (بأعلى درجة نهائية)
                $bestSubmission = CompetitionSubmission::where('competition_id', $competitionId)
                    ->where('user_id', $user->id)
                    ->whereNotNull('final_score')
                    ->orderBy('final_score', 'desc')
                    ->first();
                
                if ($bestSubmission) {
                    // تحديث أو إنشاء تصنيف للمستخدم
                    CompetitionRanking::updateOrCreate(
                        [
                            'competition_id' => $competitionId,
                            'user_id' => $user->id
                        ],
                        [
                            'score' => $bestSubmission->final_score
                        ]
                    );
                }
            }
            
            // إعادة حساب الترتيب
            $this->recalculateRanks($competitionId);
            
            return redirect()->back()->with('success', 'تم تحديث تصنيفات المنافسة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث التصنيفات: ' . $e->getMessage());
        }
    }
    
    /**
     * إعادة حساب ترتيب المشاركين في منافسة محددة
     */
    private function recalculateRanks($competitionId)
    {
        $rankings = CompetitionRanking::where('competition_id', $competitionId)
            ->orderBy('score', 'desc')
            ->get();
        
        $rank = 1;
        $lastScore = null;
        $lastRank = 1;
        
        foreach ($rankings as $ranking) {
            // التعامل مع حالة تساوي النقاط (نفس الترتيب)
            if ($lastScore !== null && $ranking->score === $lastScore) {
                $ranking->rank = $lastRank;
            } else {
                $ranking->rank = $rank;
                $lastRank = $rank;
                $lastScore = $ranking->score;
            }
            
            $ranking->save();
            $rank++;
        }
    }
    
    /**
     * عرض صفحة ترتيب مستخدم محدد في المنافسات
     */
    public function userRankings($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $rankings = CompetitionRanking::where('user_id', $user->id)
            ->with('competition')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('competitions.rankings.user', compact('user', 'rankings'));
    }
    
    /**
     * عرض صفحة إحصائيات المنافسة للمشرفين
     */
    public function statistics($slug)
    {
        $user = User::find(Auth::user()->getAuthIdentifier());

        // التحقق من صلاحية المستخدم (للمشرفين فقط)
        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'غير مصرح لك بعرض إحصائيات المنافسة');
        }
        
        $competition = Competition::where('slug', $slug)->firstOrFail();
        
        // إحصائيات عامة
        $totalSubmissions = CompetitionSubmission::where('competition_id', $competition->id)->count();
        $evaluatedSubmissions = CompetitionSubmission::where('competition_id', $competition->id)
            ->whereNotNull('final_score')
            ->count();
        $totalParticipants = CompetitionSubmission::where('competition_id', $competition->id)
            ->distinct('user_id')
            ->count('user_id');
        
        // متوسط الدرجات
        $avgScore = CompetitionSubmission::where('competition_id', $competition->id)
            ->whereNotNull('final_score')
            ->avg('final_score');
        $avgAccuracy = CompetitionSubmission::where('competition_id', $competition->id)
            ->whereNotNull('accuracy_score')
            ->avg('accuracy_score');
        $avgPerformance = CompetitionSubmission::where('competition_id', $competition->id)
            ->whereNotNull('performance_score')
            ->avg('performance_score');
        
        return view('competitions.rankings.statistics', compact(
            'competition',
            'totalSubmissions',
            'evaluatedSubmissions',
            'totalParticipants',
            'avgScore',
            'avgAccuracy',
            'avgPerformance'
        ));
    }
}