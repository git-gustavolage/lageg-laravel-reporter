<?php

use Lageg\Reporter\ReporterManager;
use Tests\Fakes\FakeCsvDriver;
use Tests\Fakes\FakePdfDriver;

beforeEach(function () {

    config()->set('reporter.default_driver', 'pdf');

    config()->set('reporter.drivers', [

        'pdf' => [
            'class' => FakePdfDriver::class,
            'config' => [
                'orientation' => 'landscape',
            ],
        ],

        'csv' => [
            'class' => FakeCsvDriver::class,
            'config' => [
                'delimiter' => ';',
            ],
        ],
    ]);

    app()->bind(FakePdfDriver::class, fn () => new FakePdfDriver());
    app()->bind(FakeCsvDriver::class, fn () => new FakeCsvDriver());
});

it('resolves manager from container', function () {
    $manager = app('reporter.manager');

    expect($manager)->toBeInstanceOf(ReporterManager::class);
});

it('resolves default driver', function () {
    $manager = app('reporter.manager');

    $driver = $manager->driver();

    expect($driver)->toBeInstanceOf(FakePdfDriver::class);
});

it('resolves configured pdf driver', function () {
    $manager = app('reporter.manager');

    $driver = $manager->driver('pdf');

    expect($driver)->toBeInstanceOf(FakePdfDriver::class);
});

it('resolves configured csv driver', function () {

    $manager = app('reporter.manager');
    $driver = $manager->driver('csv');

    expect($driver)->toBeInstanceOf(FakeCsvDriver::class);
});

it('resolves multiple configured drivers independently', function () {
    $manager = app('reporter.manager');

    $pdf = $manager->driver('pdf');
    $csv = $manager->driver('csv');

    expect($pdf)->toBeInstanceOf(FakePdfDriver::class);
    expect($csv)->toBeInstanceOf(FakeCsvDriver::class);
    expect($pdf)->and($pdf)->not->toBe($csv);
});

it('throws exception when driver is not configured', function () {

    $manager = app('reporter.manager');

    $manager->driver('invalid');
})->throws(InvalidArgumentException::class, 'Driver [invalid] is not configured.');

it('throws exception when driver class is missing', function () {

    config()->set('reporter.drivers.invalid', [
        'config' => [],
    ]);

    $manager = app('reporter.manager');

    $manager->driver('invalid');
})->throws(InvalidArgumentException::class, 'Driver [invalid] does not define a class.');

it('resolves driver through container injection', function () {
    app()->bind(FakePdfDriver::class, function () {
        $driver = new FakePdfDriver();

        $driver->injected = true;

        return $driver;
    });

    $manager = app('reporter.manager');

    $driver = $manager->driver('pdf');

    expect($driver->injected)->toBeTrue();
});

it('caches resolved drivers', function () {
    $manager = app('reporter.manager');

    $first = $manager->driver('pdf');
    $second = $manager->driver('pdf');

    expect($first)->toBe($second);
});