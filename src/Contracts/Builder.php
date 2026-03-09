<?php

namespace Lageg\Reporter\Contracts;

use Lageg\Reporter\Report;

interface Builder
{
    public function using(string $driver): self;
    public function config(array $config): self;
    public function generate(): Report;
    public function chunkrize(): Report;
    public function queue(string $queue = 'default');
}
