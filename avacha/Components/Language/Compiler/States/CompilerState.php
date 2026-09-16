<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Support\Strings\Cursor;

abstract class CompilerState
{
    public function __construct() {}

    abstract public function act(Compiler $compiler, Cursor $cursor): void;

    public function name(): string {
        return mb_substr(refl_simpleclassname($this), strlen("At"));
    }

    public function __toString()
    {
        return $this->name();
    }
}
