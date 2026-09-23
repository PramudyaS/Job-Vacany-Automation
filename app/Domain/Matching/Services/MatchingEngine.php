<?php

namespace App\Domain\Matching\Services;

use App\Domain\Candidates\Models\CandidateProfile;
use App\Domain\Jobs\Models\Job;
use App\Domain\Matching\DTOs\MatchResult;
use App\Domain\Matching\Matchers\RoleMatcher;
use App\Domain\Matching\Matchers\SkillMatcher;

class MatchingEngine
{
    public function __construct(private SkillMatcher $skills, private RoleMatcher $roles) {}

    public function match(CandidateProfile $candidate, Job $job): MatchResult
    {
        [$skill, $matched, $missing, $extra] = $this->skills->score($candidate, $job);
        $role = $this->roles->score($candidate->target_roles ?? [], $job->title);
        $experience = $job->experience_min === null ? 70 : ($candidate->experience_years >= $job->experience_min ? 100 : max(0, 100 - (($job->experience_min - $candidate->experience_years) * 30)));
        $work = in_array($job->work_type, $candidate->work_types ?? [], true) ? 100 : 0;
        $location = collect($candidate->preferred_locations ?? [])->contains(fn ($l) => str_contains(mb_strtolower($job->location), mb_strtolower($l))) ? 100 : 0;
        $salary = $job->salary_max === null ? 60 : ($job->salary_max >= ($candidate->minimum_salary ?? 0) ? 100 : 0);
        $scores = compact('skill', 'role', 'experience', 'work', 'location', 'salary');
        $weights = config('job_matching.weights');
        $total = $skill*$weights['skill'] + $role*$weights['role'] + $experience*$weights['experience'] + $work*$weights['work_type'] + $location*$weights['location'] + $salary*$weights['salary'];
        return new MatchResult(round($total, 2), ['skill' => round($skill,2), 'role' => $role, 'experience' => $experience, 'work_type' => $work, 'location' => $location, 'salary' => $salary], $matched, $missing, $extra);
    }
}
