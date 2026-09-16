<?php declare(strict_types=1);

namespace Avacha\Env;

class Config {
    public static bool $debug;
    public static string $templates_path;
    public static string $templates_cache_path;
    public static string $assets_url;

    public static function load(): void
    {
        static::$debug = env('DEBUG', true);
        static::$templates_path = env('TEMPLATES', '/components');
        static::$assets_url = env('ASSETS_URL', '/assets');
        static::$templates_cache_path = env('TEMPLATES_CACHE', '/cache/components');
    }
}