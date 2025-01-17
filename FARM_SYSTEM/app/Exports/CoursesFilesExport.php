<?php

namespace App\Exports;

use App\Models\CoursesFile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CoursesFilesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $folderNameId;
    protected $faculty;

    public function __construct($folderNameId)
    {
        $this->folderNameId = $folderNameId;
        $this->faculty = $this->getFacultyInfo();
    }

    public function collection()
    {
        return CoursesFile::where('folder_name_id', $this->folderNameId)->get();
    }

    public function headings(): array
    {
        return [
            'Faculty Name',
            'File Name',
            'Main Requirements',
            'Semester',
            'Subject',
            'Status',
        ];
    }

    public function map($coursesFile): array
    {
        return [
            $this->faculty['faculty']['first_name'] . ' ' . 
            $this->faculty['faculty']['middle_name'] . ' ' . 
            $this->faculty['faculty']['last_name'],
            $coursesFile->original_file_name,
            $coursesFile->folderName->folder_name ?? 'Unknown Folder',
            $coursesFile->semester,
            $coursesFile->subject,
            $coursesFile->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        return [];
    }
}
