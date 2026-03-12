<?php

namespace Lageg\Reporter;

use Illuminate\Support\Facades\Storage;

class Report
{
    public function __construct(
        public readonly string $content,
        public readonly string $mime,
        public readonly string $filename
    ) {}

    public function download()
    {
        $headers = [
            'Content-Type' => $this->mime,
            'Content-Disposition' => 'attachment; filename="' . $this->filename . '"'
        ];

        return response($this->content, 200, $headers);
    }

    public function store(string $disk, string $path): string
    {
        Storage::disk($disk)->put($path, $this->content);

        return $path;
    }

    public function output(): string
    {
        $path = sys_get_temp_dir() . '/' . $this->filename;

        file_put_contents($path, $this->content);

        return $path;
    }
}
