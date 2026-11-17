<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ContactMessage;
use App\Models\MenuItem;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'stats' => [
                'articles' => Article::count(),
                'published' => Article::where('status', Article::STATUS_PUBLISHED)->count(),
                'drafts' => Article::where('status', Article::STATUS_DRAFT)->count(),
                'categories' => ArticleCategory::count(),
                'menus' => MenuItem::count(),
                'messages' => ContactMessage::count(),
                'new_messages' => ContactMessage::where('status', 'new')->count(),
            ],
            'latest' => Article::with(['category', 'author'])->latest('updated_at')->take(6)->get(),
        ]);
    }
}
