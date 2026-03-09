<?php

namespace Lageg\Reporter\Contracts;

interface Exporter
{
    public function filename(): string;
    public function data(): array;
    public function provide(string $components): Component;
    public function register(Component $component): self;
}
