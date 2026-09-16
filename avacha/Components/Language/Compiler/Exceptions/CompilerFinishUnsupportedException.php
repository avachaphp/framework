<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\Exceptions;

use Avacha\Components\Language\Compiler\States\CompilerState;
use Exception;
use Throwable;

final class CompilerFinishUnsupportedException extends Exception
{
    private function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function whereStateWas(CompilerState $state): static
    {
        return new static(refl_simpleclassname($state) . " cannot be the latest state of a compiler, hence can't finish it's lexing process.");
    }
}
