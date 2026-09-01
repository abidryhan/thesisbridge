<?php

namespace App\Notifications;

use App\Models\Milestone;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MilestoneOverdueEscalation extends Notification
{
    use Queueable;

    public function __construct(protected Milestone $milestone)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'milestone_overdue_escalation',
            'milestone_id' => $this->milestone->id,
            'milestone_title' => $this->milestone->title,
            'thesis_group_id' => $this->milestone->thesis_group_id,
            'deadline' => $this->milestone->deadline->toDateString(),
            'message' => "Milestone \"{$this->milestone->title}\" was due today ({$this->milestone->deadline->format('M d, Y')}) and is still not marked complete.",
        ];
    }
}