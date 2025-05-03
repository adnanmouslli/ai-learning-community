<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumPostController extends Controller
{
    /**
     * إنشاء رد جديد
     */
    public function store(Request $request, $slug)
    {
        $request->validate([
            'content' => 'required|string'
        ]);
        
        $topic = ForumTopic::where('slug', $slug)->firstOrFail();
        
        $post = new ForumPost();
        $post->topic_id = $topic->id;
        $post->user_id = Auth::id();
        $post->content = $request->content;
        $post->save();
        
        
        $user =  User::find(Auth::user()->getAuthIdentifier());    
        // زيادة نقاط المستخدم
        $user->points += 2; // إضافة 2 نقطة للمشاركة
        $user->save();
        
        return redirect()->route('forum.topics.show', $topic->slug)->with('success', 'تم إضافة الرد بنجاح');
    }
    
    /**
     * تحديث رد
     */
    public function update(Request $request, $id)
    {
        $post = ForumPost::findOrFail($id);
        $user =  User::find(Auth::user()->getAuthIdentifier());    

        // التحقق من صلاحية المستخدم
        if (Auth::id() !== $post->user_id && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'غير مصرح لك بتعديل هذا الرد');
        }
        
        $request->validate([
            'content' => 'required|string'
        ]);
        
        $post->content = $request->content;
        $post->save();
        
        return redirect()->route('forum.topics.show', $post->topic->slug)->with('success', 'تم تحديث الرد بنجاح');
    }
    
    /**
     * حذف رد
     */
    public function destroy($id)
    {
        $post = ForumPost::findOrFail($id);
        $user =  User::find(Auth::user()->getAuthIdentifier());    

        // التحقق من صلاحية المستخدم
        if (Auth::id() !== $post->user_id && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'غير مصرح لك بحذف هذا الرد');
        }
        
        $topic = $post->topic;
        $post->delete();
        
        return redirect()->route('forum.topics.show', $topic->slug)->with('success', 'تم حذف الرد بنجاح');
    }
    
    /**
     * تقييم الرد بالإعجاب
     */
    public function upvote($id)
    {
        $post = ForumPost::findOrFail($id);
        $post->increment('upvotes');
        
        // زيادة نقاط صاحب الرد
        $post->user->increment('points');
        
        return redirect()->back();
    }
    
    /**
     * تقييم الرد بعدم الإعجاب
     */
    public function downvote($id)
    {
        $post = ForumPost::findOrFail($id);
        $post->increment('downvotes');
        
        return redirect()->back();
    }
    
    /**
     * تحديد الرد كحل للموضوع
     */
    public function markAsSolution($id)
    {
        $post = ForumPost::with('topic')->findOrFail($id);
        $topic = $post->topic;
        
        $user =  User::find(Auth::user()->getAuthIdentifier());    

        // التحقق من صلاحية المستخدم (فقط صاحب الموضوع يمكنه تحديد الحل)
        if (Auth::id() !== $topic->user_id && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'فقط صاحب الموضوع يمكنه تحديد الحل');
        }
        
        // إزالة علامة الحل من جميع الردود السابقة
        ForumPost::where('topic_id', $topic->id)->update(['is_solution' => false]);
        
        // تحديد هذا الرد كحل
        $post->is_solution = true;
        $post->save();
        
        // تحديث حالة الموضوع
        $topic->is_solved = true;
        $topic->save();
        
        // زيادة نقاط مقدم الحل
        $post->user->increment('points', 10); // 10 نقاط إضافية للحل
        
        return redirect()->back()->with('success', 'تم تحديد الرد كحل للموضوع');
    }
}