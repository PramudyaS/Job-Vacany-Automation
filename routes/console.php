<?php

use App\Jobs\CalculateJobMatches;
use App\Jobs\FetchJobVacancies;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new FetchJobVacancies)->everyThreeHours();
Schedule::job(new CalculateJobMatches)->everyThreeHours();
