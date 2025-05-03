<?php

namespace App\Http\Controllers;

use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumReplyController extends Controller
{
    /**
     * إضافة رد على مشاركة
     */
    public function store(Request $request, $post_id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);
        
        $post = ForumPost::findOrFail($post_id);
        
        $reply = new ForumReply();
        $reply->post_id = $post->id;
        $reply->user_id = Auth::id();
        $reply->content = $request->content;
        $reply->save();
        
        // زيادة نقاط المستخدم
        $user = User::find(Auth::user()->getAuthIdentifier());
        $user->points += 1; // إضافة نقطة واحدة للرد
        $user->save();
        
        return redirect()->route('forum.topics.show', $post->topic->slug)->with('success', 'تم إضافة الرد بنجاح');
    }
    
    /**
     * تحديث رد
     */
    public function update(Request $request, $id)
    {
        $reply = ForumReply::findOrFail($id);
        $user = User::find(Auth::user()->getAuthIdentifier());
        
        // التحقق من صلاحية المستخدم
        if (Auth::id() !== $reply->user_id && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'غير مصرح لك بتعديل هذا الرد');
        }
        
        $request->validate([
            'content' => 'required|string'
        ]);
        
        $reply->content = $request->content;
        $reply->save();
        
        return redirect()->route('forum.topics.show', $reply->post->topic->slug)->with('success', 'تم تحديث الرد بنجاح');
    }
    
    /**
     * حذف رد
     */
    public function destroy($id)
    {
        $reply = ForumReply::findOrFail($id);
        $user = User::find(Auth::user()->getAuthIdentifier());
        
        // التحقق من صلاحية المستخدم
        if (Auth::id() !== $reply->user_id && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'غير مصرح لك بحذف هذا الرد');
        }
        
        $topic = $reply->post->topic;
        $reply->delete();
        
        return redirect()->route('forum.topics.show', $topic->slug)->with('success', 'تم حذف الرد بنجاح');
    }
    
    /**
     * تقييم الرد بالإعجاب
     */
    public function upvote($id)
    {
        $reply = ForumReply::findOrFail($id);
        $reply->increment('upvotes');
        
        // زيادة نقاط صاحب الرد
        $reply->user->increment('points');
        
        return redirect()->back();
    }
    
    /**
     * تقييم الرد بعدم الإعجاب
     */
    public function downvote($id)
    {
        $reply = ForumReply::findOrFail($id);
        $reply->increment('downvotes');
        
        return redirect()->back();
    }
}