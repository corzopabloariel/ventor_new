<?php

namespace App\Http\Controllers\Ventor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function products(Request $request) {
        return $request->all();
    }
}
