<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Namespaces;
use Illuminate\Http\Request;

class ManageController extends Controller {

	public function getIndex(){
		$namespaces = Namespaces::paginate(10);
		return view('admin.index',compact('namespaces'));
	}

	public function getCreateNamespace(){
		$namespaces = Namespaces::all();
		return view('admin.namespace-create-form',compact('namespaces'));
	}

	public function postCreateNamespace(Request $request){
		$data = $request->only(['name','parent_id']);
		$slug = str_slug($data['name']);
		if(isset($data['parent_id']) && $data['parent_id'] !=0 ){
			if($namespace = Namespaces::find($data['parent_id'])){
				$slug = $namespace->label.'/'.$slug;
			}
		}
		$data['label'] = $slug;
		Namespaces::create($data);
		return redirect('/admin');
	}

	public function getUpdateNamespace(Request $request,$id){
		$namespaces = Namespaces::all();
		$namespace = Namespaces::find($id);
		return view('admin.namespace-update-form',compact('namespaces','namespace'));
	}

	public function postUpdateNamespace(Request $request,$id){
		
		$data = $request->only(['name','parent_id']);
		$slug = str_slug($data['name']);
		if(isset($data['parent_id']) && $data['parent_id'] !=0 ){
			if($namespace = Namespaces::find($data['parent_id'])){
				$slug = $namespace->label.'/'.$slug;
			}
		}
		$data['label'] = $slug;

		$oldNamespace = Namespaces::find($id);
		$oldNamespace->name = $data['name'];
		$oldNamespace->parent_id = $data['parent_id'];
		$oldNamespace->label = $data['label'];
		$oldNamespace->save();

		return redirect('/admin');
	}
}
