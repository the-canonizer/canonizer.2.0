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
        $uploaded = Upload::where('user_id',$request->user()->id)->orderBy('created_at','DESC')->get();
        
        return view('upload',compact('uploaded'));
    }

    public function postUpload(Request $request){
       
        // $validatorArray = [
        //     'file' => 'required|mimes:jpeg,bmp,png,jpg,gif|max:5120|min:1'
        // ];
         $validatorArray = [
            'file' => 'required|mimes:jpeg,bmp,png,jpg,gif|max:5120|min:1',
            'file_name' => 'required'
        ];
        $messages = array(
            'file_name.required' => 'File name is required.',
            'file.mimes' => 'The type of the uploaded file should be an image.(jpeg,jpg,png,bmp,gif)'
        );
        $validator = Validator::make($request->all(),$validatorArray, $messages);

        if($validator->fails()) {
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

         $path = $file->storeAs('files',$fullname,'public_files');
         //check here- file is save or not in folder
         if($path==""){
            // Everything for owner, read and execute for others
            if(chmod($dir, 0755)){
                $path = $file->storeAs('files',$fullname,'public_files');
            }
            else{
                $request->session()->flash('error', 'Unable to upload file. Please try again later.');
                return redirect()->back(); 
            } 
         }
		 
         try{
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
