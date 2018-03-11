<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;
use League\Csv\Statement;

class ImportPostalCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postalcode:import {file} {--D|delimiter= : quoated string for csv delimiter e.g ";"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import postalcode';

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
     * @return mixed
     */
    public function handle()
    {
        $file = $this->argument('file');
        $delimiter = $this->option('delimiter');

        if($this->get_file($file)) {
            $this->info('Importing postal codes from ' . $file . '.');

            $csv = Reader::createFromPath($this->get_file($file), 'r');

            if($delimiter) {
                $csv->setDelimiter($delimiter);
            } else {
                $csv->setDelimiter(',');
            }
            $csv->setHeaderOffset(0); //set the CSV header offset

            $stmt = (new Statement());

            $records = $stmt->process($csv);

            $bar = $this->output->createProgressBar(count($records));

            $default = [
              'postal_code' => '',
              'post_office' => '',
              'township' => '',
              'district' => '',
              'region' => '',
            ];

            $record_arr = [];
            foreach ($records as $row) {
                $record_arr[] = array_merge($default, $row);
                $bar->advance();
            }

            DB::table('postal_code')->insert($record_arr);

            $bar->finish();
            $this->info("\n");
        } else {
            $this->alert("File does not exist");
        }
    }

    private function get_file($file)
    {
        if(File::exists($file)) return $file;

        $storage_file = storage_path($file);
        if(File::exists($storage_file)) return $storage_file;

        $postalcode_file = storage_path('postalcode/'.$file);
        if(File::exists($storage_file)) return $postalcode_file;

        $base_path = base_path($file);
        if(File::exists($base_path)) return $base_path;

        return false;
    }
}
