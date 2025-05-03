<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumTopic;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    /**
     * عرض صفحة المنتدى الرئيسية
     */
    public function index()
    {
        $categories = ForumCategory::orderBy('order')->get();
        $topics = ForumTopic::with(['user', 'category', 'posts'])
            ->latest()
            ->paginate(10);
        
        return view('forum.index', compact('categories', 'topics'));
    }
}