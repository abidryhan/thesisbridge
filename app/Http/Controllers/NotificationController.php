<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->latest()->get();

        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function markAsRead(string $notificationId): RedirectResponse
    {
        $notification = auth()->user()->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();

        $notification->markAsRead();

        return redirect()->route(
            'thesis-groups.show',
            $notification->data['thesis_group_id']
        );
    }
}