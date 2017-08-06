<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;

class HomeController extends Controller
{
    public function home()
    {
        return view('index');
    }

    public function status()
    {
        $jobs = Job::orderBy('id', 'desc')->paginate(20);
        return json_encode(['result' => true, 'jobs' => $jobs]);
    }

    public function log($id)
    {
        $job = Job::find($id);
        return json_encode(['result' => true, 'job' => $job]);
    }
}
