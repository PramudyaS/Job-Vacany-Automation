<?php

namespace App\Domain\Jobs\Sources;

interface JobSourceInterface
{
    public function slug(): string;

    public function name(): string;

    public function fetchJobs(): array;
}
