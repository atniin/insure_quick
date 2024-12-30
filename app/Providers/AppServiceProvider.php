<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Booking\Infrastructure\Repositories\BookingRepositoryInterface;
use App\Domain\Booking\Infrastructure\Repositories\EloquentBookingRepository;
use App\Domain\Agent\Infrastructure\Repositories\AgentRepositoryInterface;
use App\Domain\Agent\Infrastructure\Repositories\EloquentAgentRepository;
use App\Domain\Client\Infrastructure\Repositories\ClientRepositoryInterface;
use App\Domain\Client\Infrastructure\Repositories\EloquentClientRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BookingRepositoryInterface::class, EloquentBookingRepository::class);
        $this->app->bind(AgentRepositoryInterface::class, EloquentAgentRepository::class);
        $this->app->bind(ClientRepositoryInterface::class, EloquentClientRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
