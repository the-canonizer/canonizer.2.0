<?php

namespace App\Http\Controllers;

use Storage;
use App\User;
use App\Helpers\Aws;
use App\Facades\Util;
use App\Model\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    //

    public function getUpload(Request $request)
    {
        $uploaded = Upload::where('user_id', $request->user()->id)->orderBy('created_at', 'DESC')->get();

        return view('upload', compact('uploaded'));
    }

    public function postUpload(Request $request)
    {
        $validatorArray = [
            'file' => 'required|mimes:jpeg,bmp,png,jpg,gif|min:1|max:5120',
            'file_name' => 'required'
        ];
        $messages = array(
            'file_name.required' => 'File name is required.',
            'file.mimes' => 'The type of the uploaded file should be an image.(jpeg,jpg,png,bmp,gif)'
        );
        $validator = Validator::make($request->all(), $validatorArray, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            $all = $request->all();
            $file = $request->file('file');
            $user = $request->user();
            $six_digit_random_number = random_int(100000, 999999);
            $filename = User::ownerCode($user->id) . '_' . time() . '_' . $six_digit_random_number  . '.' . $file->getClientOriginalExtension();
            $file_name = $request->input('file_name');
            $submittedFileName = trim($file_name);
            $fileShortCode = Util::generateShortCode($file, $submittedFileName);
            $uniquename =  trim($file_name . '.' . $file->getClientOriginalExtension());
            $existingFile = Upload::where('file_name', $uniquename)->get();
            
            if (count($existingFile) > 0) {
                $request->session()->flash('error', 'There is already a file with name ' . $uniquename . ', Please use different name.');
                return redirect()->back();
            }
            if(strlen($uniquename) > 200){
                $request->session()->flash('error', 'File name may not be greater than 200 characters');
                return redirect()->back();	
            }
            /** Upload File to S3 */
            $result = Aws::UploadFile($filename, $file);
            $response = $result->toArray();

            $data = [
                'file_name' => $uniquename,
                'user_id' => $user->id,
                'short_code' => $fileShortCode,
                'file_id' => $fileShortCode,
                'file_type' => $file->getMimeType(),
                'folder_id' => (isset($all['folder_id']) && !empty($all['folder_id'])) ? $all['folder_id'] : null,
                'file_path' => $response['ObjectURL'], 
                'created_at' => time(),
                'updated_at' => time()
            ];
            Upload::insert($data);
            $request->session()->flash('success', 'File uploaded successfully!');
        } catch (\Exception $e) {
            $request->session()->flash('error', $e->getMessage());
        }

        return redirect()->back();
    }
}
