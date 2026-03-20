<?php

use Lageg\Reporter\Components\ViewComponent;
use Lageg\Reporter\Contracts\Component;
use Lageg\Reporter\Exporter;
use Tests\Fakes\FakeExportable;

it('can build a exportable instance', function () {
    $exportable = new FakeExportable();

    expect($exportable)->toBeInstanceOf(Exporter::class);
});

it('query view component by class name resolution and alias', function () {
    $exportable = new FakeExportable();
    $exportable->register(new ViewComponent('reports.base', []), 'view');

    expect($exportable->components())->not()->toBeEmpty();

    expect($exportable->has(ViewComponent::class))->toBeTrue();
    expect($exportable->has('view'))->toBeTrue();

    expect($exportable->query(ViewComponent::class))->toBeInstanceOf(Component::class);
    expect($exportable->query('view'))->toBeInstanceOf(Component::class);
});

it('context manipulation works correctly', function () {
    $exportable = new FakeExportable();

    $exportable->addContext('key', 'value');
    expect($exportable->getContext())->toBeArray();

    expect($exportable->context('key'))->toBe('value');

    expect($exportable->context('notFoundKey'))->toBeNull();

    $exportable->setContext(['key' => 'other value']);
    expect($exportable->context('key'))->toBe('other value');
});
