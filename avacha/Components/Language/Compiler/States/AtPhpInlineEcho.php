<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Exceptions\CompilationError;
use Avacha\Components\Language\Tokens\PhpToken;
use Avacha\Support\Strings\Cursor;
use Override;

class AtPhpInlineEcho extends CompilerState
{
    public function __construct(
        private readonly HasTokenChildren $parent,
    ) {}

    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        $cursor->skip("=");
        $php = $cursor->readUntil("\n");

        if (mb_charat($php, mb_strlen($php) - 1) !== '>') {
            throw new CompilationError("HTML tag with inline PHP echo must be closed.");
        }

        $php = mb_substr($php, 0, -1);
        $cursor->shift(-1);

        $this->parent->adopt(new PhpToken("echo $php;"));

        $compiler->quit();
    }
}
