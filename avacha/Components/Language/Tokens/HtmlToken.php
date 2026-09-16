<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Tokens;

use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Support\Html\HtmlAttributes;
use Override;

class HtmlToken extends Token implements HasTokenChildren
{
    public function __construct(
        public string $tagName,
        public protected(set) array $children = [],
        public readonly HtmlAttributes $attributes = new HtmlAttributes(),
    ) {}

    #[Override]
    public function children(): array
    {
        return $this->children;
    }

    #[Override]
    public function adopt(Token $child): void
    {
        $this->children[] = $child;
    }

    public function __toString()
    {
        $body = implode(PHP_EOL, $this->children);

        return "
        echo '<$this->tagName $this->attributes>';
            {$body}
        echo '</$this->tagName>';
        ";
    }

    public function toSyntaxTreeString(): string
    {
        $syntaxTree = "[HTML | $this->tagName, $this->attributes]";

        if (! empty($this->children)) {
            $syntaxTree .= ":\n";
            foreach ($this->children as $child) {
                $syntaxTree .= mb_tabulate($child->toSyntaxTreeString()) . "\n";
            }
        }

        return $syntaxTree;
    }
}
