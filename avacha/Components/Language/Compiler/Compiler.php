<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler;

use Avacha\Components\Language\Compiler\Exceptions\StatelessCompilerException;
use Avacha\Components\Language\Compiler\States\AtRoot;
use Avacha\Components\Language\Compiler\States\CompilerState;
use Avacha\Components\Language\Tokens\ReferenceTable;
use Avacha\Components\Language\Tokens\RootToken;
use Avacha\Support\Html\Html;
use Avacha\Support\Strings\Cursor;
use SplStack;

final readonly class Compiler
{
    public SplStack $states;
    public ReferenceTable $table;

    public function __construct() {
        $this->states = new SplStack();
        $this->table = new ReferenceTable();
    }

    public function enter(CompilerState $newState): void
    {
        $this->states->push($newState);
    }

    public function replace(CompilerState $newState): void
    {
        $this->quit();
        $this->enter($newState);
    }

    public function quit(): void
    {
        $this->throwIfHasNoState();
        $this->states->pop();
    }

    public function state(): CompilerState
    {
        $this->throwIfHasNoState();
        return $this->states->top();
    }

    public function compile(string $markup): string
    {
        $root = new RootToken();
        $cursor = new Cursor($markup);
        $this->enter(new AtRoot($root));

        while ($this->hasState()) {
            $this->state()->act($this, $cursor);
        }

        return (string) $root;
    }

    public function evaluatePhpInScope(string $php): string {
        try {
            return ob_capture(function () use ($php) {
                extract($this->table->objects);

                foreach ($this->table->semantics as $semantic => $varname) {
                    $php = '$' . "$semantic = " . '$' . "$varname;\n" . $php;
                }

                if (array_key_exists('throwable', $this->table->semantics)) {
                }

                eval($php);
            });
        } catch (\Throwable $error) {
            $message = $error->getMessage();
            $sanitized = htmlspecialchars($php);
            echo Html::tag('pre', "PHP code compiled from Avacha is malformed: $message. The PHP code was:\n---\n$sanitized\n---\n");
            echo Html::tag('pre', (string) $error);

            die;
        }
    }

    private function visualizeAsString(Cursor $cursor): string {
        $visualized = $cursor->visualizeAsString() . PHP_EOL;
        $visualized
            .= iterator_to_array($this->states)
            |> (fn ($_) => implode(' ⊂ ', $_))
            |> (fn ($_) => $_ . PHP_EOL);
        $visualized .= str_repeat('-', 48) . PHP_EOL;


        return Html::tag('pre', htmlspecialchars($visualized));
    }

    private function throwIfHasNoState(): void
    {
        if ($this->hasState()) {
            return;
        }

        throw new StatelessCompilerException();
    }

    private function hasNoState(): bool
    {
        return $this->states->count() === 0;
    }

    private function hasState(): bool
    {
        return ! $this->hasNoState();
    }
}
