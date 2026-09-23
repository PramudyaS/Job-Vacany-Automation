<?php

namespace App\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;

class JobMatch extends Model
{
    protected $fillable = ['candidate_profile_id','job_id','total_score','skill_score','role_score','experience_score','work_type_score','location_score','salary_score','matched_skills','missing_skills','extra_job_skills','status'];
    protected function casts(): array { return ['matched_skills'=>'array','missing_skills'=>'array','extra_job_skills'=>'array','status'=>JobMatchStatus::class]; }
    public function job() { return $this->belongsTo(Job::class); }
    public function candidate() { return $this->belongsTo(\App\Domain\Candidates\Models\CandidateProfile::class, 'candidate_profile_id'); }
}
