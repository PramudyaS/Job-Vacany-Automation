<?php

namespace App\Providers;

use App\Domain\Jobs\Sources\ArbeitnowJobSource;
use App\Domain\Jobs\Sources\JobSourceInterface;
use App\Domain\Jobs\Sources\JobSourceRegistry;
use App\Domain\Jobs\Sources\RemoteOkJobSource;
use App\Domain\Jobs\Sources\RemotiveJobSource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(JobSourceInterface::class, RemoteOkJobSource::class);
        $this->app->singleton(JobSourceRegistry::class, fn ($app) => new JobSourceRegistry([
            $app->make(RemoteOkJobSource::class),
            $app->make(ArbeitnowJobSource::class),
            $app->make(RemotiveJobSource::class),
        ]));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
