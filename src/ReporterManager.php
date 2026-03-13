<?php

namespace Lageg\Reporter;

use Illuminate\Support\Manager;
use Lageg\Reporter\Contracts\Driver;

class ReporterManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return config('reporter.default_driver', 'pdf');
    }

    public function createPdfDriver(): Driver
    {
        $class = config('reporter.drivers.pdf.class');

        return $this->container->make($class);
    }

    public function createXlsxDriver(): Driver
    {
        $class = config('reporter.drivers.xlsx.class');

        return $this->container->make($class);
    }

    public function createCsvDriver(): Driver
    {
        $class = config('reporter.drivers.csv.class');

        return $this->container->make($class);
    }
}
