<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WeeklySupervisorDigest extends Notification
{
    use Queueable;

    public function __construct(protected array $digestData)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'weekly_supervisor_digest',
            'active_group_count' => $this->digestData['active_group_count'],
            'upcoming_milestones' => $this->digestData['upcoming_milestones'],
            'overdue_milestones' => $this->digestData['overdue_milestones'],
            'ghost_groups' => $this->digestData['ghost_groups'],
            'is_all_clear' => $this->digestData['is_all_clear'],
            'message' => $this->digestData['is_all_clear']
                ? "Weekly digest: all {$this->digestData['active_group_count']} active group(s) look healthy this week."
                : 'Weekly digest: ' . count($this->digestData['upcoming_milestones']) . ' upcoming, '
                    . count($this->digestData['overdue_milestones']) . ' overdue, '
                    . count($this->digestData['ghost_groups']) . ' ghost-flagged group(s).',
        ];
    }
}