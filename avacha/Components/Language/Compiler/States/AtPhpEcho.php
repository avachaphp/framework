<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Tokens\PhpToken;
use Avacha\Support\Strings\Cursor;
use Override;

class AtPhpEcho extends CompilerState
{
    public function __construct(
        private readonly HasTokenChildren $parent,
    ) {}

    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        $cursor->skip("=");
        $expression = $cursor->readLine();

        $this->parent->adopt(new PhpToken("echo $expression;"));
        $compiler->quit();
    }
}
