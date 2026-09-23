<?php

namespace App\Domain\Matching\DTOs;

final class MatchResult
{
    public function __construct(
        public readonly float $totalScore,
        public readonly array $scores,
        public readonly array $matchedSkills,
        public readonly array $missingSkills,
        public readonly array $extraJobSkills,
    ) {}
}
