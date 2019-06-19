<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VideoPodcast;

class VideoController extends Controller
{
    public function index()
    {
        $video = VideoPodcast::first();
        return view('admin.videopodcast',compact('video'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if(isset($data['id'])){
        	$videopodcast = VideoPodcast::where('id','=',$data['id'])->first(); 
        	$videopodcast->html_content = html_entity_decode($data['html_content']);        	
	        $videopodcast->updated_at = date();
        }else{
        	$videopodcast = new VideoPodcast();
        	$videopodcast->html_content = html_entity_decode($data['html_content']);
	        $videopodcast->created_at = date();
	        $videopodcast->updated_at = date();
        }
        
        $videopodcast->save();
        return redirect('/admin/videopodcast');
        
    }
}
