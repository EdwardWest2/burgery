<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website publik
|--------------------------------------------------------------------------
*/
Route::get('/', [Site\HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [Site\PageController::class, 'show'])->defaults('slug', 'tentang-kami')->name('about');
Route::get('/kebijakan-privasi', [Site\PageController::class, 'show'])->defaults('slug', 'kebijakan-privasi')->name('privacy');

Route::get('/menu', [Site\MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{slug}', [Site\MenuController::class, 'show'])->name('menu.show');

Route::get('/artikel', [Site\ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [Site\ArticleController::class, 'show'])->name('articles.show');

Route::get('/galeri', [Site\GalleryController::class, 'index'])->name('gallery');

Route::get('/kontak', [Site\ContactController::class, 'show'])->name('contact');
Route::post('/kontak', [Site\ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');

/*
|--------------------------------------------------------------------------
| Admin CMS (tidak ada registrasi publik)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::get('articles/{article}/preview', [Admin\ArticleController::class, 'preview'])->name('articles.preview');
        Route::resource('articles', Admin\ArticleController::class)->except('show')->parameters(['articles' => 'article']);
        Route::resource('article-categories', Admin\ArticleCategoryController::class)->only(['index', 'store', 'edit', 'update', 'destroy'])->parameters(['article-categories' => 'category']);

        Route::resource('menu-items', Admin\MenuItemController::class)->except('show')->parameters(['menu-items' => 'item']);
        Route::resource('menu-categories', Admin\MenuCategoryController::class)->only(['index', 'store', 'edit', 'update', 'destroy'])->parameters(['menu-categories' => 'category']);

        Route::resource('pages', Admin\PageController::class)->only(['index', 'edit', 'update'])->parameters(['pages' => 'page']);
        Route::resource('gallery', Admin\GalleryController::class)->only(['index', 'store', 'edit', 'update', 'destroy'])->parameters(['gallery' => 'photo']);
        Route::resource('locations', Admin\LocationController::class)->except('show')->parameters(['locations' => 'location']);
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show')->parameters(['testimonials' => 'testimonial']);
        Route::resource('messages', Admin\ContactMessageController::class)->only(['index', 'show', 'update', 'destroy'])->parameters(['messages' => 'message']);

        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

        Route::get('profile', [Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [Admin\ProfileController::class, 'update'])->name('profile.update');

        Route::post('editor/upload', Admin\EditorUploadController::class)->name('editor.upload');
    });
});
