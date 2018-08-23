<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Upload;
use Storage;
use Validator;

class UploadController extends Controller
{
    //

    public function getUpload(Request $request){
        $uploaded = Upload::where('user_id',$request->user()->id)->get();
        
        return view('upload',compact('uploaded'));
    }

    public function postUpload(Request $request){
       
       
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,bmp,png,jpg,gif|max:5120',
            //'file_name' => 'required',
        ],
		['file.max'=>'The file may not be greater than 5 MB.']
		);

        if($validator->fails()) {
             //session(['error'=> "Select a image file and fill file name"]);
			 //$request->session()->flash('error', 'Please select a image file.');
             return redirect()->back()->withErrors($validator);
        }

        $file = $request->file('file'); 
		
		
		if($request->input('file_name')=="") {
			
		  $uniquename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);	
		} else {
			
		  $uniquename = trim($request->input('file_name'));	
		}
	
		 $existingFile = Upload::where('file_id',$uniquename)->get();
		 
		 if(count($existingFile) > 0 ) {
			
             $request->session()->flash('error', 'There is already a file with name '.$uniquename.', Please use different name.');
             return redirect()->back();			
			 
		 } 

         if(strlen($uniquename) > 200){
             $request->session()->flash('error', 'File name may not be greater than 200 characters');
             return redirect()->back();	
         }
		 if($file->getClientOriginalExtension()=="") {
			 $request->session()->flash('error', 'Please select a image file.');
             return redirect()->back();	
		 }
		
        if($file){
         $fullname = $uniquename . '.' . $file->getClientOriginalExtension();
		 
		 $dir    = public_path().'/files';
         $files1 = scandir($dir);
		 
		 if(in_array($fullname,$files1)) {
			$request->session()->flash('error', 'There is already a file with name '.$fullname.' Please use different name.');
            return redirect()->back();	 
			 
		 }
		 
		 
         try{
            $path = $file->storeAs('files',$fullname,'public_files');
			
            $upload = new Upload;
            $upload->file_id = $uniquename;
            $upload->file_name = $fullname;
            $upload->file_type = $file->getMimeType();
            $upload->user_id = $request->user()->id;
            $upload->save();
           $request->session()->flash('success', 'File uploaded successfully!');
        }catch(\Exception $e){
            $request->session()->flash('error', $e->getMessage());
        }    }else{
            $request->session()->flash('error', "Select a file to upload");
        }

        return redirect()->back();
    }
}
