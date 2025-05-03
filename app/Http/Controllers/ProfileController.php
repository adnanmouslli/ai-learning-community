<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\CompetitionRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * عرض ملف المستخدم الشخصي
     */
    public function index()
    {
        $user = Auth::user();
        return redirect()->route('profile.show', $user->username);
    }
    
    /**
     * عرض ملف شخصي محدد
     */
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $recentTopics = ForumTopic::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        $recentPosts = ForumPost::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->with('topic')
            ->get();
        
        $topCompetitions = CompetitionRanking::where('user_id', $user->id)
            ->with('competition')
            ->orderBy('rank')
            ->take(5)
            ->get();
        
        return view('profile.show', compact('user', 'recentTopics', 'recentPosts', 'topCompetitions'));
    }
    
    /**
     * عرض نموذج تعديل الملف الشخصي
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    /**
     * تحديث بيانات الملف الشخصي
     */
    public function update(Request $request)
    {
        $user =  User::find(Auth::user()->getAuthIdentifier());    
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|max:2048', // أقصى حجم 2 ميجابايت
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // التحقق من كلمة المرور الحالية
        if ($request->filled('current_password') && !Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }
        
        // تحديث البيانات الأساسية
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->bio = $request->bio;
        
        // تحديث كلمة المرور إذا تم تغييرها
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        // معالجة الصورة الشخصية
        if ($request->hasFile('profile_picture')) {
            // حذف الصورة القديمة إذا وجدت
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // رفع الصورة الجديدة
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }
        
        $user->save();
        
        return redirect()->route('profile.index')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
    
    /**
     * عرض مواضيع المستخدم
     */
    public function topics($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $topics = ForumTopic::where('user_id', $user->id)
            ->with(['category', 'user'])
            ->latest()
            ->paginate(10);
        
        return view('profile.topics', compact('user', 'topics'));
    }
    
    /**
     * عرض ردود المستخدم
     */
    public function posts($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $posts = ForumPost::where('user_id', $user->id)
            ->with(['topic', 'user'])
            ->latest()
            ->paginate(10);
        
        return view('profile.posts', compact('user', 'posts'));
    }
    
    /**
     * عرض منافسات المستخدم
     */
    public function competitions($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        $rankings = CompetitionRanking::where('user_id', $user->id)
            ->with(['competition'])
            ->orderBy('rank')
            ->paginate(10);
        
        return view('profile.competitions', compact('user', 'rankings'));
    }
}