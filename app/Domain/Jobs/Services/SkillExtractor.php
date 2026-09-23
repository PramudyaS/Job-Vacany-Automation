<?php

namespace App\Domain\Jobs\Services;

class SkillExtractor
{
    public function extract(string $text): array
    {
        $text = mb_strtolower($text);
        $found = [];
        foreach (config('job_matching.skill_aliases') as $alias => $canonical) {
            if (preg_match('/(?<![a-z0-9])'.preg_quote($alias, '/').'(?=$|[^a-z0-9])/i', $text)) {
                $found[$canonical] = true;
            }
        }
        return array_keys($found);
    }
}
