<?php

namespace Lageg\Reporter\Builders;

use Lageg\Reporter\Contracts\Builder;
use Lageg\Reporter\Contracts\Chunkrizable;
use Lageg\Reporter\Contracts\Driver;
use Lageg\Reporter\Contracts\Exporter;
use Lageg\Reporter\Jobs\GenerateReportJob;
use Lageg\Reporter\Report;

class ReportBuilder implements Builder
{
    protected ?string $driverClass = null;
    protected array $config = [];

    public function __construct(protected Exporter|Chunkrizable $exporter) {}

    public function resolveDriver(): Driver
    {
        if (!$this->driverClass) {
            throw new \RuntimeException('No report driver defined.');
        }

        return app($this->driverClass);
    }

    public function using(string $driver): Builder
    {
        $this->driverClass = $driver;
        return $this;
    }

    public function config(array $config): Builder
    {
        $this->config = $config;
        return $this;
    }

    public function generate(): Report
    {
        return $this->resolveDriver()
            ->config($this->config)
            ->generate($this->exporter);
    }

    public function chunkrize(): Report
    {
        return $this->resolveDriver()
            ->config($this->config)
            ->chunkrize($this->exporter);
    }

    public function queue(string $queue = 'default')
    {
        dispatch(new GenerateReportJob(
            $this->exporter,
            $this->driverClass,
            $this->config
        ))->onQueue($queue);
    }
}
