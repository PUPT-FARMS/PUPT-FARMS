<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\UserLogin;
use App\Models\CourseSchedule;
use App\Models\FolderName;
use App\Models\CoursesFile;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;



class CoursesFileController extends Controller
{
    //store files
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|array',
            'files.*.*' => 'file|mimes:pdf,jpeg,jpg,png,gif', 
            'folder_name_id' => 'required|exists:folder_name,folder_name_id',
            'semester' => 'required|string',
            'school_year' => 'required|string',
        ]);
    
        try {
            $userLoginId = auth()->user()->user_login_id;
            $folder_name_id = $request->input('folder_name_id');
            $semester = $request->input('semester');
            $schoolYear = $request->input('school_year'); 
    
            foreach ($request->file('files') as $courseScheduleId => $courseFiles) {
                $courseSchedule = CourseSchedule::find($courseScheduleId);
                if (!$courseSchedule) {
                    continue;
                }
    
                foreach ($courseFiles as $file) {
                    $path = $file->store('courses_files', 'public');
                    $fileSize = $file->getSize();
    
                    CoursesFile::create([
                        'files' => $path,
                        'original_file_name' => $file->getClientOriginalName(),
                        'user_login_id' => $userLoginId,
                        'folder_name_id' => $folder_name_id,
                        'course_schedule_id' => $courseSchedule->course_schedule_id,
                        'semester' => $semester, 
                        'school_year' => $schoolYear, 
                        'subject' => $courseSchedule->course_subjects,
                        'file_size' => $fileSize,
                    ]);
                }
            }
    
            return redirect()->back()->with('success', 'Files uploaded successfully!');
        } catch (\Exception $e) {
            logger()->error('File upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'File upload failed. Please try again.');
        }
    }

    
    
    //update file
   public function updateFile(Request $request, $id)
{
    $request->validate([
        'files.*' => 'file|mimes:pdf,jpeg,jpg,png,gif'
    ]);

    try {
        // Get the existing record to copy its details
        $existingFile = CoursesFile::findOrFail($id);
        $userLoginId = auth()->user()->user_login_id;
        
        // Handle new file uploads
        if ($request->hasFile('files')) {
            $newFiles = $request->file('files');
            $filePaths = [];
            $fileNames = [];
            $totalSize = 0;

            foreach ($newFiles as $uploadedFile) {
                $path = $uploadedFile->store('courses_files', 'public');
                $filePaths[] = $path;
                $fileNames[] = $uploadedFile->getClientOriginalName();
                $totalSize += Storage::disk('public')->size($path);
            }

            // Create a new record instead of updating
            $newFileRecord = CoursesFile::create([
                'files' => implode(',', $filePaths),
                'original_file_name' => implode(',', $fileNames),
                'user_login_id' => $userLoginId,
                'folder_name_id' => $existingFile->folder_name_id,
                'course_schedule_id' => $existingFile->course_schedule_id,
                'folder_input_id' => $existingFile->folder_input_id,
                'semester' => $existingFile->semester,
                'school_year' => $existingFile->school_year,
                'subject' => $existingFile->subject,
                'status' => 'To Review',
                'file_size' => $totalSize,
                'is_archived' => false
            ]);

            // Create notification for admins
            $folderName = FolderName::find($existingFile->folder_name_id)->folder_name;
            $currentUser = UserLogin::findOrFail($userLoginId);
            $senderName = $currentUser->first_name . ' ' . $currentUser->surname;

            $adminUsers = UserLogin::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                Notification::create([
                    'courses_files_id' => $newFileRecord->courses_files_id,
                    'user_login_id' => $admin->user_login_id,
                    'folder_name_id' => $existingFile->folder_name_id,
                    'sender' => $senderName,
                    'sender_user_login_id' => $userLoginId,
                    'notification_message' => "has added new files for the course {$existingFile->subject} in {$folderName}.",
                    'is_read' => false,
                ]);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'No files were uploaded.']);

    } catch (\Exception $e) {
        logger()->error('File creation failed: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'File upload failed. Please try again.'], 500);
    }
}
    
   public function addNewFile(Request $request)
    {
        $request->validate([
             'files.*' => 'file|mimes:pdf,jpeg,jpg,png,gif',
            'reference_file_id' => 'required' 
        ]);
    
        try {
            $referenceFile = CoursesFile::findOrFail($request->reference_file_id);
            $userLoginId = auth()->user()->user_login_id;
    
            if ($request->hasFile('files')) {
                $newFiles = $request->file('files');
                $filePaths = [];
                $fileNames = [];
                $totalSize = 0;
    
                foreach ($newFiles as $uploadedFile) {
                    $path = $uploadedFile->store('courses_files', 'public');
                    $filePaths[] = $path;
                    $fileNames[] = $uploadedFile->getClientOriginalName();
                    $totalSize += Storage::disk('public')->size($path);
                }
    
                $newFile = new CoursesFile();
                $newFile->files = implode(',', $filePaths);
                $newFile->original_file_name = implode(',', $fileNames);
                $newFile->user_login_id = $userLoginId;
                $newFile->folder_name_id = $referenceFile->folder_name_id;
                $newFile->course_schedule_id = $referenceFile->course_schedule_id;
                $newFile->folder_input_id = $referenceFile->folder_input_id;
                $newFile->semester = $referenceFile->semester;
                $newFile->school_year = $referenceFile->school_year;
                $newFile->subject = $referenceFile->subject;
                $newFile->status = 'To Review';
                $newFile->file_size = $totalSize;
                $newFile->is_archived = false;
                $newFile->save();
    
                $folderName = FolderName::find($referenceFile->folder_name_id)->folder_name;
                $currentUser = UserLogin::findOrFail($userLoginId);
                $senderName = $currentUser->first_name . ' ' . $currentUser->surname;
    
                $adminUsers = UserLogin::where('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    Notification::create([
                        'courses_files_id' => $newFile->courses_files_id,
                        'user_login_id' => $admin->user_login_id,
                        'folder_name_id' => $referenceFile->folder_name_id,
                        'sender' => $senderName,
                        'sender_user_login_id' => $userLoginId,
                        'notification_message' => "has submitted additional files for the course {$referenceFile->subject} in {$folderName}.",
                        'is_read' => false,
                    ]);
                }
    
                return response()->json(['success' => true]);
            }
    
            return response()->json(['success' => false, 'message' => 'No files were uploaded.']);
        } catch (\Exception $e) {
            logger()->error('File creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'File upload failed. Please try again.'], 500);
        }
    }

    //show view archive page
   public function showArchive()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $userId = auth()->id();
        $user = auth()->user();
        

        if (!in_array($user->role, ['faculty', 'faculty-coordinator'])) {
            return redirect()->route('login');
        }
    
        $firstName = $user->first_name;
        $surname = $user->surname;
    
        $folder = FolderName::first();
    
        if (!$folder) {
            return redirect()->back()->with('error', 'Folder not found.');
        }
    
        $folders = FolderName::all();
        $folderInputs = CoursesFile::where('folder_name_id', $folder->folder_name_id)->get();
    
       $notifications = \App\Models\Notification::where('user_login_id', auth()->id())
                ->orderBy('created_at', 'desc') 
                ->get();
        $notificationCount = $notifications->count();
    
        $uploadedFiles = CoursesFile::where('user_login_id', $user->user_login_id)
            ->where('is_archived', 1) 
            ->with(['userLogin', 'folderName', 'folderInput', 'courseSchedule'])
            ->get();
    
        return view('faculty.view-archive', [
            'uploadedFiles' => $uploadedFiles,
            'folder' => $folder,
            'folderName' => $folder->folder_name,
            'notifications' => $notifications,
            'notificationCount' => $notificationCount,
            'folderInputs' => $folderInputs,
            'firstName' => $firstName,
            'surname' => $surname,
            'folders' => $folders,
        ]);
    }
    
    //archive file
    public function archive($id)
    {
        $file = CoursesFile::find($id);

        if ($file) {
            $file->is_archived = true;
            $file->save();

            return redirect()->back()->with('success', 'File archived successfully!');
        }

        return redirect()->back()->with('error', 'File not found.');
    }

    public function archiveByDateRange(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
    
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();
    
        try {
            DB::beginTransaction();
    
            $files = CoursesFile::whereBetween('created_at', [$fromDate, $toDate])
                ->where('status', 'Approved')
                ->where('is_archived', false)  
                ->get();
    
            if ($files->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'No approved files found within the specified date range.');
            }
    
            $count = 0;
            foreach ($files as $file) {
                $file->is_archived = 1;  
                $file->save();
                $count++;
            }
    
            DB::commit();
    
            return redirect()->back()
                ->with('success', $count . ' files have been archived successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Archive error: ' . $e->getMessage());  
            return redirect()->back()
                ->with('error', 'An error occurred while archiving the files.');
        }
    }
    

    public function unarchive($courses_files_id)
    {
        $file = CoursesFile::find($courses_files_id);

        if (!$file) {
            return redirect()->back()->with('error', 'File not found.');
        }

        if ($file->user_login_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $file->is_archived = false;
        $file->save();

        return redirect()->back()->with('success', 'File has been restored');
    }

    //archive all
    public function archiveAll(Request $request)
    {
        $fileIds = json_decode($request->input('file_ids', '[]'), true);
        \Log::info('Received file IDs:', $fileIds);
    
        if (!empty($fileIds)) {
            $query = CoursesFile::whereIn('courses_files_id', $fileIds)
                ->where('status', 'Approved');
            
            \Log::info('SQL query:', [$query->toSql()]);
            \Log::info('SQL bindings:', $query->getBindings());
    
            $updatedCount = $query->update(['is_archived' => true]);
    
            \Log::info('Updated count:', [$updatedCount]);
    
            if ($updatedCount > 0) {
                return redirect()->back()->with('success', "$updatedCount files have been archived.");
            } else {
                return redirect()->back()->with('error', 'No eligible files were found to archive.');
            }
        }
    
        return redirect()->back()->with('error', 'No files selected.');
    }

    //restore achive
    public function bulkUnarchive(Request $request)
    {
        $fileIds = $request->input('file_ids', []);
        
        if (!empty($fileIds)) {
            CoursesFile::whereIn('courses_files_id', $fileIds)->update(['is_archived' => false]);
            return redirect()->back()->with('success', count($fileIds) . ' files have been restored.');
        }

        return redirect()->back()->with('error', 'No files selected for restoration.');
    }
    
    public function destroyFacultyFile($fileId)
    {
        // Get the fileName from the request body
        $fileName = request()->input('fileName');
        // Decode the URL-encoded file name
        $decodedFileName = urldecode($fileName);
    
        // Find the file record by ID
        $file = CoursesFile::find($fileId);
    
        // Check if the file exists
        if ($file) {
            // Get all files in the 'files' column (assuming it's a comma-separated list)
            $files = explode(',', $file->files);
    
            // Check if the decoded fileName is in the files array
            if (($key = array_search($decodedFileName, $files)) !== false) {
                // Remove the file from the array
                unset($files[$key]);
    
                // Update the 'files' column in the database
                $file->files = implode(',', $files);
                $file->save();
    
                // Delete the file from storage
                if (Storage::delete($decodedFileName)) {
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['success' => false, 'message' => 'File deletion from storage failed']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'File not found in the list']);
            }
        }
    
        return response()->json(['success' => false, 'message' => 'File record not found']);
    }






}
