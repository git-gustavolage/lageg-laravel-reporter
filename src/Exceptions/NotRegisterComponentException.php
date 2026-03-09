<?php

namespace Lageg\Reporter\Exceptions;

use Exception;

class NotRegisterComponentException extends Exception
{
    public static function for(string $component): self
    {
        return new self("Component [{$component}] is not registered in exporter.");
    }
}
