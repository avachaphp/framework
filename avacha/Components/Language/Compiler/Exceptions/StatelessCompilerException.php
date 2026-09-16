<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\Exceptions;

use Exception;
use Throwable;

class StatelessCompilerException extends Exception
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("The working compiler unexpectedely became stateless.", $code, $previous);
    }
}
