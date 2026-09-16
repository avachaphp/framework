<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Tokens\HtmlTokenBuilder;
use Avacha\Support\Html\Html;
use Avacha\Support\Strings\Cursor;
use Override;

class AtHtmlTagName extends CompilerState
{
    public function __construct(
        private readonly HasTokenChildren $parent,
        private readonly HtmlTokenBuilder $html,
    ) {}

    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        if ($cursor->isLookingAt(">")) {
            $compiler->quit();
            return;
        }

        if ($cursor->isLookingAt("@")) {
            $compiler->quit();
            $compiler->replace(new AtComponent($this->parent));
            return;
        }

        $tagName = $cursor->readUntil(" ", "\t", "\n", ">");
        $this->html->setTagName($tagName);

        $compiler->replace(new AtHtmlAttributeList($this->html));
    }
}
