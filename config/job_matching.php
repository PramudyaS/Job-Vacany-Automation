<?php

return [
    'sources' => [
        'remote_ok' => ['url' => env('REMOTE_OK_FEED_URL', 'https://remoteok.com/api')],
        'arbeitnow' => ['url' => env('ARBEITNOW_FEED_URL', 'https://www.arbeitnow.com/api/job-board-api')],
        'remotive' => ['url' => env('REMOTIVE_FEED_URL', 'https://remotive.com/api/remote-jobs')],
    ],
    'weights' => [
        'skill' => 0.40, 'role' => 0.25, 'experience' => 0.15,
        'work_type' => 0.10, 'location' => 0.05, 'salary' => 0.05,
    ],
    'skill_aliases' => [
        'php' => 'PHP', 'laravel' => 'Laravel', 'mysql' => 'MySQL',
        'postgres' => 'PostgreSQL', 'postgresql' => 'PostgreSQL', 'redis' => 'Redis',
        'docker' => 'Docker', 'k8s' => 'Kubernetes', 'kubernetes' => 'Kubernetes',
        'js' => 'JavaScript', 'javascript' => 'JavaScript', 'ts' => 'TypeScript',
        'typescript' => 'TypeScript', 'aws' => 'AWS', 'git' => 'Git',
    ],
    'role_aliases' => [
        'backend developer' => 'backend engineer',
        'php backend developer' => 'backend engineer',
        'laravel developer' => 'backend engineer',
        'software developer' => 'software engineer',
    ],
];
