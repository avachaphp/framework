<?php

declare(strict_types=1);

use Avacha\Env\Config;
use function Avacha\Components\render;
use function Avacha\Env\load_env_file;

load_env_file();
Config::load();

set_exception_handler(function (Throwable $throwable) {
    try {
        echo render("avacha/exception", compact('throwable'));
    } catch (Throwable $renderingThrowable) {
        echo <<<HTML
            <pre>
                $throwable
            </pre>
            
            An exception page failed to render due to another exception:
            <pre>
                $renderingThrowable
            </pre>
        HTML;
    }
});
