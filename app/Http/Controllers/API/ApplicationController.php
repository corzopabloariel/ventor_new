<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApplicationTmp;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        if ($request->isJson()) {

            return ApplicationTmp::create($request);

        } else {

            return response(
                array(
                    'error' => true,
                    'status' => 401,
                    'message' => 'Sin autorización'
                ),
                401
            );

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ApplicationTmp  $application
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationTmp $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ApplicationTmp  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ApplicationTmp $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ApplicationTmp  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(ApplicationTmp $application)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function elements(Request $request) {

        if ($request->isJson()) {

            return ApplicationTmp::elements($request->all());

        } else {

            return response(
                array(
                    'error' => true,
                    'status' => 401,
                    'message' => 'Sin autorización'
                ),
                401
            );

        }

    }
}
