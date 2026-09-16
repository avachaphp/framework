<?php declare(strict_types=1);

namespace Avacha\Support\Html;

readonly final class Html
{
    private function __construct()
    {
        //
    }

    public static function tag(string $name, string $text): string
    {
        if ($text === '') {
            return "<$name />";
        }

        return "<$name>$text</$name>";
    }

    public static function comment(string $text): string
    {
            return "<!--$text-->";
    }
}