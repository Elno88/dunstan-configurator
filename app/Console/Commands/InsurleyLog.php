<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;
use App\Exports\InsurleyLog as InsurleyLogExport;
use App\Mail\InsurleyLog as InsurleyLogMail;

use Excel;
use Mail;

class InsurleyLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insurley-log:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports insurley log';

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

        // log
        $logfile = 'laravel.log';
        $logviewer = new LaravelLogViewer();
        $logviewer->setFile($logfile);

        // excel data
        $excel_data_rows = [];

        // log file
        $log_filtered = $logviewer->all();

        // Number of days back that the export contains, if NULL then get all;
        $email_export_days = config('services.insurley.email_export.days');

        // Loop all log rows and filter out insurley data
        foreach($log_filtered as $logrow)
        {
            if(!empty($email_export_days) && is_numeric($email_export_days)) {
                if(Carbon::parse($logrow['date'])->lessThan(now()->startOfDay()->subDays($email_export_days))) {
                    break;
                }
            }

            $lookup = substr($logrow['text'], 0, 14);

            if($lookup == 'Insurley data.'){
                $data_json = str_replace('Insurley data. ', '', $logrow['text']);
                $data_rows = json_decode($data_json, true);

                foreach($data_rows as $row){
                    $row['log_entry'] = $logrow['date'];
                    $excel_data_rows[] = $row;
                }
            }
        }

        // Sort array based on civic_number
        usort($excel_data_rows, function($a, $b) {
            return $a['civic_number'] <=> $b['civic_number'];
        });

        // Excel filename
        $excel_file_path = 'excel/insurley_log_'.now()->format('YmdHis').'.xlsx';

        // Export logfile to storage folder
        Excel::store(new InsurleyLogExport($excel_data_rows), $excel_file_path);

        // Send email with the logfile if an email exists and email is enabled
        $email_export_enabled   = config('services.insurley.email_export.enabled');
        $email_addresses        = config('services.insurley.email_export.addresses');

        if(!empty($email_addresses) && $email_export_enabled){
            $email_addresses = explode(',', $email_addresses);

            foreach($email_addresses as $email_address) {
                $email_address = str_replace(' ', '', $email_address);

                if(!empty($email_address)) {
                    Mail::to($email_address)->send(new InsurleyLogMail(
                        'Konfiguratorn dunstan - insurley logfile',
                        $excel_file_path
                    ));
                }
            }
        }

        return 0;
    }
}
