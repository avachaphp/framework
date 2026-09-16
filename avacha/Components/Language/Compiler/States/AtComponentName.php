<?php

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Compiler\States\CompilerState;
use Avacha\Components\Language\Tokens\ComponentTokenBuilder;
use Avacha\Support\Strings\Cursor;
use function Avacha\Components\render;

class AtComponentName extends CompilerState
{
    public function __construct(
        private readonly ComponentTokenBuilder $builder,
    ) {}

    public function act(Compiler $compiler, Cursor $cursor): void
    {
        $this->builder->setReferenceTable($compiler->table);
        $component = $cursor->readUntilMismatch("/[a-zA-Z_\/]/");
        $this->builder->setPhp("eval(Avacha\Components\compile('$component'));");
        $compiler->quit();
    }
}