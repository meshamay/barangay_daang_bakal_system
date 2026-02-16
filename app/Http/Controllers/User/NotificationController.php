<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUnread()
    {
        $notifications = Auth::user()->unreadNotifications;
        
        $formatted = $notifications->map(function ($n) {
            return [
                'id' => $n->id,
                'message' => $n->data['message'],
                'link' => $n->data['link'] ?? '#',
                'created_at' => $n->created_at->diffForHumans(), 
            ];
        });

        return response()->json($formatted);
    }

    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}