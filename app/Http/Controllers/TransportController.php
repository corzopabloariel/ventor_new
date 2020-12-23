<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;

class TransportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->search)) {
            $elements = Transport::where("code", "LIKE", "%{$request->search}%")->
                orWhere("description", "LIKE", "%{$request->search}%")->
                orWhere("address", "LIKE", "%{$request->search}%")->
                orWhere("phone", "LIKE", "%{$request->search}%")->
                orWhere("person", "LIKE", "%{$request->search}%")->
                orderBy("code")->paginate(PAGINATE);

        } else
            $elements = Transport::orderBy("code")->paginate(PAGINATE);

        $data = [
            "view" => "element",
            "url_search" => \URL::to(\Auth::user()->redirect() . "/transports"),
            "elements" => $elements,
            "entity" => "transport",
            "total" => number_format($elements->total(), 0, ",", ".") . " de " . number_format(Transport::count(), 0, ",", "."),
            "placeholder" => "todos los campos",
            "section" => "Transportes",
            "help" => "Los datos presentes son solo de consulta, para actualizarlos use el botón correspondiente",
            "buttons" => [
                [
                    "f" => "actualizar",
                    "b" => "btn-primary",
                    "i" => "fas fa-sync",
                    "t" => "actualizar datos",
                ]
            ]
        ];

        if (isset($request->search)) {
            $data["searchIn"] = ["code", "description", "address", "phone", "person"];
            $data["search"] = $request->search;
        }
        return view('home',compact('data'));
    }

    /**
     *
     * @param  String $row
     * @return String
     */
    public function clearRow($row)
    {
        $value = utf8_encode(trim($row));
        return $value === "" ? NULL : $value;
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function load(Request $request)
    {
        set_time_limit(0);
        $model = new Transport();
        $property = $model->getFillable();
        $arr_err = [];
        $filename = implode('/', [public_path(), env('FOLDER_TXT'), env('FILE_TRANSPORT')]);
        if (file_exists($filename))
        {
            Transport::removeAll();
            $file = fopen($filename, 'r');
            while (!feof($file))
            {
                $row = trim(fgets($file));
                if (empty($row) || strpos($row, 'Responsable') !== false)
                {
                    continue;
                }
                $aux = explode(env('SEPARATOR'), $row);
                $aux = array_map('self::clearRow', $aux);
                if (empty($aux))
                    continue;
                try {
                    $data = array_combine($property, $aux);
                    $client = Transport::create($data);
                } catch (\Throwable $th) {
                    $arr_err[] = $aux;
                }
            }
            fclose($file);
            return response()->json([
                "error" => 0,
                "success" => true,
                "txt" => "Documentos insertados: " . Transport::count() . " / Errores: " . count($arr_err)
            ], 200);
        }
        return response()->json([
            "error" => 1,
            "txt" => "Archivo no encontrado"
        ], 410);
        //return response()->json('Archivo no encontrado', 410);
        //abort(400, 'custom error');
        //throw new \Exception('There is an error with this rating.');
        //return response()->json(['message' => 'error message'], 400);
        //return response("", 400);
    }
}
