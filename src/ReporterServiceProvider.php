<?php

namespace Lageg\Reporter;

use Illuminate\Support\ServiceProvider;
use Lageg\Reporter\Builders\ReportBuilder;

class ReporterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('report.builder', function ($app) {
            return function ($exporter) {
                return new ReportBuilder($exporter);
            };
        });
    }

    public function boot(): void
    {
        //
    }
}
