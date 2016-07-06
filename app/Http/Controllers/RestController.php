<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class RestController extends Controller {

    public function msg() {
        return response("Hola", 200);
    }

}
