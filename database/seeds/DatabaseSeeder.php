<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$seeder = ["NameSpaceSeeder","VideopodcastSeeder"];
    	foreach($seeder as $seed){
         $this->call($seed);
    	}
    }
}
