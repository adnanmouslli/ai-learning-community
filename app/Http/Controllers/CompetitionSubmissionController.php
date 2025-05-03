<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\CompetitionRanking;
use App\Models\CompetitionSubmission;
use App\Models\User;
use App\Notifications\SubmissionEvaluated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompetitionSubmissionController extends Controller
{
    /**
     * File types allowed for different competition types
     */
    protected $allowedFileTypes = [
        'default' => ['zip', 'csv', 'json', 'ipynb'],
        'image_classification' => ['zip', 'csv', 'json', 'ipynb', 'jpg', 'jpeg', 'png'],
        'nlp' => ['zip', 'csv', 'json', 'ipynb', 'txt'],
        'tabular' => ['zip', 'csv', 'json', 'ipynb', 'xlsx']
    ];
    
    /**
     * Create a new submission form
     */
    public function create($slug)
    {
        $competition = Competition::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();
        
        // Check if user has reached daily submission limit
        $user = User::find(Auth::user()->getAuthIdentifier());
        $dailySubmissionsCount = $user->submissionsToday($competition);
        
        // Get user's previous submissions
        $userSubmissions = CompetitionSubmission::where('competition_id', $competition->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        
        // Get allowed file types for this competition
        $competitionType = $competition->type ?? 'default';
        $allowedExtensions = $this->allowedFileTypes[$competitionType] ?? $this->allowedFileTypes['default'];
        
        return view('competitions.submissions.create', compact(
            'competition', 
            'dailySubmissionsCount', 
            'userSubmissions',
            'allowedExtensions'
        ));
    }
    
    private function processFileUpload(Request $request, Competition $competition)
    {
        if (!$request->hasFile('file')) {
            return [
                'success' => false,
                'error' => 'الملف مطلوب. يرجى اختيار ملف صالح.'
            ];
        }
        
        $file = $request->file('file');
        
        // التحقق من أن الملف صالح
        if (!$file->isValid()) {
            return [
                'success' => false,
                'error' => 'حدث خطأ أثناء رفع الملف. يرجى المحاولة مرة أخرى.'
            ];
        }
        
        // الحصول على نوع المنافسة وامتدادات الملفات المسموح بها
        $competitionType = $competition->type ?? 'default';
        $allowedExtensions = $this->getAllowedFileTypes($competitionType);
        
        // التحقق من امتداد الملف
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedExtensions)) {
            return [
                'success' => false,
                'error' => 'نوع الملف غير مسموح به. الأنواع المسموح بها: ' . implode(', ', $allowedExtensions)
            ];
        }
        
        // التحقق من حجم الملف (10 ميجابايت)
        if ($file->getSize() > 10 * 1024 * 1024) {
            return [
                'success' => false,
                'error' => 'حجم الملف كبير جداً. يجب أن يكون أقل من 10 ميجابايت.'
            ];
        }
        
        try {
            // إنشاء اسم ملف آمن
            $originalName = $file->getClientOriginalName();
            $safeFileName = time() . '_' . Auth::id() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            
            // تخزين الملف
            $filePath = $file->storeAs('competition_submissions/' . $competition->id, $safeFileName, 'public');
            
            return [
                'success' => true,
                'filePath' => $filePath,
                'originalName' => $originalName
            ];
        } catch (\Exception $e) {
            // \Log::error('Error uploading file: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'حدث خطأ أثناء تخزين الملف. يرجى المحاولة مرة أخرى.'
            ];
        }
    }
    
    /**
     * Get allowed file types based on competition type
     */
    private function getAllowedFileTypes($competitionType)
    {
        $allowedTypes = [
            'default' => ['zip', 'csv', 'json', 'ipynb'],
            'image_classification' => ['zip', 'csv', 'json', 'ipynb', 'jpg', 'jpeg', 'png'],
            'nlp' => ['zip', 'csv', 'json', 'ipynb', 'txt'],
            'tabular' => ['zip', 'csv', 'json', 'ipynb', 'xlsx', 'xls']
        ];
        
        return $allowedTypes[$competitionType] ?? $allowedTypes['default'];
    }
    
    /**
     * Store a new submission with improved handling
     */
    public function store(Request $request, $slug)
    {
        try {
            // العثور على المنافسة
            $competition = Competition::where('slug', $slug)
                ->where('status', 'active')
                ->firstOrFail();
            
            // التحقق من حد التقديمات اليومي
            $user = User::find(Auth::user()->getAuthIdentifier());
            $dailySubmissionsCount = $user->submissionsToday($competition);
            
            if ($dailySubmissionsCount >= $competition->max_daily_submissions) {
                return redirect()->back()
                    ->with('error', 'لقد وصلت للحد الأقصى للتقديمات اليومية')
                    ->withInput();
            }
            
            // التحقق من البيانات الأساسية
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:5000',
                'github_url' => 'nullable|url',
                'terms' => 'required|accepted'
            ]);
            
            // معالجة رفع الملف
            $fileResult = $this->processFileUpload($request, $competition);
            
            if (!$fileResult['success']) {
                return redirect()->back()
                    ->with('error', $fileResult['error'])
                    ->withInput();
            }
            
            // إنشاء المشاركة
            $submission = new CompetitionSubmission();
            $submission->competition_id = $competition->id;
            $submission->user_id = Auth::id();
            $submission->title = $validated['title'];
            $submission->description = $validated['description'] ?? '';
            $submission->file_path = $fileResult['filePath'];
            $submission->original_filename = $fileResult['originalName'];
            $submission->github_url = $validated['github_url'] ?? null;
            $submission->save();
            
            // منح النقاط للمستخدم
            $user->points += 5;
            $user->save();
            
            // تحديث المرتبة العالمية للمستخدم
            $user->updateGlobalRank();
            
            return redirect()->route('competitions.show', $competition->slug)
                ->with('success', 'تم تقديم مشاركتك بنجاح');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            // إعادة استثناء التحقق لمعالجته بواسطة Laravel
            throw $e;
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // المنافسة غير موجودة أو ليست نشطة
            return redirect()->route('competitions.index')
                ->with('error', 'المنافسة غير موجودة أو لم تعد نشطة');
                
        } catch (\Exception $e) {
            // تسجيل الخطأ
            // \Log::error('Error creating submission: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            // حذف الملف المرفوع إذا كان موجوداً
            if (isset($fileResult['filePath'])) {
                Storage::disk('public')->delete($fileResult['filePath']);
            }
            
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تقديم المشاركة: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * View submission details
     */
    public function show($id)
    {
        $submission = CompetitionSubmission::with(['competition', 'user'])->findOrFail($id);
        
        // Check if user is authorized to view this submission
        $user = User::find(Auth::user()->getAuthIdentifier());
        if ($user->id !== $submission->user_id && !$user->isAdmin() && !$user->isJudge()) {
            return redirect()->back()
                ->with('error', 'غير مصرح لك بعرض هذه المشاركة');
        }
        
        // Get file info
        $fileInfo = [];
        if ($submission->file_path) {
            $fileInfo = [
                'name' => $submission->original_filename ?: basename($submission->file_path),
                'size' => Storage::disk('public')->size($submission->file_path),
                'url' => Storage::url($submission->file_path)
            ];
        }
        
        return view('competitions.submissions.show', compact('submission', 'fileInfo'));
    }
    
    /**
     * Evaluate a submission
     */
    public function evaluate(Request $request, $id)
    {
        $user = User::find(Auth::user()->getAuthIdentifier());
        
        // Check if user is admin or judge
        if (!$user->isAdmin() && !$user->isJudge()) {
            return redirect()->back()->with('error', 'غير مصرح لك بتقييم المشاركات');
        }
        
        $submission = CompetitionSubmission::findOrFail($id);
        
        $request->validate([
            'accuracy_score' => 'required|numeric|min:0|max:100',
            'performance_score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:5000'
        ]);
        
        // Calculate final score (possibly with weighted formula)
        $finalScore = ($request->accuracy_score * 0.6) + ($request->performance_score * 0.4);
        
        // Update submission
        $submission->accuracy_score = $request->accuracy_score;
        $submission->performance_score = $request->performance_score;
        $submission->final_score = $finalScore;
        $submission->feedback = $request->feedback;
        $submission->evaluated_by = $user->id;
        $submission->evaluated_at = now();
        $submission->save();
        
        // Update ranking
        $this->updateRanking($submission);
        
        // Notify the user about evaluation
        // $submission->user->notify(new SubmissionEvaluated($submission));
        
        return redirect()->back()->with('success', 'تم تقييم المشاركة بنجاح');
    }
    
    /**
     * Update ranking based on submission score
     */
    private function updateRanking($submission)
    {
        // Get user's best submission for this competition
        $bestSubmission = CompetitionSubmission::where('competition_id', $submission->competition_id)
            ->where('user_id', $submission->user_id)
            ->whereNotNull('final_score')
            ->orderBy('final_score', 'desc')
            ->first();
        
        if ($bestSubmission) {
            // Update or create ranking
            $ranking = CompetitionRanking::updateOrCreate(
                [
                    'competition_id' => $submission->competition_id,
                    'user_id' => $submission->user_id
                ],
                [
                    'score' => $bestSubmission->final_score,
                    'points_earned' => $this->calculatePointsEarned($bestSubmission)
                ]
            );
            
            // Recalculate all rankings for this competition
            $this->recalculateRankings($submission->competition_id);
        }
    }
    
    /**
     * Calculate points earned based on score
     */
    private function calculatePointsEarned($submission)
    {
        // Example formula: points based on score
        $basePoints = 10;
        $scorePoints = floor($submission->final_score / 10);
        
        return $basePoints + $scorePoints;
    }
    
    /**
     * Recalculate all rankings for a competition
     */
    private function recalculateRankings($competitionId)
    {
        $rankings = CompetitionRanking::where('competition_id', $competitionId)
            ->orderBy('score', 'desc')
            ->get();
        
        $rank = 1;
        $lastScore = null;
        $lastRank = 1;
        
        foreach ($rankings as $ranking) {
            // Handle tie scores (same rank)
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
}