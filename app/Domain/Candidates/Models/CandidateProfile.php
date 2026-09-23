<?php

namespace App\Domain\Candidates\Models;

use App\Domain\Jobs\Models\JobMatch;
use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    protected $fillable = ['name','target_roles','experience_years','preferred_levels','preferred_locations','work_types','minimum_salary','exclude_keywords'];
    protected function casts(): array { return ['target_roles'=>'array','preferred_levels'=>'array','preferred_locations'=>'array','work_types'=>'array','exclude_keywords'=>'array','minimum_salary'=>'integer']; }
    public function skills() { return $this->hasMany(CandidateSkill::class); }
    public function matches() { return $this->hasMany(JobMatch::class); }
}
