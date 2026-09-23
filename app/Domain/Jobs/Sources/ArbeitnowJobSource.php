<?php

namespace App\Domain\Jobs\Sources;

use Illuminate\Support\Facades\Http;

final class ArbeitnowJobSource implements JobSourceInterface
{
    public function slug(): string
    {
        return 'arbeitnow';
    }

    public function name(): string
    {
        return 'Arbeitnow';
    }

    public function fetchJobs(): array
    {
        $response = Http::acceptJson()->withHeaders(['User-Agent' => 'JobVacancyMatcher/1.0'])
            ->timeout(20)->get(config('job_matching.sources.arbeitnow.url'));
        $response->throw();

        return collect($response->json('data', []))
            ->filter(fn ($job) => is_array($job) && isset($job['slug'], $job['title'], $job['company_name']))
            ->map(function (array $job) {
                $description = trim(strip_tags($job['description'] ?? ''));
                $text = mb_strtolower($job['title'].' '.$description.' '.implode(' ', $job['tags'] ?? []));

                return [
                    'external_id' => (string) $job['slug'],
                    'title' => trim($job['title']),
                    'company_name' => trim($job['company_name']),
                    'description' => $description,
                    'location' => trim($job['location'] ?? '') ?: ($job['remote'] ?? false ? 'Remote' : 'Unspecified'),
                    'work_type' => ($job['remote'] ?? false) ? 'remote' : 'onsite',
                    'job_level' => str_contains($text, 'senior') ? 'senior' : (str_contains($text, 'junior') ? 'junior' : 'mid'),
                    'salary_min' => null,
                    'salary_max' => null,
                    'experience_min' => null,
                    'experience_max' => null,
                    'url' => $job['url'] ?? 'https://www.arbeitnow.com/',
                    'published_at' => isset($job['created_at']) ? date(DATE_ATOM, (int) $job['created_at']) : null,
                ];
            })->values()->all();
    }
}
