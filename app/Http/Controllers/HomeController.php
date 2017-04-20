<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function launch()
    {
        return view('index');
    }

    public function status()
    {
        return view('index');
    }

    public function about()
    {
        return view('index');
    }
}
