<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HttpHelper extends Controller
{
    private function curl_post($url, $post_data)
    {
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息不作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);

        return $data;
    }

    private function curl_get($url, $get_data)
    {
        $curl = curl_init();
        $url .= "?";
        foreach ($get_data as $key => $val)
            $url .= "$key=$val&";
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息不作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);

        return $data;
    }

    public function getNodeIP()
    {

    }

    public function getSSNodesByMuApiV2(Request $request)
    {
        $this->validate($request, [
            "website" => "required",
            "email" => "required",
            "password" => "required"
        ]);
        $url = $request->website . "/token";
        $post_data = array(
            "email" => $request->email,
            "passwd" => $request->password
        );
        $data = $this->curl_post($url, $post_data);
        $data = json_decode($data, true);
        if ($data['ret'] == 0)
            return json_encode($data);
        else
            $token = $data['data']['token'];

        $url = $request->website . "/node";
        $get_data = array(
            "access_token" => $token
        );
        $data = $this->curl_get($url, $get_data);

        $data = json_decode($data, true);
        if ($data['ret'] == 0)
            return json_encode($data);
        else {
            $nodes = array();
            foreach ($data['data'] as $node_data) {
                array_push($nodes, $node_data['server']);
            }
            return json_encode(['result' => true, 'data' => $nodes]);
        }
    }

    public function getSSConfigByMuApiV2()
    {

    }

    public function getSSRNodesBy2645NetWork(Request $request)
    {
        $this->validate($request, [
            "website" => "required",
            "email" => "required",
            "password" => "required"
        ]);
        $url = $request->website . "/token";
        $post_data = array(
            "email" => $request->email,
            "passwd" => $request->password
        );
        $data = $this->curl_post($url, $post_data);
        $data = json_decode($data, true);
        if ($data['ret'] == 0)
            return json_encode($data);
        else
            $token = $data['data']['token'];

        $url = $request->website . "/node";
        $get_data = array(
            "access_token" => $token
        );
        $data = $this->curl_get($url, $get_data);

        $data = json_decode($data, true);
        if ($data['ret'] == 0)
            return json_encode($data);
        else {
            $nodes = array();
            foreach ($data['data'] as $node_data) {
                if ($node_data['ssr'])
                    array_push($nodes, $node_data['server']);
            }
            return json_encode(['result' => true, 'data' => $nodes]);
        }
    }

    public function getSSRConfigBy2645Network()
    {

    }
}
