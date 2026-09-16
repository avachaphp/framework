<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Exceptions;

use Throwable;

class CompilationError extends BayException
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
