<?php

namespace App\Console\Commands;

use App\Exports\LeadsExport;
use App\Mail\InsurleyLead;
use App\Models\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:export {--date= : Specific date for filtering export of leads}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends daily mail with leads';

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
        if ($this->isDisabled()) {
            return 0;
        }

        $filename = $this->getFilename();

        if (!$status = $this->makeExcelFile($filename)) {
            return 0;
        }

        if ($this->option('date')) {
            $this->setExported();
        }

        foreach ($this->getRecipients() as $recipient) {
            Mail::to($recipient)->send(
                new InsurleyLead($filename, $this->option('date'))
            );
        }

        return 0;
    }

    protected function getFilename()
    {
        return  sprintf('excel/insurley-leads-%s.xlsx', $this->option('date') ?? now()->toDateString());
    }

    protected function makeExcelFile(string $filename)
    {
        return (new LeadsExport)->forDate($this->option('date'))->store($filename);
    }

    protected function setExported()
    {
        Lead::notExported()->update([
            'exported_at' => now(),
        ]);
    }

    protected function isDisabled(): bool
    {
        return !config('services.insurley.email_export.enabled');
    }

    protected function getRecipients(): array
    {
        return explode(',', config('services.insurley.email_export.addresses'));
    }
}
