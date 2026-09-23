<?php

namespace App\Domain\Jobs\Sources;

use Illuminate\Support\Facades\Http;

final class RemoteOkJobSource implements JobSourceInterface
{
    public function slug(): string
    {
        return 'remote-ok';
    }

    public function name(): string
    {
        return 'Remote OK';
    }

    public function fetchJobs(): array
    {
        $response = Http::acceptJson()->withHeaders(['User-Agent' => 'JobVacancyMatcher/1.0'])->timeout(20)->get(config('job_matching.sources.remote_ok.url'));
        $response->throw();

        return collect($response->json())->skip(1)->filter(fn ($job) => is_array($job) && isset($job['id'], $job['position'], $job['company']))->map(function (array $job) {
            $title = trim($job['position']);
            $description = trim(strip_tags($job['description'] ?? ''));
            $text = mb_strtolower($title.' '.$description.' '.implode(' ', $job['tags'] ?? []));

            return ['external_id' => (string) $job['id'], 'title' => $title, 'company_name' => trim($job['company']), 'description' => $description, 'location' => trim($job['location'] ?? '') ?: 'Remote', 'work_type' => 'remote', 'job_level' => str_contains($text, 'senior') ? 'senior' : (str_contains($text, 'junior') ? 'junior' : 'mid'), 'salary_min' => ((int) ($job['salary_min'] ?? 0)) ?: null, 'salary_max' => ((int) ($job['salary_max'] ?? 0)) ?: null, 'experience_min' => null, 'experience_max' => null, 'url' => $job['apply_url'] ?? $job['url'], 'published_at' => $job['date'] ?? null];
        })->values()->all();
    }
}
