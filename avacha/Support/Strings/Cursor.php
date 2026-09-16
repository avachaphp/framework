<?php

declare(strict_types=1);

namespace Avacha\Support\Strings;

use Avacha\Components\Language\Exceptions\CompilationError;
use Avacha\Support\Stability\InfiniteLoopDetector;

const EOS = 0;

class Cursor
{
    private readonly InfiniteLoopDetector $loopDetector;

    public function __construct(
        private readonly string $string,
        private int $caret = 0,
    ) {
        $this->loopDetector = new InfiniteLoopDetector();
    }

    public function get(): ?string
    {
        if ($this->loopDetector->isInfiniteLoop()) {
            throw new CompilationError("Infinite loop detected.\n" . htmlspecialchars((string) $this));
        }

        $this->loopDetector->cycle();

        return $this->eof()
            ? null
            : mb_charat($this->string, $this->caret);
    }

    public function eat(string $character): bool
    {
        if (! $this->isLookingAt($character)) {
            return false;
        }

        $this->shift();
        return true;
    }

    public function read(): ?string
    {
        $char = $this->get();
        $this->shift();

        return $char;
    }

    public function getByLength(int $length): ?string
    {
        return $this->eof()
            ? null
            : mb_substr($this->string, $this->caret, $length);
    }

    public function shift(int $offset = 1): void
    {
        $target = $this->caret + $offset;

        if ($target < 0 || $target > mb_strlen($this->string)) {
            throw new CompilationError("Can't shift from $this->caret to an offset of $offset (to $target) in a string of length " . mb_strlen($this->string) . ".");
        }

        $this->caret = $target;
        $this->loopDetector->reset();
    }

    public function eof(): bool
    {
        return $this->caret >= mb_strlen($this->string);
    }

    public function eol(): bool
    {
        return $this->isLookingAt("\n");
    }

    public function isLookingAt(string $character, string ...$more): bool
    {
        $characters = varargs_1ormore_merge($character, ...$more);

        if (in_array("eof", $characters, true) && $this->eof()) {
            return true;
        }

        return in_array($this->get(), $characters);
    }

    public function canRead(string $token, string ...$more): bool
    {
        $tokens = varargs_1ormore_merge($token, ...$more);

        foreach ($tokens as $token) {
            $length = mb_strlen($token);
            $reads = $this->getByLength($length);

            if ($reads === $token) {
                return true;
            }
        }

        return false;
    }

    public function isLookingAtMatch(string $preg): bool
    {
        return preg_match($preg, $this->get()) === 1;
    }

    public function skip(string $character, string ...$more): void
    {
        while (! $this->eof() && $this->isLookingAt($character, ...$more)) {
            $this->shift();
        }
    }

    public function readUntil(string $character, string ...$more): string
    {
        $accumulator = '';

        while (! $this->eof() && ! $this->isLookingAt($character, ...$more)) {
            $accumulator .= $this->read();
        }

        return $accumulator;
    }

    public function readUntilMismatch(string $preg): string
    {
        $accumulator = '';

        while (! $this->eof() && $this->isLookingAtMatch($preg)) {
            $accumulator .= $this->read();
        }

        return $accumulator;
    }

    public function readLine(): string
    {
        $line = $this->readUntil("\n");
        $this->skip("\n");

        return $line;
    }

    public function visualizeAsString(): string
    {
        $string = '';
        $lines = explode("\n", $this->string);
        $chars = 0;

        foreach ($lines as $i => $line) {
            $length = mb_strlen($line) + 1;
            $thisLine = $chars < $this->caret && $chars + $length >= $this->caret;
            $string .= ($thisLine ? "[!] -> |" : "       |") . $line . PHP_EOL;

            if ($thisLine) {
                $string .= str_repeat(' ', $this->caret - $chars + 8 - 1) . '⤴' . PHP_EOL;
            }

            $chars += $length;
        }

        return $string;
    }

    public function __toString(): string
    {
        if ($this->eof()) {
             return "eof";
        }

        return $this->get();
    }
}
