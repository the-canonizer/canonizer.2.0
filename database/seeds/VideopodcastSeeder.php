<?php

use Illuminate\Database\Seeder;

class VideopodcastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            [
            'html_content' => html_entity_decode('<h3>Video Podcast</h3>
<div style="background-color: #f0efef;padding: .75rem 1rem;font-size: 15px;margin-bottom: 2rem;float: left; width: 100%;">
<span style="color:#0000ff; font-size:15px">Episode 1 of 4</span>
<object height="275" width="480" data="https://www.youtube.com/embed/tgbNymZ7vqY""></object>
<div>'),
            'created_at' =>date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]
		);
		DB::table('videopodcast')->insert($data);
    }
}
