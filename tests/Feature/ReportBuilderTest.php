<?php

use Lageg\Reporter\Builders\ReportBuilder;
use Lageg\Reporter\Report;
use Tests\Fakes\FakeDriver;
use Tests\Fakes\FakeExportable;

beforeEach(function () {
    config()->set('reporter.default_driver', 'fake');
    config()->set('reporter.drivers.fake.config', [
        'default_key' => 'default_value'
    ]);
});

it('resolves builder from container', function () {
    $instance = app('reporter.builder');

    expect($instance)->toBeInstanceOf(ReportBuilder::class);
});

it('uses default driver from config when none is provided', function () {
    app()->bind('reporter.manager', function () {
        return new class {
            public function driver($name) {
                return new FakeDriver();
            }
        };
    });

    $builder = new ReportBuilder();

    $driver = $builder->getDriver();

    expect($driver)->toBeInstanceOf(FakeDriver::class);
});

it('uses driver class when class exists', function () {
    app()->bind(FakeDriver::class, fn () => new FakeDriver());

    $builder = new ReportBuilder();
    $builder->using(FakeDriver::class);

    $driver = $builder->getDriver();

    expect($driver)->toBeInstanceOf(FakeDriver::class);
});

it('uses manager driver when class does not exist', function () {
    app()->bind('reporter.manager', function () {
        return new class {
            public function driver($name) {
                return new FakeDriver();
            }
        };
    });

    $builder = new ReportBuilder();
    $builder->using('fake');

    $driver = $builder->getDriver();

    expect($driver)->toBeInstanceOf(FakeDriver::class);
});

it('merges default config with custom config', function () {
    app()->bind('reporter.manager', function () {
        return new class {
            public function driver($name) {
                return new FakeDriver();
            }
        };
    });

    $builder = new ReportBuilder();

    $builder->config([
        'custom_key' => 'custom_value'
    ]);

    $reflection = new ReflectionClass($builder);
    $method = $reflection->getMethod('resolveConfig');

    $config = $method->invoke($builder);

    expect($config)->toMatchArray([
        'default_key' => 'default_value',
        'custom_key' => 'custom_value',
    ]);
});

it('make assigns exportable', function () {
    $builder = new ReportBuilder();
    $exportable = new FakeExportable();

    $builder->make($exportable);

    $reflection = new ReflectionClass($builder);
    $property = $reflection->getProperty('exportable');

    expect($property->getValue($builder))->toBe($exportable);
});

it('config merges multiple calls', function () {
    $builder = new ReportBuilder();

    $builder->config(['a' => 1])
            ->config(['b' => 2]);

    $config = $builder->getConfig();

    expect($config)->toMatchArray([
        'a' => 1,
        'b' => 2,
    ]);
});

it('generate calls driver with correct exportable and config', function () {
    $driver = new FakeDriver();

    app()->bind('reporter.manager', function () use ($driver) {
        return new class($driver) {
            public function __construct(private $driver) {}

            public function driver($name) {
                return $this->driver;
            }
        };
    });

    $exportable = new FakeExportable();
    $builder = new ReportBuilder();

    $builder->make($exportable)
        ->config(['custom' => 'value']);

    $report = $builder->generate();

    expect($report)->toBeInstanceOf(Report::class)
        ->and($driver->received['exportable'])->toBe($exportable)
        ->and($driver->received['config'])->toMatchArray([
            'default_key' => 'default_value',
            'custom' => 'value',
        ]);
});

it('using overrides default driver', function () {
    app()->bind('reporter.manager', function () {
        return new class {
            public function driver($name) {
                return new FakeDriver();
            }
        };
    });

    $builder = new ReportBuilder();

    $builder->using('custom');

    $reflection = new ReflectionClass($builder);
    $method = $reflection->getMethod('resolveDriverName');

    $driverName = $method->invoke($builder);

    expect($driverName)->toBe('custom');
});