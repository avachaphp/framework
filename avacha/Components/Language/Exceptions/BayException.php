<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Exceptions;

use Avacha\Exceptions\AvachaException;
use Throwable;

class BayException extends AvachaException
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
