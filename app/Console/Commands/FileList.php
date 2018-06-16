<?php

namespace App\Console\Commands;

use App\File;
use Illuminate\Console\Command;

class FileList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'File list';

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
     * @return void
     */
    public function handle()
    {
        $files = File::all();
        $data = [];
        foreach ($files as $file) {
            $data[] = [
                'name' => $file->name,
                'status' => $file->status_title
            ];
        }
        $this->table(['Name', 'Status'], $data);
    }
}
