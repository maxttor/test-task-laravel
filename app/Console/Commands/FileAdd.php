<?php

namespace App\Console\Commands;

use App\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class FileAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:add {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add file';

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
        $validation = Validator::make($this->arguments(), ['url' => 'required|url']);
        if ($validation->fails()) {
            $this->error($validation->errors[0]);
        }

        try {
            File::create($this->arguments());
        } catch (\Throwable $error) {
            $this->error(trans('app.save_error'));
        }
        $this->info('Success');
    }
}
