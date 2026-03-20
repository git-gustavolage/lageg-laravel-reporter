<?php

namespace Tests;

use Lageg\Reporter\ReporterServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ReporterServiceProvider::class,
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['view']->addLocation(__DIR__ . '/Data/views');
    }
}
