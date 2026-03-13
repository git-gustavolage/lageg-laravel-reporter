<?php

namespace Lageg\Reporter\Builders;

use Lageg\Reporter\Contracts\Builder;
use Lageg\Reporter\Contracts\Driver;
use Lageg\Reporter\Contracts\Exportable;
use Lageg\Reporter\Report;

class ReportBuilder implements Builder
{
    protected string $driver;
    protected Exportable $exportable;
    protected array $config = [];

    public function resolveDriver(): Driver
    {
        if (!$this->driver) {
            $this->driver = config('reporter.default_driver');
        }

        if (class_exists($this->driver)) {
            return app()->make($this->driver);
        }

        return app('reporter.manager')->driver($this->driver);
    }

    public function using(string $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getDriver(): Driver
    {
        return $this->resolveDriver();
    }

    public function make(Exportable $exportable): static
    {
        $this->exportable = $exportable;
        return $this;
    }

    public function config(array $config): static
    {
        $this->config = $config;
        return $this;
    }

    public function generate(): Report
    {
        return $this->resolveDriver()
            ->generate($this->exportable, $this->config);
    }
}
