<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Library\General;
use App\Library\Wiky;
use App\Library\wikiparser\wikiParser;
use App\Model\Topic;
use App\Model\Camp;
use App\Model\Statement;
use App\Model\Nickname;
use DB;
use Validator;
use App\Model\Namespaces;
use App\Model\NamespaceRequest;

/**
 * ApiController Class Doc Comment
 *
 * @category Class
 * @package  MyPackage
 * @author   Varun Gautam <gautamv16@gmail.com>
 * @license  GNU General Public License     
 * @link     http://varungautam.com
 */
class ApiController extends Controller {
    
    public function __construct()
    {
        parent::__construct();
        //$this->middleware('auth'); //->except('logout');
    }

    
    /**
     * Return camp outline.
     *
     * @return \Illuminate\Http\Response
     */
    public function getcampoutline($topic_num,$camp_num,$add_supporters) {
        
		$topic     = Camp::getAgreementTopic($topic_num);
		$campOutline = $topic->campTreeHtml($camp_num,1,$add_supporters);
		
		return response()->json(['outline' => $campOutline, 'status' => '200']);
    }

    
    /**
     * Display the specified topic data with camps/statement.
     *
     * @param  int  $id = topic_num, $parentcampnum
     * @return \Illuminate\Http\Response
     */
    public function show($id,$parentcampnum) {
        			
		$topicnumArray  = explode("-",$id);
		$topicnum       = $topicnumArray[0];
		
		$topic      = Camp::getAgreementTopic($topicnum);
        $camp       = Camp::getLiveCamp($topicnum,$parentcampnum);
        $parentcamp = Camp::campNameWithAncestors($camp,'',$topic->topic_name);
        
		$wiky=new Wiky;
		
		$WikiParser  = new wikiParser;

		
		return view('topics.view',  compact('topic','parentcampnum','parentcamp','camp','wiky','WikiParser'));
    }

    	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }


    public function usersupports(Request $request,$id){
    
        $nickName = Nickname::find($id);
        $namespaces= Namespaces::all();
        return view('user-supports',compact('nickName','namespaces'));
    }

}
