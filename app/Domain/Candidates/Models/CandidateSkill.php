<?php

namespace App\Domain\Candidates\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateSkill extends Model
{
    protected $fillable = ['candidate_profile_id','skill','weight'];
    public function candidate() { return $this->belongsTo(CandidateProfile::class, 'candidate_profile_id'); }
}
