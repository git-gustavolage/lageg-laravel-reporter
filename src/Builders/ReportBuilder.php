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

    protected function resolveDriverName(): string
    {
        return $this->driver ?? config('reporter.default_driver');
    }

    protected function resolveConfig(): array
    {
        $driver = $this->resolveDriverName();

        $defaultConfig = config("reporter.drivers.$driver.config", []);

        return array_merge($defaultConfig, $this->config);
    }

    public function resolveDriver(): Driver
    {
        $driver = $this->resolveDriverName();

        if (class_exists($driver)) {
            return app()->make($driver);
        }

        return app('reporter.manager')->driver($driver);
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
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function generate(): Report
    {
        return $this->resolveDriver()
            ->generate($this->exportable, $this->resolveConfig());
    }
}
