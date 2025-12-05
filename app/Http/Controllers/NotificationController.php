<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for managing user notifications.
 * Handles viewing, marking as read, and clearing notifications.
 */
class NotificationController extends Controller
{
    /**
     * Display all notifications for the current user.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notification count (for AJAX polling).
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirect based on notification type
        $redirectUrl = $this->getRedirectUrl($notification);
        
        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Delete a notification.
     */
    public function destroy(string $id)
    {
        Auth::user()->notifications()->findOrFail($id)->delete();

        return back()->with('success', 'Notification deleted.');
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll()
    {
        Auth::user()->notifications()->delete();

        return back()->with('success', 'All notifications deleted.');
    }

    /**
     * Get the redirect URL based on notification type.
     */
    private function getRedirectUrl($notification): ?string
    {
        $data = $notification->data;
        $type = $data['type'] ?? null;

        return match ($type) {
            'task_status_change_requested' => isset($data['task_id']) ? "/tasks/{$data['task_id']}" : null,
            'task_status_request_reviewed' => isset($data['task_id']) ? "/tasks/{$data['task_id']}" : null,
            'task_assigned' => isset($data['task_id']) ? "/tasks/{$data['task_id']}" : null,
            'task_reminder' => isset($data['task_id']) ? "/tasks/{$data['task_id']}" : null,
            'team_invitation' => route('teams.index'),
            default => null,
        };
    }
}
