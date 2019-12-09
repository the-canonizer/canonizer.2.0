<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Templates;
use Validator;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Templates::paginate(10);
        return view('admin.templates.index',compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('name','subject','body','status');
         $validatorArray = [ 
          'name' => 'required',
          'subject' => 'required',
          'body' => 'required'
          ];
         $message = [
            'name.required' => 'Template Name field is required.',
            'subject.required' => 'Template Subject field is required.',
            'body.required' => 'Template Body field is required.',
         ];
         $validator = Validator::make($request->only(['name','subject','body']), $validatorArray, $message);
         if ($validator->fails()) {  
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        $template = new Templates();
        $template->name = $data['name'];
        $template->subject = $data['subject'];
        $template->body = $data['body'];
        $template->status = $data['status'];
        $template->save();
        return redirect('/admin/templates');
        
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
            $template = Templates::find($id);
            return view('admin.templates.edit',compact('template'));
            
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
            $data = $request->only('name','subject','body','status');
             $validatorArray = [ 
              'name' => 'required',
              'subject' => 'required',
              'body' => 'required'
              ];
             $message = [
                'name.required' => 'Name field is required.',
                'subject.required' => 'Subject field is required.',
                'body.required' => 'Body field is required.',
             ];
             $validator = Validator::make($request->only(['name','subject','body']), $validatorArray, $message);
             if ($validator->fails()) {  
                return back()->withErrors($validator->errors())->withInput($request->all());
            }
            $template = Templates::find($id);
            $template->name = $data['name'];
            $template->subject = $data['subject'];
            $template->status = $data['status'];
            $template->body = $data['body'];
            $template->update();
            $request->session()->flash('success', 'Template Updated Successfully');
            return redirect('/admin/templates');
            
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
            Templates::find($id)->delete();
            $request->session()->flash('success', 'Template Deleted Successfully');
            return redirect('/admin/templates');
        }
    }
}
