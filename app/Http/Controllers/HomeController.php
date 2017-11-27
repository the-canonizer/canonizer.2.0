<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Topic;
use App\Model\Camp;

class HomeController extends Controller {

    public function index() {

        $topics = Topic::all();
        return view('welcome');
    }

}
