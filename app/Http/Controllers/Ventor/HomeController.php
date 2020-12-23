<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Ventor;
use App\Models\Ventor\Ticket;
use App\Models\Content;

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
        $data = Ventor::first();
        if (empty($request->all())) {
            if(empty($data)) {
                $data = Ventor::create([
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
                "elements" => $data,
                "section" => "Datos básicos"
            ];
            return view('home',compact('data'));
        }
        
        $aux = (new \App\Http\Controllers\Auth\BasicController)->store($request, $data, new Ventor, null, true);
        $OBJ = json_decode($aux, true);
        if ($OBJ["error"] == 0) {
            if ($OBJ["success"]) {
                $data->fill($OBJ["data"]);
                $data->save();

                foreach ($OBJ["data"] AS $k => $v) {
                    $valueNew = $v;
                    $valueOld = $data[$k];
                    if (gettype($valueNew) == "array")
                        $valueNew = json_encode($valueNew);
                    if (gettype($valueOld) == "array")
                        $valueOld = json_encode($valueOld);
                    if ($valueOld != $valueNew) {
                        Ticket::create([
                            'type' => 3,
                            'table' => 'ventor',
                            'table_id' => $data->id,
                            'obs' => '<p>Se modificó el valor de "' . $k . '" de [' . $valueOld . '] por [' . $valueNew . ']</p>',
                            'user_id' => \Auth::user()->id
                        ]);
                    }
                }
            }
        }
        return $aux;
    }

    public function content(Request $request, $section)
    {
        $data = Content::section($section);
        if (empty($request->all())) {
            if (!$data) {
                $data = Content::create(
                    ['section' => $section, 'data' => []]
                );
            }
            $data = [
                "content" => $section,
                "view" => "content",
                "elements" => $data,
                "section" => "Contenido de " . strtoupper($section)
            ];
            return view('home',compact('data'));
        }
        $aux = (new \App\Http\Controllers\Auth\BasicController)->store($request, $data->data, new Ventor, null, true);
        $OBJ = json_decode($aux, true);
        if ($OBJ["error"] == 0) {
            if ($OBJ["success"]) {
                foreach ($OBJ["data"] AS $k => $v) {
                    $valueNew = $v;
                    $valueOld = isset($data->data[$k]) ? $data->data[$k] : "";
                    if (gettype($valueNew) == "array")
                        $valueNew = json_encode($valueNew);
                    if (gettype($valueOld) == "array")
                        $valueOld = json_encode($valueOld);
                    if ($valueOld != $valueNew) {
                        Ticket::create([
                            'type' => 3,
                            'table' => 'contents',
                            'table_id' => $data->id,
                            'obs' => '<p>Se modificó el valor de "' . $k . '" de [' . $valueOld . '] por [' . $valueNew . ']</p>',
                            'user_id' => \Auth::user()->id
                        ]);
                    }
                }
                $data->fill(['data' => $OBJ["data"]]);
                $data->save();
            }
        }
        return $aux;
    }
}
