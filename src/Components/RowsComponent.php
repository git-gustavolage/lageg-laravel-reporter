<?php

namespace Lageg\Reporter\Components;

use Lageg\Reporter\Contracts\Component;

class RowsComponent implements Component
{
    public function __construct(protected array $rows) {}

    public function value(): array
    {
        return $this->rows;
    }
}
