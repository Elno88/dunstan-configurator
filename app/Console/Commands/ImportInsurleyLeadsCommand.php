<?php

namespace App\Console\Commands;

use App\Imports\LeadsImport;
use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ImportInsurleyLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insurley:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Insurley leads from Excel files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = collect(Storage::files('import'));

        $files->filter(function ($file) {
            return Str::endsWith($file, '.xlsx');
        })->filter(function ($file) {
            return !File::where('file', $file)->exists();
        })->each(function ($file) {
            Excel::import($import = new LeadsImport, $file);

            File::create([
                'file' => $file,
                'items' => $import->getRowCount(),
            ]);
        });

        return 0;
    }
}
