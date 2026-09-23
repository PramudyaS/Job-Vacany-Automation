<?php

namespace App\Domain\Jobs\Models;

enum JobMatchStatus: string
{
    case NEW = 'new'; case SAVED = 'saved'; case INTERESTED = 'interested'; case APPLIED = 'applied'; case IGNORED = 'ignored';
}
