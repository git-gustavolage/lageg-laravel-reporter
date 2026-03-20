<?php

use Lageg\Reporter\Components\HeadingsComponent;
use Lageg\Reporter\Components\HtmlComponent;
use Lageg\Reporter\Components\ViewChunkComponent;
use Lageg\Reporter\Components\ViewComponent;

it('renders a view component with the provided data', function () {
    $component = new ViewComponent('reports.template', ['value' => 'Here is some value']);

    $result = $component->value()->render();

    expect($result)->toContain('Here is some value');
});

it('returns headings as an array with the correct number of items', function () {
    $component = new HeadingsComponent(['column1', 'column2']);

    expect($component->value())->toBeArray();
    expect(count($component->value()))->toBe(2);
});

it('returns raw html content without modification', function () {
    $component = new HtmlComponent('<h2>HTML</h2>');

    expect($component->value())->toBeString();
    expect($component->value())->toBe('<h2>HTML</h2>');
});

it('renders a chunked view merging chunk data, context, and metadata', function () {
    $component = new ViewChunkComponent(
        view: 'reports.chunk',
        chunk: [
            'name' => 'ViewChunkComponent'
        ],
        context: [
            'extra' => 'Context value'
        ],
        index: 1,
        size: 10,
        total: 3
    );

    $result = $component->value()->render();

    expect($result)
        ->toContain('Index: 1')
        ->toContain('Size: 10')
        ->toContain('Total: 3')
        ->toContain('Name: ViewChunkComponent')
        ->toContain('Extra: Context value');
});

it('gives precedence to context data when keys collide with chunk data', function () {
    $component = new ViewChunkComponent(
        view: 'reports.chunk',
        chunk: [
            'name' => 'Chunk Name'
        ],
        context: [
            'name' => 'Context Name'
        ],
        index: 1,
        size: 1,
        total: 1
    );

    $result = $component->value()->render();

    expect($result)->toContain('Name: Context Name');
});
