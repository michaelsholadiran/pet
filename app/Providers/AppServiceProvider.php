<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
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
        View::composer('layouts.app', function ($view) {
            $name = Route::currentRouteName();
            $meta = $name ? (config("pages.{$name}") ?? []) : [];
            $defaults = [
                'page_title' => $meta['title'] ?? config('puppiary.name'),
                'page_description' => $meta['description'] ?? config('puppiary.name').' - Puppy toys, teething & starter kit.',
                'page_canonical' => $meta['canonical'] ?? '/',
                'current_nav' => $meta['nav'] ?? null,
                'robots_noindex' => $meta['robots_noindex'] ?? false,
                'page_og_image' => $meta['og_image'] ?? null,
            ];
            foreach ($defaults as $key => $value) {
                if (! array_key_exists($key, view()->getShared())) {
                    $view->with($key, $value);
                }
            }
        });
    }
}
