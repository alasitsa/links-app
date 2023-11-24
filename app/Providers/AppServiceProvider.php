<?php

namespace App\Providers;

use App\Repositories\Interfaces\ILinkRepository;
use App\Repositories\LinkRepository;
use App\Services\Interfaces\ILinkService;
use App\Services\LinkService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        ILinkRepository::class => LinkRepository::class,
        ILinkService::class => LinkService::class,
    ];

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
        //
    }
}
