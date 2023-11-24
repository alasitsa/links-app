<?php

namespace App\Providers;

use App\Repositories\Interfaces\ILinkRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\LinkRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\ILinkService;
use App\Services\Interfaces\IUserService;
use App\Services\LinkService;
use App\Services\UserService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $singletons = [
        ILinkRepository::class => LinkRepository::class,
        IUserRepository::class => UserRepository::class,
        IUserService::class => UserService::class,
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
        Blade::if('admin', function() {
            $user = auth()->user();
            return $user && $user->hasRole('admin');
        });
    }
}
