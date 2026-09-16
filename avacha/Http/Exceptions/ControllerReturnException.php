<?php

declare(strict_types=1);

namespace Avacha\Http\Exceptions;

use Avacha\Components\Language\Exceptions\BayException;
use Throwable;

final class ControllerReturnException extends BayException
{
    private function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function whereReturnWas(mixed $return): self
    {
        $returnType = get_debug_type($return);

        return new self("$returnType cannot be returned from a controller method, but $return was returned. Only Responses and strings are allowed.");
    }
}
