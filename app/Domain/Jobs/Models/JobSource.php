<?php

namespace App\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;

class JobSource extends Model
{
    protected $fillable = ['name','slug'];
    public function jobs() { return $this->hasMany(Job::class); }
}
