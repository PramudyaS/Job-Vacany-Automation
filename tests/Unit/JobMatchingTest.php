<?php

namespace Tests\Unit;

use App\Domain\Candidates\Models\CandidateProfile;
use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Services\SkillExtractor;
use App\Domain\Matching\Services\MatchingEngine;
use Tests\TestCase;

class JobMatchingTest extends TestCase
{
    public function test_it_extracts_canonical_skills_from_job_text(): void
    {
        $this->assertSame(['PHP', 'Laravel', 'PostgreSQL', 'Kubernetes'], app(SkillExtractor::class)->extract('Build PHP / Laravel services with PostgreSQL and K8s'));
    }

    public function test_it_weights_important_candidate_skills_more_heavily(): void
    {
        $candidate = new CandidateProfile(['target_roles' => ['Backend Engineer'], 'experience_years' => 4, 'work_types' => ['remote'], 'preferred_locations' => ['Remote']]);
        $candidate->setRelation('skills', collect([(object) ['skill' => 'PHP', 'weight' => 10], (object) ['skill' => 'Docker', 'weight' => 1]]));
        $job = new Job(['title' => 'Backend Engineer', 'work_type' => 'remote', 'location' => 'Remote', 'experience_min' => 3, 'salary_max' => 20000000]);
        $job->setRelation('skills', collect([(object) ['skill' => 'PHP']]));
        $result = app(MatchingEngine::class)->match($candidate, $job);
        $this->assertGreaterThan(80, $result->scores['skill']);
        $this->assertGreaterThan(70, $result->totalScore);
    }
}
