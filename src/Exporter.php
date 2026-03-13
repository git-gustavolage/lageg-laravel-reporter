<?php

namespace Lageg\Reporter;

use Illuminate\Support\Str;
use Lageg\Reporter\Contracts\Component;
use Lageg\Reporter\Contracts\Exportable;

abstract class Exporter implements Exportable
{
    /**
     * @var string|null $filename
     */
    protected ?string $filename = null;

    /**
     * The additional data that can be shared across layers.
     *
     * @var array
     */
    protected array $context = [];

    /**
     * The components registered in the exporter.
     *
     * @var Component[]
     */
    protected array $components = [];


    /**
     * Create a new exporter instance.
     */
    public function __construct()
    {
        $this->filename = Str::uuid()->toString();

        $this->boot();
    }

    /**
     * Boot the exporter.
     *
     * This method may be overridden by child exporters
     * to register components or initialize context data.
     */
    protected function boot(): void {}


    /**
     * Get the filename of the report.
     */
    public function getFilename(): string
    {
        return $this->filename;
    }


    /**
     * Get a value from the exporter context.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function context(string $key, mixed $default = null): mixed
    {
        return $this->context[$key] ?? $default;
    }


    /**
     * Get the full exporter context.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }


    /**
     * Add a value to the exporter context.
     */
    public function addContext(string $key, mixed $value): static
    {
        $this->context[$key] = $value;
        return $this;
    }


    /**
     * Override the exporter context data.
     */
    public function setContext(array $context): static
    {
        $this->context = $context;
        return $this;
    }


    /**
     * Get all registered components.
     *
     * @return Component[]
     */
    public function components(): array
    {
        return $this->components;
    }


    /**
     * @return bool
     */
    public function has(string $component): bool
    {
        if (isset($this->components[$component])) {
            return true;
        }

        foreach ($this->components as $instance) {
            if ($instance instanceof $component) {
                return true;
            }
        }

        return false;
    }


    /**
     * Retrieve a registered component by alias or class name.
     *
     * @return Component|null
     */
    public function query(string $component): ?Component
    {
        if (isset($this->components[$component])) {
            return $this->components[$component];
        }

        foreach ($this->components as $instance) {
            if ($instance instanceof $component) {
                return $instance;
            }
        }

        return null;
    }


    /**
     * Register a component in the exporter.
     *
     * @param Component $component
     * @param string|null $alias
     * @return static
     */
    public function register(Component $component, ?string $alias = null): static
    {
        $name = $alias ?? get_class($component);
        $this->components[$name] = $component;

        return $this;
    }
}
