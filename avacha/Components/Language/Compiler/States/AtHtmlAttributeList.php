<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Tokens\HtmlToken;
use Avacha\Components\Language\Tokens\HtmlTokenBuilder;
use Avacha\Support\Strings\Cursor;
use Override;

class AtHtmlAttributeList extends CompilerState
{
    public function __construct(
        private readonly HtmlTokenBuilder $html,
    ) {}

    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        $cursor->skip(' ', "\t");

        if ($cursor->isLookingAt("\n", '>', 'eof')) {
            $compiler->quit();
            return;
        }

        if ($cursor->isLookingAt('=')) {
            $compiler->replace(new AtPhpInlineEcho($this->html));
            return;
        }

        $compiler->enter(new AtHtmlAttribute($this->html));
    }
}
