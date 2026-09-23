<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidate_profiles', function (Blueprint $t) { $t->id(); $t->string('name'); $t->json('target_roles')->nullable(); $t->unsignedInteger('experience_years')->default(0); $t->json('preferred_levels')->nullable(); $t->json('preferred_locations')->nullable(); $t->json('work_types')->nullable(); $t->unsignedBigInteger('minimum_salary')->nullable(); $t->json('exclude_keywords')->nullable(); $t->timestamps(); });
        Schema::create('candidate_skills', function (Blueprint $t) { $t->id(); $t->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete(); $t->string('skill'); $t->unsignedInteger('weight')->default(1); $t->unique(['candidate_profile_id','skill']); $t->timestamps(); });
        Schema::create('job_sources', function (Blueprint $t) { $t->id(); $t->string('name'); $t->string('slug')->unique(); $t->timestamps(); });
        Schema::create('vacancy_jobs', function (Blueprint $t) { $t->id(); $t->foreignId('job_source_id')->constrained()->cascadeOnDelete(); $t->string('external_id'); $t->string('title'); $t->string('company_name'); $t->text('description'); $t->string('location')->nullable(); $t->string('work_type')->nullable(); $t->string('job_level')->nullable(); $t->unsignedBigInteger('salary_min')->nullable(); $t->unsignedBigInteger('salary_max')->nullable(); $t->unsignedInteger('experience_min')->nullable(); $t->unsignedInteger('experience_max')->nullable(); $t->text('url')->nullable(); $t->timestamp('published_at')->nullable(); $t->json('raw_data')->nullable(); $t->unique(['job_source_id','external_id']); $t->index(['work_type','job_level','published_at']); $t->timestamps(); });
        Schema::create('job_skills', function (Blueprint $t) { $t->id(); $t->foreignId('job_id')->constrained('vacancy_jobs')->cascadeOnDelete(); $t->string('skill'); $t->unique(['job_id','skill']); $t->timestamps(); });
        Schema::create('job_matches', function (Blueprint $t) { $t->id(); $t->foreignId('candidate_profile_id')->constrained()->cascadeOnDelete(); $t->foreignId('job_id')->constrained('vacancy_jobs')->cascadeOnDelete(); $t->decimal('total_score',5,2); foreach (['skill','role','experience','work_type','location','salary'] as $field) $t->decimal($field.'_score',5,2); $t->json('matched_skills')->nullable(); $t->json('missing_skills')->nullable(); $t->json('extra_job_skills')->nullable(); $t->string('status')->default('new')->index(); $t->unique(['candidate_profile_id','job_id']); $t->index(['candidate_profile_id','total_score']); $t->timestamps(); });
    }
    public function down(): void { Schema::dropIfExists('job_matches'); Schema::dropIfExists('job_skills'); Schema::dropIfExists('vacancy_jobs'); Schema::dropIfExists('job_sources'); Schema::dropIfExists('candidate_skills'); Schema::dropIfExists('candidate_profiles'); }
};
