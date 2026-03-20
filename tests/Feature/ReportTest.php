<?php

use Lageg\Reporter\Report;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

beforeEach(function () {
    $this->content = 'Test content';
    $this->mime = 'text/plain';
    $this->filename = 'test.txt';

    $this->report = new Report(
        $this->content,
        $this->mime,
        $this->filename
    );
});

it('returns a file response', function () {
    $response = $this->report->response();

    expect($response)->toBeInstanceOf(\Symfony\Component\HttpFoundation\BinaryFileResponse::class);
});

it('returns a download response with correct headers', function () {
    $response = $this->report->download();

    expect($response)->toBeInstanceOf(Response::class)
        ->and($response->headers->get('Content-Type'))->toBe($this->mime)
        ->and($response->headers->get('Content-Disposition'))
        ->toContain('attachment; filename="' . $this->filename . '"')
        ->and($response->getContent())->toBe($this->content);
});

it('stores the file on disk', function () {
    Storage::fake('local');

    $path = 'reports/test.txt';

    $result = $this->report->store('local', $path);

    Storage::disk('local')->assertExists($path);

    expect($result)->toBe($path);
});

it('creates a temporary file on output', function () {
    $path = $this->report->output();

    expect(file_exists($path))->toBeTrue()
        ->and(file_get_contents($path))->toBe($this->content);

    // cleanup
    unlink($path);
});

it('response uses the generated output file', function () {
    $response = $this->report->response();

    $file = $response->getFile();

    expect($file)->not->toBeNull()
        ->and(file_exists($file->getPathname()))->toBeTrue();

    // cleanup
    unlink($file->getPathname());
});