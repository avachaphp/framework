<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Exceptions\CompilationError;
use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Tokens\HtmlTokenBuilder;
use Avacha\Support\Strings\Cursor;
use Override;

class AtHtml extends CompilerState
{
    private readonly HtmlTokenBuilder $html;

    public function __construct(
        private readonly HasTokenChildren $parent,
    ) {
        parent::__construct();
        $this->html = new HtmlTokenBuilder();
    }

    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        if ($cursor->eof()) {
            $compiler->quit();
            return;
        }

        if ($cursor->eat('<')) {
            $compiler->enter(new AtHtmlTagName($this->parent, $this->html));
            return;
        }

        if ($cursor->eat('>')) {
            $this->parent->adopt($this->html->build());
            $compiler->quit();
            return;
        }

        $compiler->enter(new AtLineStart($this->html));
    }
}
