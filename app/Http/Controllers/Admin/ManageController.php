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
		$all = $request->all();
		$page_no  = isset($all['page']) ? $all['page'] : 1 ; 
		$data = $request->only(['name','parent_id']);
		$name = $data['name'];
		if((isset($name[0]) && $name[0] =='/') || ((!empty($name) && $name[strlen($name) - 1] == '/'))){
			$data['name'] = str_replace('/', "", $name);	
		}
		 $validatorArray = ['name' => 'required|unique:namespace|regex:"^[/]?[a-zA-Z0-9_]*[/]?$"|max:100'];
         $message = [
         	'name.required' => 'Namespace field is required.',
         	'name.unique' => 'Namespace must be unique.',
         	'name.max' => 'Namespace Name may not be greater than 100 characters.',
         	'name.regex' => 'Namespace Name may only contain letters, numbers, underscore and forward slashes(/) at beginning and end only.'
         ];

         $validator = Validator::make($data, $validatorArray, $message);
         if ($validator->fails()) {  
            return back()->withErrors($validator->errors())->withInput($request->all());
        }else{
        	$query =Namespaces::where('name',$data['name']);
        	if($data['name'][0]!='/' && $data['name'][strlen($data['name'])-1] != '/'){
        		$query = $query->orWhere('name',"/".$data['name']."/");
        	}else if($data['name'][0]!='/'){
        		$query = $query->orWhere('name',"/".$data['name']);
        	}else if($data['name'][strlen($data['name'])-1] != '/'){
        		$query = $query->orWhere('name',$data['name']."/");
        	}
			$oldData = $query->get();
         	if($oldData && sizeof($oldData) > 0){
         		return back()->withErrors($validator->errors()->add('name', 'Namespace must be unique.'))->withInput($request->all());
         	}
        }
        $name = $data['name'];
		if((isset($name[0]) && $name[0] =='/') || ((!empty($name) && $name[strlen($name) - 1] == '/'))){
			$name = str_replace('/', "", $name);	
		}
		$data['name'] = $name;
		$requestId = $request->input('request_id');
		$namespaceRequest = NamespaceRequest::find($requestId);
		$namespace = Namespaces::create($data);
		if($namespaceRequest){
			$namespaceRequest->status=1;
			$namespaceRequest->save();
			Topic::where('topic_num',$namespaceRequest->topic_num)->update(array('namespace_id'=>$namespace->id));
		}
		return redirect('/admin/namespace?page='.$page_no);
	}

	public function getUpdateNamespace(Request $request,$id){
		$namespaces = Namespaces::all();
		$namespace = Namespaces::find($id);
		return view('admin.namespace-update-form',compact('namespaces','namespace'));
	}

	public function postUpdateNamespace(Request $request,$id){
		
		$data = $request->only(['name','parent_id']);
		$all = $request->all();
		$page_no  = isset($all['page']) ? $all['page'] : 1 ;  
		$oldNamespace = Namespaces::find($id);
		$name = $data['name'];
		if((isset($name[0]) && $name[0] =='/') || ((!empty($name) && $name[strlen($name) - 1] == '/'))){
			$data['name'] = str_replace('/', "", $name);	
		}
		if(strtolower($oldNamespace->name) != strtolower($data['name'])){
			$validatorArray = [  'name' => 'required|unique:namespace|max:100|regex:"^[/]?[a-zA-Z0-9_]*[/]?$"'];
		}else{
			$validatorArray = [  'name' => 'required|max:100|regex:"^[/]?[a-zA-Z0-9_]*[/]?$"'];
		}
	  $message = [
         	'name.required' => 'Namespace field is required.',
         	'name.unique' => 'Namespace must be unique.',
         	'name.max' => 'Namespace Name may not be greater than 100 characters.',
         	'name.regex' => 'Namespace Name may only contain letters, numbers, underscore and forward slashes(/) at beginning and end only.'
         ];
         $validator = Validator::make($data, $validatorArray, $message);
         if ($validator->fails()) {  
            return back()->withErrors($validator->errors())->withInput($request->all());
        }else{
        	$query =Namespaces::where('name',$data['name']);
        	if($data['name'][0]!='/' && $data['name'][strlen($data['name'])-1] != '/'){
        		$query = $query->orWhere('name',"/".$data['name']."/");
        	}else if($data['name'][0]!='/'){
        		$query = $query->orWhere('name',"/".$data['name']);
        	}else if($data['name'][strlen($data['name'])-1] != '/'){
        		$query = $query->orWhere('name',$data['name']."/");
        	}
			$oldData = $query->get();
         	if($oldData && sizeof($oldData) > 0 && $oldNamespace->id != $oldData[0]->id){
         		return back()->withErrors($validator->errors()->add('name', 'Namespace must be unique.'))->withInput($request->all());
         	}
        }
		$name = $data['name'];
		if((isset($name[0]) && $name[0] =='/') || ((!empty($name) && $name[strlen($name) - 1] == '/'))){
			$name = str_replace('/', "", $name);	
		}
		$data['name'] = $name;
		$oldNamespace->name = $data['name'];
		$oldNamespace->parent_id = isset($data['parent_id']) ? $data['parent_id'] : 0;
		$oldNamespace->save();

		return redirect('/admin/namespace?page='.$page_no);
	}

	public function removeNamespace($id){
		if($id){
			$namespace = Namespaces::find($id);
     		$namespace->delete();     		
		}
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
