<?php

namespace App\Http\Controllers;

use App\Host;
use Illuminate\Http\Request;

class HostController extends Controller
{
    public function getStatus(Request $request) {
        $hosts = Host::orderBy('last_online_time', "desc")->get();
        $cnt = 0;
        foreach ($hosts as $host) {
            if (time() - $host->last_online_time < 300) {
                $host->online = true;
                $cnt++;
            }
        }
        return json_encode(['result' => true, 'count' => $cnt, 'hosts' => $hosts]);
    }

    public function syncStatus(Request $request, $hostname) {
        if($request->time) {
            $host = Host::where('hostname', $hostname)->first();
            $host->last_online_time = $request->time;
        }
        return json_encode(['result' => true, 'time' => $request->time]);
    }
}
