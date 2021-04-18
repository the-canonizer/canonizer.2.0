<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\SharesAlgorithm;
use App\Model\Nickname;
use Illuminate\Support\Facades\Validator;

class SharesAlgoController extends Controller {

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shares = SharesAlgorithm::paginate(10);
        return view('admin.shares.index',compact('shares'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $nickNames = Nickname::all();
        $nick_names = [];
        if(count($nickNames) > 0){
            foreach($nickNames as $n){
               $nick_names[$n->id] = $n->nick_name; 
            }
        }
        return view('admin.shares.create',compact('nick_names'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('nick_name_id','as_of_date','share_value');
         $validatorArray = [ 
          'nick_name_id' => 'required',
          'as_of_date' => 'required',
          'share_value' => 'required'
          ];
         // $message = [
         //    'name.required' => 'Template Name field is required.',
         //    'subject.required' => 'Template Subject field is required.',
         //    'body.required' => 'Template Body field is required.',
         // ];
         $validator = Validator::make($request->only(['nick_name_id','as_of_date','share_value']), $validatorArray);
         if ($validator->fails()) {  
            return back()->withErrors($validator->errors())->withInput($request->all());
        }

        // echo "<pre>"; print_r($data); die;
        $share = new SharesAlgorithm();
        $share->nick_name_id = $data['nick_name_id'];
        $share->as_of_date =  date('Y-'.$data['as_of_date'].'-01');
        $share->share_value = $data['share_value'];
        $share->save();
        return redirect('/admin/shares');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if($id){
            $share = SharesAlgorithm::find($id);
            $nickNames = Nickname::all();
            $nick_names = [];
            if(count($nickNames) > 0){
                foreach($nickNames as $n){
                   $nick_names[$n->id] = $n->nick_name; 
                }
            }
            return view('admin.shares.edit',compact('share','nick_names'));
            
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($id){
            $data = $request->only('nick_name_id','as_of_date','share_value');
             $share = SharesAlgorithm::find($id);
              $validatorArray = [ 
                  'nick_name_id' => 'required',
                  'as_of_date' => 'required',
                  'share_value' => 'required'
                  ];
             if($share->nick_name_id != $data['nick_name_id']){
                 $validatorArray['nick_name_id'] = 'required';
             }
             if($share->as_of_date != $data['as_of_date']){
                $validatorArray['as_of_date'] = 'required';
             }
             if($share->share_value != $data['share_value']){
                $validatorArray['share_value'] = 'required';
             }
             
             $validator = Validator::make($request->only(['nick_name_id','as_of_date','share_value']), $validatorArray);
             if ($validator->fails()) {  
                return back()->withErrors($validator->errors())->withInput($request->all());
            }
            $share = SharesAlgorithm::find($id);
            $share->nick_name_id = $data['nick_name_id'];
            $share->as_of_date = $data['as_of_date'];
            $share->share_value = $data['share_value'];
            $request->session()->flash('success', 'Share Data Updated Successfully');
            return redirect('/admin/shares');
            
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($id){
            SharesAlgorithm::find($id)->delete();
            $request->session()->flash('success', 'Share Data Deleted Successfully');
            return redirect('/admin/shares');
        }
    }


}
