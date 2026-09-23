<?php

namespace App\Jobs;

use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Models\JobSource;
use App\Domain\Jobs\Services\SkillExtractor;
use App\Domain\Jobs\Sources\JobSourceRegistry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchJobVacancies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(JobSourceRegistry $sources, SkillExtractor $extractor): void
    {
        foreach ($sources->all() as $source) {
            try {
                $sourceModel = JobSource::firstOrCreate(['slug' => $source->slug()], ['name' => $source->name()]);
                foreach ($source->fetchJobs() as $data) {
                    $job = Job::updateOrCreate(['job_source_id' => $sourceModel->id, 'external_id' => $data['external_id']], array_merge($data, ['job_source_id' => $sourceModel->id, 'raw_data' => $data, 'published_at' => $data['published_at'] ?? now()]));
                    $job->skills()->delete();
                    foreach ($extractor->extract($job->title.' '.$job->description) as $skill) {
                        $job->skills()->create(['skill' => $skill]);
                    }
                }
            } catch (\Throwable $exception) {
                Log::warning('Job source collection failed', ['source' => $source->slug(), 'message' => $exception->getMessage()]);
            }
        }
    }
}
