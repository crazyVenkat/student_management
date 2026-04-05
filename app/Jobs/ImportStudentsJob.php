<?php

namespace App\Jobs;

use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImportStudentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        try {
            $fullPath = storage_path('app/public/' . $this->filePath);

            if (!file_exists($fullPath)) {
                Log::error('File not found: ' . $fullPath);
                return;
            }

            $import = new \App\Imports\StudentsImport();

            \Maatwebsite\Excel\Facades\Excel::import($import, $fullPath);

            // Log failures
            if ($import->failures()->isNotEmpty()) {
                foreach ($import->failures() as $failure) {
                    Log::error('Import Error Row ' . $failure->row(), $failure->errors());
                }
            }


            Log::info('Student import completed successfully');

        } catch (\Exception $e) {
            Log::error('Import Job Failed: ' . $e->getMessage());
        }
    }
}
