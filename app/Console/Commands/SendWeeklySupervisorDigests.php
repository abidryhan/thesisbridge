<?php

namespace App\Console\Commands;

use App\Models\Supervisor;
use App\Notifications\WeeklySupervisorDigest;
use Illuminate\Console\Command;

class SendWeeklySupervisorDigests extends Command
{
    protected $signature = 'digests:send-weekly';

    protected $description = 'Send every supervisor a weekly digest of their active thesis groups.';

    public function handle(): int
    {
        $supervisors = Supervisor::with('user')->get();

        foreach ($supervisors as $supervisor) {
            $supervisor->user->notify(new WeeklySupervisorDigest($supervisor->weeklyDigestData()));
        }

        $this->info("Sent weekly digests to {$supervisors->count()} supervisor(s).");

        return self::SUCCESS;
    }
}
