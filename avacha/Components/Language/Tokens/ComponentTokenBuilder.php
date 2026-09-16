<?php

namespace Avacha\Components\Language\Tokens;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Compiler\HasTokenChildren;
use Avacha\Components\Language\Exceptions\CompilationError;

class ComponentTokenBuilder implements HasTokenChildren
{
    public function __construct(
        private ?ReferenceTable $table = null,
        private ?string $php = null,
        private array $children = [],
    ) {}

    public function children(): array
    {
        return $this->children;
    }

    public function adopt(Token $child): void
    {
        $this->children[] = $child;
    }

    public function setPhp(string $php): void
    {
        $this->php = $php;
    }

    public function setReferenceTable(ReferenceTable $table): void
    {
        $this->table = $table;
    }

    public function build(): ComponentToken
    {
        if ($this->php === null) {
            throw new CompilationError("PHP content was not set.");
        }

        if ($this->table === null) {
            throw new CompilationError("Reference table was not set.");
        }

        return new ComponentToken($this->table, $this->php, $this->children);
    }
}