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

    public function collection()
    {
        $facultyMembers = $this->getAllFaculty();
        
        Log::info('Number of faculty members: ' . $facultyMembers->count());
        
        $data = collect();
        
        foreach ($facultyMembers as $index => $faculty) {
            Log::info('Processing faculty member: ' . $faculty->first_name . ' ' . $faculty->surname);
            
            $rowData = [
                'no' => $index + 1,
                'date_submitted' => $this->getLatestSubmissionDate($faculty->user_login_id),
                'faculty_name' => $faculty->first_name . ' ' . $faculty->surname,
            ];
            
            foreach ($this->mainFolders as $mainFolder) {
                foreach ($this->subFolders[$mainFolder] as $folderNameId => $folderName) {
                    $fileCount = $this->getFileCount($faculty->user_login_id, $folderNameId);
                    $rowData[$folderName] = $fileCount;
                    Log::info("Faculty: {$faculty->surname}, Folder: {$folderName}, Count: {$fileCount}");
                }
            }
            
            $rowData['semester'] = $this->semester;
            
            $data->push($rowData);
            Log::info('Added row data for faculty: ' . $faculty->surname);
        }
        
        Log::info('Number of rows in final data collection: ' . $data->count());
        Log::info('Data collection: ' . json_encode($data));
        
        return $data;
    }

    public function headings(): array
    {
        $headers = ['No.', 'Date Submitted', 'Faculty Name'];

        foreach ($this->mainFolders as $mainFolder) {
            foreach ($this->subFolders[$mainFolder] as $folderName) {
                $headers[] = $folderName;
            }
        }

        $headers[] = 'Semester';

        return $headers;
    }

    private function getFileCount($facultyId, $folderNameId)
    {
        $count = CoursesFile::where('folder_name_id', $folderNameId)
            ->where('user_login_id', $facultyId)
            ->where('semester', $this->semester)
            ->count();
        
        Log::info("File count for faculty {$facultyId}, folder {$folderNameId}: {$count}");
        
        return $count;
    }

    public function map($row): array
    {
        Log::info('Mapping row: ' . json_encode($row));

        $mappedRow = [
            $row['no'],
            $row['date_submitted'],
            $row['faculty_name']
        ];

        foreach ($this->mainFolders as $mainFolder) {
            foreach ($this->subFolders[$mainFolder] as $folderNameId => $subFolderName) {
                $value = $row[$subFolderName];
                $mappedRow[] = $value > 0 ? $value : 'X';
                Log::info("Mapping folder: {$subFolderName}, Value: {$value}");
            }
        }

        $mappedRow[] = $row['semester'];

        Log::info('Mapped row: ' . json_encode($mappedRow));

        return $mappedRow;
    }
}