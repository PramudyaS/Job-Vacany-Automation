<?php

namespace App\Domain\Matching\Matchers;

use App\Domain\Candidates\Models\CandidateProfile;
use App\Domain\Jobs\Models\Job;

class SkillMatcher
{
    public function score(CandidateProfile $candidate, Job $job): array
    {
        $wanted = $candidate->skills->keyBy(fn ($s) => mb_strtolower($s->skill));
        $jobSkills = $job->skills->pluck('skill')->map(fn ($s) => mb_strtolower($s))->all();
        $matched = $wanted->keys()->filter(fn ($s) => in_array($s, $jobSkills, true))->values();
        $total = max(1, $wanted->sum('weight'));
        $matchedWeight = $matched->sum(fn ($s) => (int) $wanted[$s]->weight);
        return [min(100, $matchedWeight / $total * 100), $matched->map(fn ($s) => $wanted[$s]->skill)->all(),
            $wanted->keys()->diff($matched)->map(fn ($s) => $wanted[$s]->skill)->values()->all(),
            collect($jobSkills)->diff($wanted->keys())->values()->map(fn ($s) => $job->skills->firstWhere('skill', $s)?->skill ?? $s)->all()];
    }
}
