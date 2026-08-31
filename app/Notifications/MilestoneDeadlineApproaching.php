<?php

namespace App\Notifications;

use App\Models\Milestone;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MilestoneDeadlineApproaching extends Notification
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
            'type' => 'milestone_deadline_approaching',
            'milestone_id' => $this->milestone->id,
            'milestone_title' => $this->milestone->title,
            'thesis_group_id' => $this->milestone->thesis_group_id,
            'deadline' => $this->milestone->deadline->toDateString(),
            'message' => "Your milestone \"{$this->milestone->title}\" is due in 3 days ({$this->milestone->deadline->format('M d, Y')}).",
        ];
    }
}