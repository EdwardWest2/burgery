<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use App\Models\ContactMessage;
use App\Models\MenuCategory;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Pagination memakai markup Bootstrap 5 (sesuai template Sarab).
        Paginator::useBootstrapFive();

        // Nama bulan/hari berbahasa Indonesia pada tanggal artikel.
        Carbon::setLocale(config('app.locale', 'id'));

        // Kategori menu untuk navigasi pencarian dan footer.
        View::composer('layouts.app', function ($view) {
            $view->with('navCategories', MenuCategory::orderBy('sort_order')->orderBy('name')->get());
        });

        // Jumlah pesan baru untuk lencana sidebar admin.
        View::composer('layouts.admin', function ($view) {
            $view->with('newMessageCount', ContactMessage::where('status', 'new')->count());
        });

        // Perlindungan spam form kontak: batas per menit dan per hari per IP.
        RateLimiter::for('contact', function (Request $request) {
            return [
                Limit::perMinute(3)->by($request->ip()),
                Limit::perDay(20)->by($request->ip()),
            ];
        });
    }
}
