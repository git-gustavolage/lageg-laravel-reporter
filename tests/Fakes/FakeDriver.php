<?php

namespace Tests\Fakes;

use Lageg\Reporter\Contracts\Driver;
use Lageg\Reporter\Contracts\Exportable;
use Lageg\Reporter\Report;

class FakeDriver implements Driver {
    public array $received = [];

    public function generate(Exportable $exportable, array $config): Report
    {
        $this->received = [
            'exportable' => $exportable,
            'config' => $config,
        ];

        return new Report('content', 'text/plain', 'file.txt');
    }
}