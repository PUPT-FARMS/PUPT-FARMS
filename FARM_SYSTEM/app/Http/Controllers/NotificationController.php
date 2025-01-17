<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserLogin;
use App\Models\FolderName;
use App\Models\CoursesFile;
use App\Models\UserDetails;
use App\Models\Notification;
use Illuminate\Support\Facades\Log; 

class NotificationController extends Controller
{
    //get notification count 
    public function getNotificationCount()
    {
        $count = Notification::where('user_login_id', Auth::id())
                            ->where('is_read', false)
                            ->count();
        return response()->json(['count' => $count]);
    }

    public function markNotificationsAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
    
        if ($notificationId) {
            // Mark specific notification as read
            Notification::where('id', $notificationId)
                        ->where('user_login_id', Auth::id())
                        ->update(['is_read' => true]);
        } else {
            // Mark all notifications as read
            Notification::where('user_login_id', Auth::id())
                        ->where('is_read', false)
                        ->update(['is_read' => true]);
        }
    
        return response()->json(['status' => 'success']);
    }
}
