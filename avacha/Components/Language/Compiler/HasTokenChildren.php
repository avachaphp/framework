<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler;

use Avacha\Components\Language\Tokens\Token;

interface HasTokenChildren
{
    public function children(): array;
    public function adopt(Token $child): void;
}
