<?php

namespace App\Console\Commands;

use App\Model\Upload;
use Illuminate\Console\Command;

class UpdateS3FilePathInUploadTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'S3FillePath:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update file path in upload table if file path is null';

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
        $uploadRecords = Upload::where('file_path',null)->get();
        if($uploadRecords){
            foreach($uploadRecords as $val){
                $val->file_path = "https://canonizer-public-file.s3.us-east-2.amazonaws.com/".$val->file_name;
                $val->update();
            }
        }
    }
}
