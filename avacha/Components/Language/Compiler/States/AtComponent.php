<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Exceptions\CompilationError;
use Avacha\Components\Language\Tokens\ComponentTokenBuilder;
use Avacha\Components\Language\Tokens\PhpToken;
use Avacha\Support\Html\Html;
use Avacha\Support\Strings\Cursor;
use Override;
use function Avacha\Components\render;

class AtComponent extends CompilerState
{
    private readonly ComponentTokenBuilder $builder;

    public function __construct(
        private readonly HasTokenChildren $parent,
    ) {
        $this->builder = new ComponentTokenBuilder();
    }

    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        if ($cursor->eat('>')) {
            $this->parent->adopt($this->builder->build());
            $compiler->quit();
            return;
        }

        if ($cursor->eol()) {
            $compiler->enter(new AtLineStart($this->builder));
            return;
        }

        if ($cursor->eat('@')) {
            $compiler->enter(new AtComponentName($this->builder));
            return;
        }

        throw new CompilationError("Unexpected '$cursor' while compiling component tag contents.");
    }
}
