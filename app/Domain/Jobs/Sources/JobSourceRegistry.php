<?php

namespace App\Domain\Jobs\Sources;

final class JobSourceRegistry
{
    /** @param array<JobSourceInterface> $sources */
    public function __construct(private readonly array $sources) {}

    /** @return array<JobSourceInterface> */
    public function all(): array
    {
        return $this->sources;
    }
}
