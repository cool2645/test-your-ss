<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;

class HomeController extends Controller
{
    public function launch()
    {
        return view('index');
    }

    public function status()
    {
        $jobs = Job::orderBy('id', 'desc')->paginate(20);
        return view('status', ['jobs' => $jobs]);
    }

    public function log($id)
    {
        $job = Job::find($id);
        return view('log', ['job' => $job]);
    }
}
