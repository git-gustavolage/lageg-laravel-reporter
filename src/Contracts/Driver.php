<?php

namespace Lageg\Reporter\Contracts;

use Lageg\Reporter\Report;

interface Driver
{
    public function config(array $config = []): self;
    public function generate(Exporter $exporter): Report;
    public function chunkrize(Chunkrizable $chunkrizable): Report;
}
