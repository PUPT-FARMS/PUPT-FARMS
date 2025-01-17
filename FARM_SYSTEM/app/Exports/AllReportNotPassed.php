<?php

namespace App\Exports;

use App\Models\CoursesFile;
use App\Models\FolderName;
use App\Models\UserLogin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AllReportNotPassed implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $facultyInfo;

    public function __construct()
    {
        $this->facultyInfo = $this->getFacultyInfo();
    }

    public function collection()
    {
        $folderIds = FolderName::pluck('folder_name_id');
        \Log::info('Folder IDs:', $folderIds->toArray());

        $submittedFolderIds = CoursesFile::whereNotNull('user_login_id')
            ->pluck('folder_name_id')
            ->unique();
        \Log::info('Submitted Folder IDs:', $submittedFolderIds->toArray());

        $notPassedFolderIds = $folderIds->diff($submittedFolderIds);
        \Log::info('Not Passed Folder IDs:', $notPassedFolderIds->toArray());

        $facultyMembers = UserLogin::where('role', 'faculty')->get();
        \Log::info('Faculty Members:', $facultyMembers->toArray());

        $notPassedFaculty = collect();

        foreach ($facultyMembers as $faculty) {
            $notPassedFolders = FolderName::whereIn('folder_name_id', $notPassedFolderIds)->get();
            foreach ($notPassedFolders as $folder) {
                $notPassedFaculty->push([
                    'full_name' => $this->facultyInfo['faculty']['first_name'] . ' ' . 
                                   $this->facultyInfo['faculty']['middle_name'] . ' ' . 
                                   $this->facultyInfo['faculty']['last_name'],
                                   'main_folder_name' => $folder->main_folder_name,  
                    'not_passed_folder' => $folder->folder_name,
            
                    'semester' => $this->facultyInfo['faculty']['subjects'][0]['semester']['semester'] 
                ]);
            }
        }

        \Log::info('Not Passed Faculty:', $notPassedFaculty->toArray());

        return $notPassedFaculty;
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Main Requirements',
            'Folder Name',
            'Semester',
        ];
    }

}
