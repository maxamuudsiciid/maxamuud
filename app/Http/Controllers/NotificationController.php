<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = SystemNotification::where(function ($q) {
                $q->where('user_id', Auth::id())
                  ->orWhereNull('user_id');
            })
            ->orderBy('is_read')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(SystemNotification $notification)
    {
        $notification->update(['is_read' => true]);
        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        SystemNotification::where(function ($q) {
            $q->where('user_id', Auth::id())->orWhereNull('user_id');
        })->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function destroy(SystemNotification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Notification deleted.');
    }
}
