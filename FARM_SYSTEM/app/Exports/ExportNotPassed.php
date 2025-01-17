<?php

namespace App\Exports;

use App\Models\CoursesFile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\UserLogin;
use App\Models\FolderName;

class ExportNotPassed implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $folderNameId;

    public function __construct($folderNameId)
    {
        $this->folderNameId = $folderNameId;
    }

    public function collection()
    {
        $submittedUserIds = CoursesFile::where('folder_name_id', $this->folderNameId)
            ->whereNotNull('user_login_id')
            ->pluck('user_login_id');
    
        return UserLogin::whereNotIn('user_login_id', $submittedUserIds)->get();
    }

    public function headings(): array
    {
        return [
            'Faculty Name',
            'Main Requirements',
        ];
    }

    public function map($userLogin): array
    {
        $facultyInfo = $this->getFacultyInfo();
        $faculty = $facultyInfo['faculty'];
    
        $coursesFile = CoursesFile::where('folder_name_id', $this->folderNameId)
            ->where('user_login_id', $userLogin->user_login_id)
            ->first();

        return [
            $faculty['first_name'] . ' ' . 
            $faculty['middle_name'] . ' ' . 
            $faculty['last_name'],
            FolderName::find($this->folderNameId)->folder_name ?? 'Unknown Folder',
           
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        return [];
    }
}
