<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserLogin;
use App\Models\FolderName;
use App\Models\CoursesFile;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\LogoutLog;
use App\Models\LoginLog;
use App\Models\Department;
use Carbon\Carbon;
use App\Models\CourseSchedule;

class AccomplishmentController extends Controller
{
    //show the accomplishtment department
    public function showAccomplishmentPage()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    
        $user = auth()->user();
        $firstName = $user->first_name;
        $surname = $user->surname;
 
    
        $notifications = Notification::where('user_login_id', $user->user_login_id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $notificationCount = $notifications->where('is_read', 0)->count();
    
        $folders = FolderName::all();
    
        $departments = Department::all();
    
        return view('admin.accomplishment.accomplishment', [
            'folders' => $folders,
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
            'firstName' => $firstName,
            'surname' => $surname,
            'user' => $user, 
            'departments' => $departments,
        ]);
    }

    //show the faculty members per department
    public function showAccomplishmentDepartment($department, $folder_name_id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    
        $user = auth()->user();
        $firstName = $user->first_name;
        $surname = $user->surname;
    
        $notifications = Notification::where('user_login_id', $user->user_login_id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $notificationCount = $notifications->where('is_read', 0)->count();
    
        $folder = FolderName::findOrFail($folder_name_id);
        $folders = FolderName::all();
    
        $decodedDepartment = urldecode($department);
        
        // Check if the department exists
        $departmentRecord = Department::where('name', $decodedDepartment)->first();
        
        if (!$departmentRecord) {
            return redirect()->back()->withErrors(['Department not found']);
        }
    
        $facultyUsers = UserLogin::whereIn('role', ['faculty', 'faculty-coordinator'])
            ->where('department_id', $departmentRecord->department_id)
            ->get();
    
        return view('admin.accomplishment.view-accomplishment-faculty', [
            'folders' => $folders,
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
            'firstName' => $firstName,
            'surname' => $surname,
            'user' => $user,
            'user_login_id' => $user->user_login_id, 
            'department' => $departmentRecord,  
            'facultyUsers' => $facultyUsers,
            'folder_name_id' => $folder_name_id,
            'folder' => $folder,
            'folderName' => $folder->folder_name
        ]);
    }

    //show main requirements
    public function viewFacultyAccomplishments($user_login_id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    
        $user = auth()->user();
        $firstName = $user->first_name;
        $surname = $user->surname;
    
        $notifications = Notification::where('user_login_id', $user->user_login_id)
            ->orderBy('created_at', 'desc')
            ->get();
    
        $notificationCount = $notifications->where('is_read', 0)->count();
    
        $faculty = UserLogin::findOrFail($user_login_id);
    
        $folders = FolderName::select('main_folder_name')->distinct()->get(); 
    
        $department = Department::find($faculty->department_id);
        $departmentName = $department ? $department->name : '';
    
        $facultyUsers = UserLogin::whereIn('role', ['faculty', 'faculty-coordinator']) 
            ->where('department_id', $faculty->department_id) 
            ->get();
    
        return view('admin.accomplishment.main-folder', [
            'faculty' => $faculty,
            'folders' => $folders,
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
            'firstName' => $firstName,
            'surname' => $surname,
            'department' => $departmentName,
        ]);
    }
}
