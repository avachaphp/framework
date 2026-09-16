<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Tokens\RootToken;
use Avacha\Support\Strings\Cursor;
use Override;

class AtRoot extends CompilerState
{
    public function __construct(
        public readonly RootToken $root,
    ) {}


    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        if ($cursor->isLookingAt('>', 'eof')) {
            $compiler->quit();
            return;
        }

        $compiler->enter(new AtLineStart($this->root));
    }
}
