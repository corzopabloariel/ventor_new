<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:adm']);
    }

    public function index(Request $Request)
    {
        $data = ["view" => "home"];
        return view('home',compact('data'));
    }
}
