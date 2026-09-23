<?php

namespace App\Domain\Jobs\Sources;

use Illuminate\Support\Facades\Http;

final class RemotiveJobSource implements JobSourceInterface
{
    public function slug(): string
    {
        return 'remotive';
    }

    public function name(): string
    {
        return 'Remotive';
    }

    public function fetchJobs(): array
    {
        $response = Http::acceptJson()->withHeaders(['User-Agent' => 'JobVacancyMatcher/1.0'])
            ->timeout(20)->get(config('job_matching.sources.remotive.url'));
        $response->throw();

        return collect($response->json('jobs', []))
            ->filter(fn ($job) => is_array($job) && isset($job['id'], $job['title'], $job['company_name'], $job['url']))
            ->map(function (array $job) {
                $description = trim(strip_tags($job['description'] ?? ''));
                $text = mb_strtolower($job['title'].' '.$description.' '.implode(' ', $job['tags'] ?? []));

                return [
                    'external_id' => (string) $job['id'],
                    'title' => trim($job['title']),
                    'company_name' => trim($job['company_name']),
                    'description' => $description,
                    'location' => trim($job['candidate_required_location'] ?? '') ?: 'Remote',
                    'work_type' => 'remote',
                    'job_level' => str_contains($text, 'senior') ? 'senior' : (str_contains($text, 'junior') ? 'junior' : 'mid'),
                    'salary_min' => null,
                    'salary_max' => null,
                    'experience_min' => null,
                    'experience_max' => null,
                    'url' => $job['url'],
                    'published_at' => $job['publication_date'] ?? null,
                ];
            })->values()->all();
    }
}
