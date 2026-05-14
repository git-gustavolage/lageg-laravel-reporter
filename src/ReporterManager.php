<?php

namespace Lageg\Reporter;

use Illuminate\Support\Manager;
use InvalidArgumentException;
use Lageg\Reporter\Contracts\Driver;
use Override;

class ReporterManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return config('reporter.default_driver', 'pdf');
    }

        #[Override]
    protected function createDriver($driver): Driver
    {
        $config = config("reporter.drivers.$driver");

        if (! $config) {
            throw new InvalidArgumentException("Driver [$driver] is not configured.");
        }

        $class = $config['class'] ?? null;

        if (! $class) {
            throw new InvalidArgumentException("Driver [$driver] does not define a class.");
        }

        return $this->container->make($class);
    }
}
