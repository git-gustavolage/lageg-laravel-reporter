<?php

namespace Lageg\Reporter\Traits;

use Lageg\Reporter\Contracts\Component;
use Lageg\Reporter\Exceptions\NotRegisterComponentException;

trait HasComponents
{
    protected array $components = [];

    public function provide(string $component): Component
    {
        $instance = $this->components[$component];

        if (!$instance) {
            throw NotRegisterComponentException::for($component);
        }

        return $instance;
    }

    public function register(Component $component): self
    {
        $name = get_class($component);
        $this->components[$name] = $component;
        return $this;
    }
}
