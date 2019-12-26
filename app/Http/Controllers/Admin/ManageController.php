<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Namespaces;
use Illuminate\Http\Request;
use App\Model\NamespaceRequest;
use DB;
use Validator;
use App\Model\Topic;

class ManageController extends Controller {

	public function getIndex(){
		$namespaces = Namespaces::paginate(10);
		$users = \App\User::where('status','=',1)->count();
		$camps = \App\Model\Camp::where('objector_nick_id', '=', NULL)->where('go_live_time', '<=', time())->count();
		$topics = \App\Model\Topic::where('objector_nick_id', '=', NULL)
                            ->where('go_live_time', '<=', time())->count();
		
		return view('admin.index',compact('namespaces','users','camps','topics'));
	}
	public function namespace(){
		$namespaces = Namespaces::paginate(10);
		return view('admin.namespace',compact('namespaces'));
	}

	public function getCreateNamespace(Request $request){
		$requestId = $request->input('request_id');
		$namespaceRequest = NamespaceRequest::find($requestId);
		$namespaces = Namespaces::all();
		return view('admin.namespace-create-form',compact('namespaces','namespaceRequest'));
	}

	public function postCreateNamespace(Request $request){
		$data = $request->only(['name','parent_id']);
		$data['name'] = str_slug($data['name']);
		 $validatorArray = [  'name' => 'required|unique:namespace|max:100'];
         $message = [
         	'name.required' => 'Namespace field is required.',
         	'name.unique' => 'Namespace must be unique.',
         	'name.max' => 'Namespace Name may not be greater than 100 characters.'
         ];
         $validator = Validator::make($data, $validatorArray, $message);
         if ($validator->fails()) {  
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
        $data['name'] = str_slug($data['name']);
		$requestId = $request->input('request_id');
		$namespaceRequest = NamespaceRequest::find($requestId);
		
		$slug = str_slug($data['name']);
		if(isset($data['parent_id']) && $data['parent_id'] !=0 ){
			if($namespace = Namespaces::find($data['parent_id'])){
				$slug = $namespace->label.$slug.'/';
			}
		}
		if($slug[0] != '/' || $slug[strlen($slug) - 1] != '/'){
			$slug = "/".$slug."/";
		}
		$data['label'] = $slug;
		$namespace = Namespaces::create($data);
		if($namespaceRequest){
			$namespaceRequest->status=1;
			$namespaceRequest->save();
			Topic::where('topic_num',$namespaceRequest->topic_num)->update(array('namespace_id'=>$namespace->id));
		}
		return redirect('/admin/namespace');
	}

	public function getUpdateNamespace(Request $request,$id){
		$namespaces = Namespaces::all();
		$namespace = Namespaces::find($id);
		return view('admin.namespace-update-form',compact('namespaces','namespace'));
	}

	public function postUpdateNamespace(Request $request,$id){
		
		$data = $request->only(['name','parent_id']);

		$slug = (isset($data['name']) && $data['name'] != '' && $data['name']!=null) ? str_slug($data['name']) : '';
		$oldNamespace = Namespaces::find($id);
		if(isset($data['parent_id']) && $data['parent_id'] !=0 ){
			if($namespace = Namespaces::find($data['parent_id'])){
				$slug = $namespace->label.'/'.$slug;
			}
		}
		if((isset($slug[0]) && $slug[0] != '/') || (!empty($slug) && $slug[strlen($slug) - 1] != '/')){
			$slug = "/".$slug."/";
		}
		$data['label'] = $slug;
		$data['name'] = str_slug($data['name']);
		
		if(strtolower($oldNamespace->name) != strtolower($data['name'])){
			$validatorArray = [  'name' => 'required|unique:namespace|max:100'];
		}else{
			$validatorArray = [  'name' => 'required|max:100'];
		}
	  $message = [
         	'name.required' => 'Namespace field is required.',
         	'name.unique' => 'Namespace must be unique.',
         	'name.max' => 'Namespace Name may not be greater than 100 characters.'
         ];
         $validator = Validator::make($data, $validatorArray, $message);
         if ($validator->fails()) {  
            return back()->withErrors($validator->errors())->withInput($request->all());
        }
      
		
		$oldNamespace->name = $data['name'];
		$oldNamespace->parent_id = isset($data['parent_id']) ? $data['parent_id'] : 0;
		$oldNamespace->label = $data['label'];
		$oldNamespace->save();

		return redirect('/admin/namespace');
	}

	public function getNamespaceRequests(){

		$namespacesrequest = NamespaceRequest::orderBy('created_at','DESC')->paginate(10);
		
		return view('admin.namespace-requests',compact('namespacesrequest'));
	}

	public function logout(){

		$this->guard('admin')->logout();

        $request->session()->invalidate();

        return redirect('/admin');
	}
}
