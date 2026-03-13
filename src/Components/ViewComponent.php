<?php

namespace Lageg\Reporter\Components;

use Illuminate\View\View;
use Lageg\Reporter\Contracts\Component;

class ViewComponent implements Component
{
    public function __construct(protected string $view, protected array $data) {}

    public function value(): View
    {
        return view($this->view, $this->data);
    }
}
