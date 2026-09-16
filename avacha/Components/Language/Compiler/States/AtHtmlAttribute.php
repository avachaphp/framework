<?php

declare(strict_types=1);

namespace Avacha\Components\Language\Compiler\States;

use Avacha\Components\Language\Exceptions\CompilationError;
use Avacha\Components\Language\Compiler\Compiler;
use Avacha\Components\Language\Tokens\HtmlToken;
use Avacha\Components\Language\Tokens\HtmlTokenBuilder;
use Avacha\Support\Strings\Cursor;
use Override;

class AtHtmlAttribute extends CompilerState
{
    private const string HTML_ATTRIBUTE_NAME_REGEX = '/^[a-zA-Z0-9-]+$/';

    public function __construct(
        private readonly HtmlTokenBuilder $html,
    ) {}

    #[Override]
    public function act(Compiler $compiler, Cursor $cursor): void
    {
        $cursor->skip(' ', "\t");

        if ($cursor->isLookingAt("\n", '>', 'eof')) {
            $compiler->quit();
            return;
        }

        if (! $cursor->isLookingAtMatch(static::HTML_ATTRIBUTE_NAME_REGEX)) {
            throw new CompilationError("HTML attribute names cannot start with '$cursor'.");
        }

        $name = $cursor->readUntil('=');

        if (! preg_match(static::HTML_ATTRIBUTE_NAME_REGEX, $name)) {
            throw new CompilationError("'$name' is not a valid HTML attribute name.");
        }

        $cursor->skip('=');
        $cursor->skip('"');
        $value = $cursor->readUntil('"');
        $cursor->skip('"');

        $this->html->attributes->set($name, $value);
        $compiler->quit();
    }
}
