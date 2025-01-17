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
}
