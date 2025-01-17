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
    }

}
