<?php

namespace Avacha\Components\Language\Tokens;

class Reference
{
    private function __construct() {}

    public static function generatePhpVarName(): string
    {
        return '_av_' . uniqid();
    }
}