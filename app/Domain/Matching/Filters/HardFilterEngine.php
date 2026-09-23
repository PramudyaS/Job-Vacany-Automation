<?php

namespace App\Domain\Matching\Filters;

use App\Domain\Candidates\Models\CandidateProfile;
use App\Domain\Jobs\Models\Job;

class HardFilterEngine
{
    public function passes(CandidateProfile $candidate, Job $job): bool
    {
        $haystack = mb_strtolower($job->title.' '.$job->description);
        foreach ($candidate->exclude_keywords ?? [] as $keyword) if (str_contains($haystack, mb_strtolower($keyword))) return false;
        if ($candidate->work_types && !in_array($job->work_type, $candidate->work_types, true)) return false;
        if ($candidate->minimum_salary && $job->salary_max !== null && $job->salary_max < $candidate->minimum_salary) return false;
        if ($job->experience_min !== null && $candidate->experience_years + 2 < $job->experience_min) return false;
        return true;
    }
}
