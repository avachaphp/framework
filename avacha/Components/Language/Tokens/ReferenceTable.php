<?php

namespace Avacha\Components\Language\Tokens;

class ReferenceTable
{
    public array $objects;
    public array $semantics;

    public function __construct()
    {
        $this->objects = [];
        $this->semantics = [];
    }

    public function allocate(string $semantic, mixed $object): string
    {
        $varname = Reference::generatePhpVarName();
        $this->objects[$varname] = $object;
        $this->semantics[$semantic] = $varname;

        return $varname;
    }

    public function dereference(string $varname): mixed
    {
        return $this->objects[$varname];
    }

    public function semantic(string $varname): string
    {
        return $this->semantics[$varname];
    }

    public function generateUseClause(): string {
        if (empty($this->semantics)) {
            return '';
        }

        $semantics = array_map(
            fn($_) => '$' . $_,
            array_keys($this->semantics)
        );

        return "use (" . implode(', ', $semantics) . ")";
    }
}