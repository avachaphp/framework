<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Support\Strings\Cursor;
use Override;

class AtLineStart extends CompilerState
{
    public function __construct(
        private readonly HasTokenChildren $parent,
    ) {}

    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        $cursor->skip(" ", "\t", "\n");

        if ($cursor->isLookingAt("\n", '>', 'eof')) {
            $compiler->quit();
            return;
        }

        if ($cursor->isLookingAt('<')) {
            $compiler->replace(new AtHtml($this->parent));
            return;
        }

        if ($cursor->isLookingAt('=')) {
            $compiler->replace(new AtPhpEcho($this->parent));
            return;
        }

        $compiler->replace(new AtPhp($this->parent));
    }
}
