<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ForumTopicController extends Controller
{
    /**
     * عرض قائمة المواضيع
     */
    public function index(Request $request)
    {
        $query = ForumTopic::with(['user', 'category', 'posts']);
        
        // البحث
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // التصفية حسب الفئة
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }
        
        // ترتيب المواضيع
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $query->latest();
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                case 'most_viewed':
                    $query->orderBy('views', 'desc');
                    break;
                case 'most_replied':
                    $query->withCount('posts')->orderBy('posts_count', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $topics = $query->paginate(10);
        $categories = ForumCategory::all();
        
        return view('forum.topics.index', compact('topics', 'categories'));
    }

    /**
     * عرض نموذج إنشاء موضوع جديد
     */
    public function create()
    {
        $categories = ForumCategory::orderBy('name')->get();
        return view('forum.topics.create', compact('categories'));
    }

    /**
     * حفظ موضوع جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:forum_categories,id',
            'content' => 'required|string'
        ]);
        
        $topic = new ForumTopic();
        $topic->title = $request->title;
        $topic->slug = Str::slug($request->title . '-' . Str::random(5));
        $topic->category_id = $request->category_id;
        $topic->user_id = Auth::id();
        $topic->content = $request->content;
        $topic->save();
        
        // زيادة نقاط المستخدم
        $user =  User::find(Auth::user()->getAuthIdentifier());    
        $user->points += 5; // إضافة 5 نقاط لإنشاء موضوع جديد
        $user->save();
        
        return redirect()->route('forum.topics.show', $topic->slug)->with('success', 'تم إنشاء الموضوع بنجاح');
    }

    /**
     * عرض موضوع محدد
     */
    public function show($slug)
    {
        $topic = ForumTopic::where('slug', $slug)
            ->with(['user', 'category', 'posts' => function($query) {
                $query->with('user', 'replies.user')->orderBy('created_at');
            }])
            ->firstOrFail();
        
        // زيادة عداد المشاهدات
        $topic->increment('views');
        
        return view('forum.topics.show', compact('topic'));
    }

    /**
     * عرض نموذج تعديل موضوع
     */
    public function edit($slug)
    {
        $topic = ForumTopic::where('slug', $slug)->firstOrFail();
        $user =  User::find(Auth::user()->getAuthIdentifier());    

        // التحقق من صلاحية المستخدم
        if (Auth::id() !== $topic->user_id && !$user->isAdmin()) {
            return redirect()->route('forum.topics.show', $topic->slug)->with('error', 'غير مصرح لك بتعديل هذا الموضوع');
        }
        
        $categories = ForumCategory::orderBy('name')->get();
        return view('forum.topics.edit', compact('topic', 'categories'));
    }

    /**
     * تحديث موضوع في قاعدة البيانات
     */
    public function update(Request $request, $slug)
    {
        $topic = ForumTopic::where('slug', $slug)->firstOrFail();
        $user =  User::find(Auth::user()->getAuthIdentifier());    

        // التحقق من صلاحية المستخدم
        if (Auth::id() !== $topic->user_id && !$user->isAdmin()) {
            return redirect()->route('forum.topics.show', $topic->slug)->with('error', 'غير مصرح لك بتعديل هذا الموضوع');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:forum_categories,id',
            'content' => 'required|string'
        ]);
        
        $topic->title = $request->title;
        $topic->category_id = $request->category_id;
        $topic->content = $request->content;
        $topic->save();
        
        return redirect()->route('forum.topics.show', $topic->slug)->with('success', 'تم تحديث الموضوع بنجاح');
    }

    /**
     * حذف موضوع من قاعدة البيانات
     */
    public function destroy($slug)
    {
        $topic = ForumTopic::where('slug', $slug)->firstOrFail();
        
        $user =  User::find(Auth::user()->getAuthIdentifier());    

        // التحقق من صلاحية المستخدم
        if (Auth::id() !== $topic->user_id && !$user->isAdmin()) {
            return redirect()->route('forum.topics.show', $topic->slug)->with('error', 'غير مصرح لك بحذف هذا الموضوع');
        }
        
        // حذف الموضوع
        $topic->delete();
        
        return redirect()->route('forum.index')->with('success', 'تم حذف الموضوع بنجاح');
    }
}