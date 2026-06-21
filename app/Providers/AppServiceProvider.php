<?php

namespace App\Providers;

use App\Contracts\GuruServiceInterface;
use App\Services\GuruAssignmentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GuruServiceInterface::class, GuruAssignmentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useTailwind();
    }
}
