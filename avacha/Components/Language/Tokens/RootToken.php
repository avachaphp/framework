<?php

namespace Avacha\Components\Language\Tokens;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Support\Html\Html;
use function Avacha\Components\render;

class RootToken extends Token implements HasTokenChildren
{
    public function __construct(
        private array           $children = [],
    ) {
    }

    public function children(): array
    {
        return $this->children;
    }

    public function adopt(Token $child): void
    {
        $this->children[] = $child;
    }

    public function __toString(): string
    {
        return $this->children
            |> (fn($_) => array_map(fn (Token $token) => (string) $token, $_))
            |> implode(...);
    }

    public function toSyntaxTreeString(): string
    {
        $syntaxTree = "[ROOT]";

        if (! empty($this->children)) {
            $syntaxTree .= ":\n";
            foreach ($this->children as $child) {
                $syntaxTree .= mb_tabulate($child->toSyntaxTreeString()) . "\n";
            }
        }

        return htmlspecialchars($syntaxTree);
    }
}