<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumTopic;

class ForumCategoryController extends Controller
{
    /**
     * عرض قائمة الفئات
     */
    public function index()
    {
        $categories = ForumCategory::orderBy('order')->get();
        return view('forum.categories.index', compact('categories'));
    }

    /**
     * عرض مواضيع فئة محددة
     */
    public function show($slug)
    {
        $category = ForumCategory::where('slug', $slug)->firstOrFail();
        $topics = ForumTopic::where('category_id', $category->id)
            ->with(['user', 'posts'])
            ->latest()
            ->paginate(10);
        
        return view('forum.categories.show', compact('category', 'topics'));
    }
}