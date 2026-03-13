<?php

namespace Lageg\Reporter;

use Illuminate\Support\ServiceProvider;
use Lageg\Reporter\Builders\ReportBuilder;

class ReporterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/reporter.php',
            'reporter'
        );

        $this->app->bind('reporter.builder', ReportBuilder::class);

        $this->app->singleton('reporter.manager', function ($app) {
            return new ReporterManager($app);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/reporter.php' => config_path('reporter.php'),
            ], 'reporter');
        }
    }
}
