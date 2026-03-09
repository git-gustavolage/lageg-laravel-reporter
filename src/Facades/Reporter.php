<?php

namespace Lageg\Reporter\Facades;

use Illuminate\Support\Facades\Facade;
use Lageg\Reporter\Builders\ReportBuilder;
use Lageg\Reporter\Contracts\Builder;
use Lageg\Reporter\Contracts\Chunkrizable;
use Lageg\Reporter\Contracts\Exporter;

class Reporter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'report.builder';
    }

    public static function make(Exporter|Chunkrizable $exporter): Builder
    {
        return new ReportBuilder($exporter);
    }
}
