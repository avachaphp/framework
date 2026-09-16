<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Tokens;

abstract class Token
{
    public abstract function toSyntaxTreeString(): string;
}
