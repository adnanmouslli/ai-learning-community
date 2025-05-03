<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\CompetitionSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCompetitionController extends Controller
{
    /**
     * Display a listing of competitions
     */
    public function index()
    {
        $competitions = Competition::withCount('submissions')->latest()->paginate(10);
        return view('admin.competitions.index', compact('competitions'));
    }
    
    /**
     * Show form to create a new competition
     */
    public function create()
    {
        return view('admin.competitions.create');
    }
    
    /**
     * Store a newly created competition
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rules' => 'nullable|string',
            'evaluation_criteria' => 'required|string',
            'dataset_description' => 'nullable|string',
            'dataset_file' => 'nullable|file|max:51200', // 50MB max
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_daily_submissions' => 'required|integer|min:1',
            'is_featured' => 'nullable|boolean'
        ]);
        
        // Generate slug
        $slug = Str::slug($request->title);
        $baseSlug = $slug;
        $counter = 1;
        
        // Ensure slug is unique
        while (Competition::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        // Handle dataset file upload
        $datasetUrl = null;
        if ($request->hasFile('dataset_file')) {
            $file = $request->file('dataset_file');
            $fileName = time() . '_dataset_' . $file->getClientOriginalName();
            $path = $file->storeAs('competition_datasets', $fileName, 'public');
            $datasetUrl = Storage::url($path);
        }
        
        // Determine status based on dates
        $now = now();
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        
        if ($startDate->gt($now)) {
            $status = 'upcoming';
        } elseif ($endDate->lt($now)) {
            $status = 'completed';
        } else {
            $status = 'active';
        }
        
        // Create the competition
        Competition::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'rules' => $request->rules,
            'evaluation_criteria' => $request->evaluation_criteria,
            'dataset_description' => $request->dataset_description,
            'dataset_url' => $datasetUrl,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $status,
            'max_daily_submissions' => $request->max_daily_submissions,
            'is_featured' => $request->has('is_featured')
        ]);
        
        return redirect()->route('admin.competitions.index')
            ->with('success', 'تم إنشاء المنافسة بنجاح');
    }
    
    /**
     * Show competition details
     */
    public function show($id)
    {
        $competition = Competition::with(['submissions' => function($query) {
            $query->with('user')->latest();
        }])->findOrFail($id);
        
        // Get pending submissions (not evaluated yet)
        $pendingSubmissions = $competition->submissions()
            ->whereNull('final_score')
            ->count();
            
        // Get participant count
        $participantsCount = $competition->submissions()
            ->distinct('user_id')
            ->count('user_id');
            
        return view('admin.competitions.show', compact(
            'competition', 
            'pendingSubmissions', 
            'participantsCount'
        ));
    }
    
    /**
     * Show form to edit competition
     */
    public function edit($id)
    {
        $competition = Competition::findOrFail($id);
        return view('admin.competitions.edit', compact('competition'));
    }
    
    /**
     * Update competition
     */
    public function update(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rules' => 'nullable|string',
            'evaluation_criteria' => 'required|string',
            'dataset_description' => 'nullable|string',
            'dataset_file' => 'nullable|file|max:51200', // 50MB max
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_daily_submissions' => 'required|integer|min:1',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:upcoming,active,completed'
        ]);
        
        // Handle dataset file upload
        if ($request->hasFile('dataset_file')) {
            // Remove old file if exists
            if ($competition->dataset_url) {
                $oldPath = str_replace('/storage/', '', $competition->dataset_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $file = $request->file('dataset_file');
            $fileName = time() . '_dataset_' . $file->getClientOriginalName();
            $path = $file->storeAs('competition_datasets', $fileName, 'public');
            $competition->dataset_url = Storage::url($path);
        }
        
        // Update the competition
        $competition->update([
            'title' => $request->title,
            'description' => $request->description,
            'rules' => $request->rules,
            'evaluation_criteria' => $request->evaluation_criteria,
            'dataset_description' => $request->dataset_description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'max_daily_submissions' => $request->max_daily_submissions,
            'is_featured' => $request->has('is_featured')
        ]);
        
        return redirect()->route('admin.competitions.show', $competition->id)
            ->with('success', 'تم تحديث المنافسة بنجاح');
    }
    
    /**
     * Manual status update and competition management
     */
    public function updateStatus(Request $request, $id)
    {
        $competition = Competition::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:upcoming,active,completed'
        ]);
        
        $competition->status = $request->status;
        $competition->save();
        
        return redirect()->back()->with('success', 'تم تحديث حالة المنافسة بنجاح');
    }
    
    /**
     * Show submissions for evaluation
     */
    public function submissions($id)
    {
        $competition = Competition::findOrFail($id);
        
        $pendingSubmissions = $competition->submissions()
            ->with('user')
            ->whereNull('final_score')
            ->latest()
            ->paginate(15);
            
        $evaluatedSubmissions = $competition->submissions()
            ->with('user')
            ->whereNotNull('final_score')
            ->latest()
            ->paginate(15);
            
        return view('admin.competitions.submissions', compact(
            'competition', 
            'pendingSubmissions', 
            'evaluatedSubmissions'
        ));
    }
    
    /**
     * Delete a competition
     */
    public function destroy($id)
    {
        $competition = Competition::findOrFail($id);
        
        // Delete dataset file if exists
        if ($competition->dataset_url) {
            $path = str_replace('/storage/', '', $competition->dataset_url);
            Storage::disk('public')->delete($path);
        }
        
        // Delete all submission files
        foreach ($competition->submissions as $submission) {
            if ($submission->file_path) {
                Storage::disk('public')->delete($submission->file_path);
            }
        }
        
        $competition->delete();
        
        return redirect()->route('admin.competitions.index')
            ->with('success', 'تم حذف المنافسة بنجاح');
    }
}