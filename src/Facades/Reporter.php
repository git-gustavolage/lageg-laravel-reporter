<?php

namespace Lageg\Reporter\Facades;

use Illuminate\Support\Facades\Facade;
use Lageg\Reporter\Builders\ReportBuilder;
use Lageg\Reporter\Contracts\Builder;
use Lageg\Reporter\Contracts\Exportable;

class Reporter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'report.builder';
    }

    public static function make(Exportable $exportable): Builder
    {
        return (new ReportBuilder())->make($exportable);
    }
}
