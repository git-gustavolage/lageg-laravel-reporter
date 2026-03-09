<?php

namespace Lageg\Reporter\Components;

use Lageg\Reporter\Contracts\Component;

class HtmlComponent implements Component
{
    public function __construct(protected string $html) {}

    public function value(): string
    {
        return $this->html;
    }
}
