<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Templates;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminEmailCampaign;

class UserController extends Controller {

    public function getIndex(Request $request) {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function getEdit(Request $request, $id) {
        $user = User::find($id);
        if (!$user) {
            return redirect('/admin/users');
        }

        return view('admin.user-edit', compact('user'));
    }

    protected function validator(array $data)
    {
        $message = [];
        return Validator::make($data, [
            'first_name' => 'required|regex:/^[a-zA-Z ]*$/|string|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z ]*$/|string|max:100',
            'middle_name' => 'nullable|regex:/^[a-zA-Z ]*$/|max:100',
            'address_1' =>'max:500',
            'address_2'=>'max:500',
            'city'=>'max:50',
            'state'=>'max:50',
            'postal_code'=>'max:10',
            'country'=>'max:20'
           ],$message);
    }

    public function postUpdate(Request $request, $id) {
        $data = $request->only(['first_name', 'middle_name', 'last_name', 'address_1', 'address_2', 'city', 'state', 'postal_code', 'country']);
        $validator = $this->validator($data);
        if($validator->fails()){
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        User::where('id', $id)->update($data);
        return redirect('/admin/users');
    }

    public function getSendMail() {
        $users = User::all();
        $templates = Templates::where('status', 1)->get();
        return view('admin.user-sendmail', compact('users', 'templates'));
    }

    public function postSendMail(Request $request) {
        $data = $request->all();
        $rules = [
            'template' => 'required',
            'send_to' => 'required',
        ];
        
        if(isset($data['send_to']) && $data['send_to'] == 'filter_users'){
            $rules += ['users' => 'required'];
        }
        
        $messages = [
            'template.required'=>'Please select email template',
            'users.required'=>'Please select users'
        ];
        
        
        $validator = Validator::make($data, $rules,$messages);
        if ($validator->fails()) {
             return redirect('/admin/sendmail')->withErrors($validator)->withInput();
        }

        if ($data['send_to'] == 'canonizer_1.0') {
            $users = User::all();
        } else {
            $userIDs = $data['users'];
            $users = User::whereIn('id', $userIDs)->get();
        }
        
        $template = Templates::find($data['template']);        
        foreach($users as  $user){
            Mail::to($user->email)->bcc('brent.allsop@canonizer.com')->send(new AdminEmailCampaign($user,$template));
        }
        $request->session()->flash('success', 'Mail sent successfully');
        return redirect('/admin/sendmail');
    }

}
