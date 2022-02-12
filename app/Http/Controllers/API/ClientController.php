<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {

        return Client::gets($request);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $userId) {

        return Client::one($request, $userId);

    }

    /**
     * Acciones sobre cliente.
     *
     * @return \Illuminate\Http\Response
     */
    public function action(Request $request, int $userId, string $action) {

        return Client::action($request, $userId, $action);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) {

        return Client::change($request);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }
    
    public function event(Request $request) {

        header("Content-Type: text/event-stream");
        header("Cache-Control: no-cache");
        header("Access-Control-Allow-Origin: *");
        if (\Auth::check()) {
            $user = \Auth::user();
            if ($user->notice) {

                $notice = $user->notice;
                $notice->fill(['read' => true]);
                $notice->save();
                $json = json_encode($notice->data);
                echo "id: ".$notice->id.PHP_EOL;
                echo "data: ".$json.PHP_EOL;
                echo "event: eventClient".PHP_EOL;
                echo PHP_EOL;
                flush();
                die;

            }

        }
        echo "id: 0".PHP_EOL;
        echo "data: []".PHP_EOL;
        echo "event: eventClient".PHP_EOL;
        echo PHP_EOL;
        flush();

    }
}
