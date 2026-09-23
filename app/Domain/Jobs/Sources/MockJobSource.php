<?php

namespace App\Domain\Jobs\Sources;

class MockJobSource implements JobSourceInterface
{
    public function slug(): string
    {
        return 'mock';
    }

    public function name(): string
    {
        return 'Mock Job Source';
    }

    public function fetchJobs(): array
    {
        return [
            ['external_id' => 'mock-backend-001', 'title' => 'Backend Engineer', 'company_name' => 'Nusantara Labs',
                'description' => 'Build Laravel APIs with PHP, MySQL, Redis and Docker.', 'location' => 'Jakarta', 'work_type' => 'hybrid', 'job_level' => 'mid',
                'salary_min' => 18000000, 'salary_max' => 25000000, 'experience_min' => 3, 'experience_max' => 5, 'url' => 'https://example.test/jobs/mock-backend-001'],
            ['external_id' => 'mock-frontend-002', 'title' => 'Frontend Engineer', 'company_name' => 'Pixel Works',
                'description' => 'Create modern JavaScript and TypeScript user interfaces.', 'location' => 'Bandung', 'work_type' => 'onsite', 'job_level' => 'senior',
                'salary_min' => 12000000, 'salary_max' => 16000000, 'experience_min' => 5, 'experience_max' => 8, 'url' => 'https://example.test/jobs/mock-frontend-002'],
            ['external_id' => 'mock-php-003', 'title' => 'PHP Developer', 'company_name' => 'Remote First Co',
                'description' => 'Maintain PHP and Laravel services. Experience with PostgreSQL is a plus.', 'location' => 'Remote', 'work_type' => 'remote', 'job_level' => 'senior',
                'salary_min' => 16000000, 'salary_max' => 22000000, 'experience_min' => 4, 'experience_max' => 7, 'url' => 'https://example.test/jobs/mock-php-003'],
        ];
    }
}
