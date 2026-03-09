<?php

namespace Lageg\Reporter\Components;

use Lageg\Reporter\Contracts\Component;

class ViewChunkComponent implements Component
{
    public function __construct(
        protected string $view,
        protected array $chunk,
        protected array $context,
        protected int $index,
        protected int $size,
        protected int $total,
    ) {}

    public function value(): mixed
    {
        $context = [
            'chunk_index' => $this->index,
            'chunk_size' => $this->size,
            'total_chunks' => $this->total,
            ...$this->context,
        ];

        return view($this->view, [...$this->chunk, ...$context]);
    }
}
