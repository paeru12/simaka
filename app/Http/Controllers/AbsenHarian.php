<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsenHarian extends Controller
{
    function index() {
        return view('absensiharian');
    }
}
