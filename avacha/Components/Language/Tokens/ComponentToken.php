<?php

namespace Avacha\Components\Language\Tokens;

use Avacha\Components\Language\Compiler\HasTokenChildren;

class ComponentToken extends Token implements HasTokenChildren
{
    public function __construct(
        private readonly ReferenceTable $table,
        private readonly string         $php,
        private array                   $children = [],
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
        $children
                =  array_map(fn($_) => (string)$_, $this->children)
                |> (fn($x) => implode(PHP_EOL, $x))
                |> (fn($x) => var_export($x, true));

        $uses = $this->table->generateUseClause();

        return <<<PHP
            (function (string \$children) $uses {
                $this->php;
            })($children);
        PHP;
    }

    public function toSyntaxTreeString(): string
    {
        $syntaxTree = "[COMPONENT]";

        $syntaxTree .= mb_tabulate("PHP = " . $this->php);
        if (! empty($this->children)) {
            $syntaxTree .= ":\n";
            foreach ($this->children as $child) {
                $syntaxTree .= mb_tabulate($child->toSyntaxTreeString()) . "\n";
            }
        }

        return $syntaxTree;
    }
}