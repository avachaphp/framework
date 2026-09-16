<?php

declare(strict_types=1);

namespace Avacha\Support\Html;

use function Avacha\Support\Arrays\array_areduce;

final class HtmlAttributes
{
    public function __construct(
        private(set) array $list = [],
    ) {}

    public function set(string $attribute, string $value): void
    {
        $this->list[$attribute] = $value;
    }

    public function get(string $attribute): ?string
    {
        return $this->list[$attribute] ?? null;
    }

    public function exists(string $attribute): bool
    {
        return isset($this->list[$attribute]);
    }

    public function __toString()
    {
        return array_areduce(
            $this->list,
            fn($carry, $attribute, $value) => "$carry $attribute=\"$value\"",
            "",
        );
    }
}
