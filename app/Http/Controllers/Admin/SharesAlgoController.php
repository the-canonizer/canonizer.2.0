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

        return view('admin.shares.create');
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
         $todayDate = date('Y-m-d');
         $jandate = date('01/01/2021');
         $validatorArray = [ 
          'nick_name_id' => 'required',
          'as_of_date' => 'required|date|after_or_equal:'.$jandate.'|before_or_equal:'.$todayDate,
          'share_value' => 'required|numeric|min:1|max:100000'
          ];
        
         $validator = Validator::make($request->only(['nick_name_id','as_of_date','share_value']), $validatorArray);
         if ($validator->fails()) {  
            return back()->withErrors($validator->errors())->withInput($request->all());
        }

        //validate if nickname id exist
        $nickName = Nickname::find($data['nick_name_id']);
        if(isset($nickName) && isset($nickName->id)){
            $share = new SharesAlgorithm();
            $share->nick_name_id = $data['nick_name_id'];
            $share->as_of_date =  date('Y-m-d',strtotime($data['as_of_date']));
            $share->share_value = $data['share_value'];
            $share->save();
            return redirect('/admin/shares');
        }else{
             return back()->with('nickNameError', 'Nickname Id does not exist.');   
        }

        
        
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
            return view('admin.shares.edit',compact('share'));
            
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
            $todayDate = date('Y-m-d');
            $jandate = date('01/01/2021');
            $data = $request->only('nick_name_id','as_of_date','share_value');
             $share = SharesAlgorithm::where('id','=',$id)->first();
              $validatorArray = [ 
                  'nick_name_id' => 'required',
                   'as_of_date' => 'required|date|after_or_equal:'.$jandate.'|before_or_equal:'.$todayDate,
                   'share_value' => 'required|numeric|min:1|max:100000'
                  ];
             if($share->nick_name_id != $data['nick_name_id']){
                 $validatorArray['nick_name_id'] = 'required';
             }
             if($share->as_of_date != $data['as_of_date']){
                $validatorArray['as_of_date'] = 'required|date|after_or_equal:'.$jandate.'|before_or_equal:'.$todayDate;
             }
             if($share->share_value != $data['share_value']){
                $validatorArray['share_value'] = 'required|numeric|min:1|max:100000';
             }
             
             $validator = Validator::make($request->only(['nick_name_id','as_of_date','share_value']), $validatorArray);
             if ($validator->fails()) {  
                return back()->withErrors($validator->errors())->withInput($request->all());
            }

            $nickName = Nickname::find($data['nick_name_id']);
            if(isset($nickName) && isset($nickName->id)){
                $share = SharesAlgorithm::find($id);
                $share->nick_name_id = $data['nick_name_id'];
                $share->as_of_date =  date('Y-m-d',strtotime($data['as_of_date']));
                $share->share_value = $data['share_value'];
                $share->save();
                $request->session()->flash('success', 'Share Data Updated Successfully');
                return redirect('/admin/shares');
            }else{
                 return back()->with('nickNameError', 'Nickname Id does not exist.');   
            }
            
            
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

    public function getshares(Request $request){
         $data = $request->only('month');
         $table = "<table class='table table-row'><tr>
                    <th>Nick Name</th>
                    <th>Date</th>
                    <th>share</th>
                    <th>Sqrt(shares)</th>
                    <th>Action</th>
                </tr>";
        if(isset($data['month']) && $data['month']!=''){
                $year = date('Y',strtotime($data['month']));
                $month = date('m',strtotime($data['month']));
                $dataShares = SharesAlgorithm::whereYear('as_of_date', '=', $year)
                  ->whereMonth('as_of_date', '=', $month)->paginate(10);
               
            }else{

                $dataShares = SharesAlgorithm::paginate(10);
           
            }
            
            if(count($dataShares) > 0){
                foreach($dataShares as $d){
                    $table.="<tr>";
                    $table.="<td>".$d->usernickname->nick_name."</td><td>".date("F,Y",strtotime($d->as_of_date))."</td><td>".$d->share_value."</td><td>".number_format(sqrt($d->share_value),2)."</td>";
                    $table.="<td>
                        <a href='".url('/admin/shares/edit/'.$d->id) ."'><i class='fa fa-edit'></i>&nbsp;&nbsp;Edit</a>
                        &nbsp;&nbsp;<a href='javascript:void(0)' onClick='deleteShare(".$d->id.")'><i class='fa fa-trash'></i>&nbsp;&nbsp;Delete</a>
                    </td>";
                    $table.="</tr>";
                }
            }else{
                $table.="<tr>";
                $table.="<td colspan='5'><span>No Share data found!</span></td>";
                $table.="</tr>";
            }

            $table.="</table>";
            $table.=$dataShares->links();
          echo $table; exit;
        

    }


}
