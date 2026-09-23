<?php

namespace Database\Seeders;

use App\Domain\Candidates\Models\CandidateProfile;
use App\Jobs\FetchJobVacancies;
use App\Jobs\CalculateJobMatches;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $candidate = CandidateProfile::updateOrCreate(['id' => 1], ['name'=>'Demo candidate','target_roles'=>['Backend Engineer','PHP Developer','Software Engineer'],'experience_years'=>4,'preferred_levels'=>['mid','senior'],'preferred_locations'=>['Jakarta','Remote'],'work_types'=>['remote','hybrid'],'minimum_salary'=>15000000,'exclude_keywords'=>['internship','frontend only']]);
        $candidate->skills()->delete();
        foreach ([['PHP',10],['Laravel',10],['MySQL',8],['Redis',7],['Docker',6]] as [$skill,$weight]) $candidate->skills()->create(compact('skill','weight'));
        FetchJobVacancies::dispatchSync(); CalculateJobMatches::dispatchSync();
    }
}
