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
        $uploadRecords = Upload::where('file_path',null)
        ->orWhere('file_path','like',"%https://canonizer-user-file-529089637802.s3-accesspoint.us-east-2.amazonaws.com%")
        ->get();
        // echo $uploadRecords->count(); die;
        if($uploadRecords){
            foreach($uploadRecords as $val){
                // dd($val);
                $val->file_path = env('AWS_PUBLIC_URL')."/".$val->file_name;
                $val->save();
            }
        }
    }
}
