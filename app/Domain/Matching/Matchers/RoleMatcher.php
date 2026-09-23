<?php

namespace App\Domain\Matching\Matchers;

class RoleMatcher
{
    public function score(array $candidateRoles, string $jobTitle): float
    {
        $normalize = fn ($v) => config('job_matching.role_aliases')[mb_strtolower(trim($v))] ?? mb_strtolower(trim($v));
        $title = $normalize($jobTitle);
        foreach ($candidateRoles as $role) {
            $role = $normalize($role);
            if ($role === $title) return 100;
            if (str_contains($title, $role) || str_contains($role, $title)) return 85;
            $tokens = array_filter(explode(' ', $role), fn ($t) => strlen($t) > 2);
            if ($tokens && count(array_filter($tokens, fn ($t) => str_contains($title, $t))) >= max(1, ceil(count($tokens) / 2))) return 65;
        }
        return 0;
    }
}
