<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Hash;
class AdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:new';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New Admin user';

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

        $firstname = $this->ask('Enter your first name?');
        $lastname = $this->ask('Enter your last name?');
        $email = $this->ask('Enter your email?');
        $passsword = $this->ask('Enter your password?');
         $user = User::where('email', $email)->first();
        if($user){
            $this->error("User Already exists");
        }else{
            User::create(['first_name'=>$firstname,'last_name'=>$lastname,'email'=>$email,'password'=>Hash::make($passsword)]);
            $this->info("User Created");
        }
    }
}
