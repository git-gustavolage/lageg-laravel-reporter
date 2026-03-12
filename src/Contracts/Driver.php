<?php

namespace Lageg\Reporter\Contracts;

use Lageg\Reporter\Report;

interface Driver
{
    public function generate(Exportable $exportable, array $config): Report;
}
