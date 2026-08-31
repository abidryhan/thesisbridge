<?php

namespace App\Console\Commands;

use App\Models\Milestone;
use App\Notifications\MilestoneDeadlineApproaching;
use App\Notifications\MilestoneOverdueEscalation;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckMilestoneDeadlines extends Command
{
    protected $signature = 'milestones:check-deadlines';

    protected $description = 'Send reminders for milestones due in 3 days, and escalate to supervisors for milestones overdue today.';

    public function handle(): int
    {
        $this->sendReminders();
        $this->sendEscalations();

        return self::SUCCESS;
    }

    protected function sendReminders(): void
    {
        $targetDate = Carbon::today()->addDays(3);

        $milestones = Milestone::whereDate('deadline', $targetDate)
            ->whereNull('completed_at')
            ->with('thesisGroup.students.user')
            ->get();

        foreach ($milestones as $milestone) {
            foreach ($milestone->thesisGroup->students as $student) {
                $user = $student->user;

                $alreadySent = $user->notifications()
                    ->where('type', MilestoneDeadlineApproaching::class)
                    ->where('data->milestone_id', $milestone->id)
                    ->exists();

                if (!$alreadySent) {
                    $user->notify(new MilestoneDeadlineApproaching($milestone));
                }
            }
        }

        $this->info("Checked {$milestones->count()} milestone(s) for reminders.");
    }

    protected function sendEscalations(): void
    {
        $today = Carbon::today();

        $milestones = Milestone::whereDate('deadline', $today)
            ->whereNull('completed_at')
            ->with('thesisGroup.supervisor.user')
            ->get();

        foreach ($milestones as $milestone) {
            $supervisor = $milestone->thesisGroup->supervisor;

            if (!$supervisor || !$supervisor->user) {
                continue;
            }

            $user = $supervisor->user;

            $alreadySent = $user->notifications()
                ->where('type', MilestoneOverdueEscalation::class)
                ->where('data->milestone_id', $milestone->id)
                ->exists();

            if (!$alreadySent) {
                $user->notify(new MilestoneOverdueEscalation($milestone));
            }
        }

        $this->info("Checked {$milestones->count()} milestone(s) for escalations.");
    }
}