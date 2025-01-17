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

}
