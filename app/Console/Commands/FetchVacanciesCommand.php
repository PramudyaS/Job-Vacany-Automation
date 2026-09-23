<?php

namespace App\Console\Commands;

use App\Jobs\{CalculateJobMatches, FetchJobVacancies};
use Illuminate\Console\Command;

class FetchVacanciesCommand extends Command
{
    protected $signature = 'jobs:fetch';
    protected $description = 'Fetch and normalize live vacancies from configured sources';

    public function handle(): int
    {
        FetchJobVacancies::dispatchSync();
        CalculateJobMatches::dispatchSync();
        $this->info('Live vacancies fetched successfully.');
        return self::SUCCESS;
    }
}
