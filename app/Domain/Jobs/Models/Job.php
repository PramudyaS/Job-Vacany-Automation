<?php

namespace App\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'vacancy_jobs';
    protected $fillable = ['job_source_id','external_id','title','company_name','description','location','work_type','job_level','salary_min','salary_max','experience_min','experience_max','url','published_at','raw_data'];
    protected function casts(): array { return ['published_at'=>'datetime','raw_data'=>'array']; }
    public function source() { return $this->belongsTo(JobSource::class, 'job_source_id'); }
    public function skills() { return $this->hasMany(JobSkill::class); }
    public function matches() { return $this->hasMany(JobMatch::class); }
}
