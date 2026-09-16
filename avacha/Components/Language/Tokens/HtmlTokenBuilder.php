<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Tokens;

use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Exceptions\CompilationError;
use Avacha\Support\Html\HtmlAttributes;
use Override;

class HtmlTokenBuilder implements HasTokenChildren
{
    public function __construct(
        private ?string $tagName = null,
        private array $children = [],
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

    public function setTagName(string $tagName): void
    {
        if ($tagName === "") {
            throw new CompilationError("HTML tag name cannot be empty.");
        }

        $this->tagName = $tagName;
    }

    public function build(): HtmlToken
    {
        if ($this->tagName === null) {
            throw new CompilationError("HTML tag name cannot be null.");
        }

        return new HtmlToken($this->tagName, $this->children, $this->attributes);
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
}
