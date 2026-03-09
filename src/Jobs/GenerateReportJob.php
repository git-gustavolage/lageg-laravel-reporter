<?php

namespace Lageg\Reporter\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Lageg\Reporter\Contracts\Exporter;

class GenerateReportJob implements ShouldQueue
{
    public function __construct(
        protected Exporter $exporter,
        protected string $driver,
        protected array $config = []
    ) {}

    public function handle()
    {
        $driver = app($this->driver);

        $driver->generate($this->exporter, $this->config);
    }
}
