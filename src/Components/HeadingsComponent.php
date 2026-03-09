<?php

namespace Lageg\Reporter\Components;

use Lageg\Reporter\Contracts\Component;

class HeadingsComponent implements Component
{
    public function __construct(protected array $headings) {}

    public function value(): array
    {
        return $this->headings;
    }
}
