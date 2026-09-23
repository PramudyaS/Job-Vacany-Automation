<?php

namespace App\Jobs;

use App\Domain\Candidates\Models\CandidateProfile;
use App\Domain\Jobs\Models\Job;
use App\Domain\Matching\Filters\HardFilterEngine;
use App\Domain\Matching\Services\MatchingEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateJobMatches implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function handle(MatchingEngine $engine, HardFilterEngine $filters): void
    {
        $candidate = CandidateProfile::with('skills')->first(); if (!$candidate) return;
        foreach (Job::with('skills')->get() as $job) {
            if (!$filters->passes($candidate, $job)) continue;
            $result = $engine->match($candidate, $job);
            $candidate->matches()->updateOrCreate(['job_id' => $job->id], ['total_score'=>$result->totalScore, 'skill_score'=>$result->scores['skill'], 'role_score'=>$result->scores['role'], 'experience_score'=>$result->scores['experience'], 'work_type_score'=>$result->scores['work_type'], 'location_score'=>$result->scores['location'], 'salary_score'=>$result->scores['salary'], 'matched_skills'=>$result->matchedSkills, 'missing_skills'=>$result->missingSkills, 'extra_job_skills'=>$result->extraJobSkills]);
        }
    }
}
