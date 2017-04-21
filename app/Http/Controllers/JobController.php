<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Http\Controllers\HttpHelper;

class JobController extends Controller
{
    private function check($server, $port, $docker)
    {
        $cnt = Job::where('node_ip', $server)
            ->where('port', $port)
            ->where('docker', $docker)
            ->where('time', '>', time() - env('ALLOW_EACH_HOUR', 5) * 60 * 60)
            ->count();
        if ($cnt > env('ALLOW_TIMES', 3))
            return false;
        else
            return true;
    }

    public function createJob(Request $request)
    {
        switch ($request->config) {
            case "json":
                $data = json_decode($request->json, true);
                $ip = HttpHelper::getNodeIP($data['server']);
                if ((isset($request->admin_key) && $request->admin_key == env('ADMIN_KEY'))
                    || $this->check($ip[0], $data['server_port'], $request->docker)
                ) {
                    if ($ip[0]) {
                        $job = new Job();
                        $job->node_ip = $ip[0];
                        $job->node_ip4 = $ip[1];
                        $job->node_ip6 = $ip[2];
                        $job->port = $data['server_port'];
                        $job->docker = $request->docker;
                        $job->config = $request->json;
                        $job->time = time();
                        $job->request_ip = $request->ip();
                        $job->status = "Queuing";
                        $job->save();
                        return json_encode(['result' => true, 'data' => ['ip' => $ip[0], 'ip4' => $ip[1], 'ip6' => $ip[2]]]);
                    }
                    else
                        return json_encode(['result' => false, 'msg' => "Unknown host", 'data' => ['ip' => $ip[0], 'ip4' => $ip[1], 'ip6' => $ip[2]]]);
                } else
                    return json_encode(['result' => false, 'msg' => 'You can only open ' . env('ALLOW_TIMES', 3) . " times in " . env('ALLOW_EACH_HOUR', 5) . " hours."]);
                break;

        }
    }

    public function getJobList()
    {

    }

    public function getJobStatus()
    {

    }

    public function getQueuingJobs()
    {

    }

    public function assignJob()
    {

    }

    public function showJobLog()
    {

    }

    public function syncJobLog()
    {

    }

    public function judge()
    {

    }
}
