<?php

namespace Avacha\Support\Stability;

class InfiniteLoopDetector
{
    private int $cycles = 0;

    public function __construct(
        private readonly int $threshold = 100,
    ) {}

    public function cycle(): void
    {
        $this->cycles++;
    }

    public function isInfiniteLoop(): bool
    {
        return $this->cycles >= $this->threshold;
    }

    public function reset(): void
    {
        $this->cycles = 0;
    }
}