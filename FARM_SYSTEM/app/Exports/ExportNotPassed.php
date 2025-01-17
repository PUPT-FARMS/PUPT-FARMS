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
}
