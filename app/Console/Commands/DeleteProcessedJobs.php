<?php

namespace App\Console\Commands;

use App\Model\ProcessedJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteProcessedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processJob:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove each topic duplicate entries except latest ones in processed jobs table';

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
        $items = DB::table('processed_jobs')->select('topic_num')->distinct()->get();
        $notDelId = [];
        foreach ($items as $val) {
            $delId =  ProcessedJob::where('topic_num', $val->topic_num)
                ->where('status','=','Failed')
                ->latest('created_at')->first();
                $notDelId[] = $delId->id;
        }

        ProcessedJob::whereNotIn('id', $notDelId)->delete();


    }
}
