<?php

namespace App\Providers;

use App\View\Composers\ApprovalNotificationComposer;
use App\Models\MasterBackend\SettingInput\JudulHeader;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');
        View::composer('layouts.header', ApprovalNotificationComposer::class);
        View::composer(['layouts.sidebar', 'login.index'], function ($view) {
            $imageColumn = $view->name() === 'layouts.sidebar' ? 'gambar3' : 'gambar4';
            $image = Schema::hasTable('judul_headers')
                ? JudulHeader::query()->value($imageColumn)
                : null;

            if (! $image) {
                $fallbackLogo = $view->name() === 'login.index'
                    ? 'assets/media/logos/logo-1.svg'
                    : 'assets/media/logos/logo-1-dark.svg';
                $logoUrl = asset($fallbackLogo);
            } elseif (preg_match('/^(https?:)?\\/\\//i', $image) || str_starts_with($image, '/')) {
                $logoUrl = $image;
            } elseif (str_starts_with($image, 'assets/')) {
                $logoUrl = asset($image);
            } elseif (str_starts_with($image, 'storage/')) {
                $logoUrl = asset($image);
            } else {
                $image = ltrim(str_replace('\\', '/', $image), '/');
                $image = preg_replace('#^public/#', '', $image);
                $logoUrl = asset('storage/' . $image);
            }

            $view->with('header6LogoUrl', $logoUrl);
        });
    }
}
