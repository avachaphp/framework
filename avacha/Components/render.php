<?php

declare(strict_types=1);

namespace Avacha\Components;

use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Exceptions\CompilationError;
use Avacha\Support\Html\Html;

function component(string $component): string {
    $markup = file_get_contents(BASE_PATH . "/components/$component.avacha");

    if ($markup === false) {
        throw new CompilationError("No Avacha component file for '$component'.");
    }

    return preg_replace("~\R~u", "\n", $markup);
}

function compile(string $component): string {
    $markup = component($component);
    $compiler = new Compiler();

    return $compiler->compile($markup);
}

function render(string $component, array $variables = []): string
{
    $compiler = new Compiler();
    foreach ($variables as $semantic => $object) {
        $compiler->table->allocate($semantic, $object);
    }

    $markup = component($component);
    $php = $compiler->compile($markup);

    try {
        return $compiler->evaluatePhpInScope($php);
    } catch (\ParseError $e) {
        $lines = explode("\n", $php);
        echo Html::tag('pre', htmlspecialchars($php));
        echo '<pre>';
        foreach ($lines as $i => $line) {
            echo htmlspecialchars(($i + 1 === $e->getLine() ? "ERROR> |" : "       | ") . $line . PHP_EOL);
        }
        echo '</pre>';

        throw $e;
    }
}
