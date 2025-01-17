<?php

namespace App\Exports;

use App\Models\CoursesFile;
use App\Models\FolderName;
use App\Models\UserLogin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateAllReports implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
   
    protected $mainFolders;
    protected $subFolders;
    protected $semester;

    public function __construct($semester)
    {
        $this->semester = $semester;
        
        $this->mainFolders = ['Test Administration', 'Classroom Management', 'Syllabus Preparation'];
        
        $allFolders = FolderName::orderBy('main_folder_name')
            ->orderBy('folder_name')
            ->get();
        
        $this->subFolders = [];
        foreach ($this->mainFolders as $mainFolder) {
            $this->subFolders[$mainFolder] = $allFolders
                ->where('main_folder_name', $mainFolder)
                ->pluck('folder_name', 'folder_name_id')
                ->toArray();
        }
    }
}