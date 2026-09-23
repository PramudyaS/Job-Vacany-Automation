<?php

namespace App\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    protected $fillable = ['job_id','skill'];
    public function job() { return $this->belongsTo(Job::class); }
}
