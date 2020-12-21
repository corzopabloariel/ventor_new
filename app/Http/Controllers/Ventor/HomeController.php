<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Ventor;

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

    public function index(Request $request)
    {
        $data = ["view" => "home"];
        return view('home',compact('data'));
    }

    public function data(Request $request)
    {
        if (empty($request->all())) {
            $datos = Ventor::first();
            if(empty($datos)) {
                $datos = Ventor::create([
                    'address' => [],
                    'phone' => [],
                    'email' => [],
                    'social' => [],
                    'metadata' => [],
                    'images' => [],
                    'section' => [],
                    'miscellaneous' => [],
                    'form' => []
                ]);
            }
            $data = [
                "view" => "ventor",
                "elements" => $datos,
                "section" => "Datos básicos"
            ];
            return view('home',compact('data'));
        }
        dd($request->all());
    }
}
