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

    public function home_status()
    {
        $jobs = Job::orderBy('id', 'desc')->paginate(20);
        return view('status', ['jobs' => $jobs]);
    }
    public function home_log($id)
    {
        $job = Job::find($id);
        return view('log', ['job' => $job]);
    }

    public function status()
    {
        $jobs = Job::select('id', 'node_addr', "docker", "port", "request_ip", "status", "run_host", "log")->orderBy('id', 'desc')->paginate(20);
        return json_encode(['result' => true, 'jobs' => $jobs]);
    }

    public function status_port($port)
    {
        $jobs = Job::where('port', $port)->select('id', 'node_addr', "docker", "port", "request_ip", "status", "run_host", "log")->orderBy('id', 'desc')->paginate(20);
        return json_encode(['result' => true, 'jobs' => $jobs]);
    }

    public function log($id)
    {
        $job = Job::find($id);
        return json_encode(['result' => true, 'job' => $job]);
    }
}
