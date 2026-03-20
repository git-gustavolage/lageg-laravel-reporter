<?php

namespace Lageg\Reporter\Contracts;

use Lageg\Reporter\Report;

interface Builder
{
    public function make(Exportable $exportable): static;
    public function using(string $driver): static;
    public function config(array $config): static;
    public function getConfig(): array;
    public function getDriver(): Driver;
    public function generate(): Report;
}
