<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\Exceptions;

use Avacha\Components\Language\Exceptions\BayException;
use Throwable;

abstract class CompilerException extends BayException
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
