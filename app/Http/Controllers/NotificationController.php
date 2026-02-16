<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Try admin guard first, then web guard
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'unread' => $user->unreadNotifications,
            'read' => $user->notifications()->whereNotNull('read_at')->take(10)->get(), // Get 10 recent read notifications
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    public function markAsRead($id)
    {
        // Try admin guard first, then web guard
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
}