<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Http\Controllers\HttpHelper;
use Illuminate\Queue\RedisQueue;

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
        $this->validate($request, [
            "config" => "required",
            "docker" => "required"
        ]);
        switch ($request->config) {
            case "json":
                $this->validate($request, [
                    "json" => "required"
                ]);
                $json = $request->json;
                $json_var = json_decode($json, true);
                $node_addr = $json_var['server'];
                break;
            case "mu_api_v2":
                $this->validate($request, [
                    "website" => "required",
                    "email" => "required",
                    "password" => "required",
                    "node" => "required"
                ]);
                $node_addr = explode(':', $request->node)[0];
                $json = HttpHelper::getSSConfigByMuApiV2($request->website, $request->email, $request->password, $request->node);
                break;
            case "2645network_ssr":
                $this->validate($request, [
                    "website" => "required",
                    "email" => "required",
                    "password" => "required",
                    "node" => "required"
                ]);
                $node_addr = explode(':', $request->node)[0];
                $json = HttpHelper::getSSRConfigBy2645Network($request->website, $request->email, $request->password, $request->node);
                break;

        }
        $data = json_decode($json, true);
        $ip = HttpHelper::getNodeIP($data['server']);
        if ((isset($request->admin_key) && $request->admin_key == env('ADMIN_KEY'))
            || $this->check($ip[0], $data['server_port'], $request->docker)
        ) {
            if ($ip[0]) {
                $job = new Job();
                $job->node_addr = $node_addr;
                $job->node_ip = $ip[0];
                $job->node_ip4 = $ip[1];
                $job->node_ip6 = $ip[2];
                $job->port = $data['server_port'];
                $job->docker = $request->docker;
                $job->config = $json;
                $job->time = time();
                $job->request_ip = $request->ip();
                $job->status = "Queuing";
                $job->save();
                return json_encode(['result' => true, 'data' => ['ip' => $ip[0], 'ip4' => $ip[1], 'ip6' => $ip[2]]]);
            } else
                return json_encode(['result' => false, 'msg' => "Unknown host", 'data' => ['ip' => $ip[0], 'ip4' => $ip[1], 'ip6' => $ip[2]]]);
        } else
            return json_encode(['result' => false, 'msg' => 'You can only open ' . env('ALLOW_TIMES', 3) . " times in " . env('ALLOW_EACH_HOUR', 5) . " hours."]);

    }

    public function getJobList(Request $request)
    {
        $jobs = Job::orderBy('id', 'desc')->limit(20)->offset($request->offset)->get();
        return json_encode($jobs);
    }

    public function getJobStatus($id)
    {
        $job = Job::find($id);
        return json_encode($job);
    }

    public function getQueuingJobs()
    {
        $jobs = Job::where('status', "Queuing")->orderBy('id', 'asc')->limit(20)->get();
        return json_encode($jobs);
    }

    public function assignJob(Request $request, $id)
    {
        $job = Job::find($id);
        if ($job->status != "Queuing")
            return json_encode(['result' => false, 'msg' => "locked"]);
        $job->run_host = $request->run_host;
        $job->status = "Starting";
        $job->save();
        return json_encode(['result' => true, 'msg' => "success", 'id' => $job->id, 'json' => $job->config, 'docker' => $job->docker]);
    }

    public function cancelJob($id)
    {
        $job = Job::find($id);
        $job->status = "Queuing";
        $job->run_host = null;
        $job->log = null;
        $job->save();
        return json_encode(['result' => true, 'msg' => "success"]);
    }

    public function showJobLog($id)
    {
        $job = Job::find($id);
        return json_encode(['result' => true, 'data' => $job->log]);
    }

    public function syncJobLog(Request $request, $id)
    {
        $job = Job::find($id);
        if ($request->log != "") {
            $request->log .= "\n";
            $job->status = "Running";
        }
        $job->log .= $request->log;
        $job->save();
        return json_encode(['result' => true, 'msg' => "success"]);
    }

    public function judge(Request $request, $id)
    {
        if (isset($request->key) || (isset($request->admin_key) && $request->admin_key == env('ADMIN_KEY'))) {
            $job = Job::find($id);
            $job->status = "Pending";
            $job->save();
            if (strpos($job->log, "callback") !== false)
                $job->status = "Passing";
            else
                $job->status = "Failing";
            $job->save();
            return json_encode(['result' => true, 'msg' => $job->status]);
        }
        return json_encode(['result' => false, 'msg' => 'Authentication failure']);
    }

    public function reRun(Request $request, $id)
    {
        $job = Job::find($id);
        if ((isset($request->admin_key) && $request->admin_key == env('ADMIN_KEY'))
            || $this->check($job->node_ip, $job->port, $job->docker)
        ) {
            $job1 = new Job();
            $job1->node_addr = $job->node_addr;
            $job1->node_ip = $job->node_ip;
            $job1->node_ip4 = $job->node_ip4;
            $job1->node_ip6 = $job->node_ip6;
            $job1->port = $job->port;
            $job1->docker = $job->docker;
            $job1->config = $job->config;
            $job1->time = time();
            $job1->request_ip = $request->ip();
            $job1->status = "Queuing";
            $job1->save();
            return json_encode(['result' => true, 'msg' => "success"]);
        } else
            return json_encode(['result' => false, 'msg' => 'You can only open ' . env('ALLOW_TIMES', 3) . " times in " . env('ALLOW_EACH_HOUR', 5) . " hours."]);
    }
}
