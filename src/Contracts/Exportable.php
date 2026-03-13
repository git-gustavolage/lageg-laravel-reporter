<?php

namespace Lageg\Reporter\Contracts;

interface Exportable
{
    public function getFilename(): string;

    public function context(string $key, mixed $default = null): mixed;
    public function getContext(): array;
    public function addContext(string $key, mixed $value): static;
    public function setContext(array $context): static;

    /** @return Component[] */
    public function components(): array;
    public function has(string $component): bool;
    public function query(string $component): ?Component;
    public function register(Component $component): self;
}
