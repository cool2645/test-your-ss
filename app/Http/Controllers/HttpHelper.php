<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HttpHelper extends Controller
{
    static private function curl_post($url, $post_data)
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

    static private function curl_get($url, $get_data)
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

    static public function getNodeIP($host)
    {
        $ret = self::getNodeIP4($host);
        if ($ret)
            return [$ret, $ret, self::getNodeIP6($host)];
        else {
            $ret6 = self::getNodeIP6($host);
            return [$ret6, $ret, $ret6];
        }
    }

    static public function getNodeIP4($host)
    {
        $ret4 = shell_exec('ping -c 4 -n ' . $host);
        preg_match('/(?<=\()([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])\.([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])(?=\))/', $ret4, $ipv4);
        if (count($ipv4) > 0)
            return $ipv4[0];
        else
            return false;
    }

    static public function getNodeIP6($host)
    {
        $ret6 = shell_exec('ping6 -c 4 -n ' . $host);
        preg_match('/(?<=\()((([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])))(?=\))/', $ret6, $ipv6);
        if (count($ipv6) > 0)
            return $ipv6[0];
        else
            return false;
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
                array_push($nodes, ['node_name' => $node_data['server'], 'node_method' => $node_data['custom_method'] ? "custom_method" : $node_data['method']]);
            }
            return json_encode(['result' => true, 'data' => $nodes]);
        }
    }

    static public function getSSConfigByMuApiV2($website, $email, $password, $node)
    {
        $url = $website . "/token";
        $post_data = array(
            "email" => $email,
            "passwd" => $password
        );
        $data = self::curl_post($url, $post_data);
        $data = json_decode($data, true);
        if ($data['ret'] == 0)
            return false;
        else
            $token = $data['data']['token'];

        $url = $website . "/user/" . $data['data']['user_id'];
        $get_data = array(
            "access_token" => $token
        );
        $data = self::curl_get($url, $get_data);

        $data = json_decode($data, true);
        if ($data['ret'] == 0)
            return false;
        else {
            $node = explode(':', $node);
            $method = $node[1];
            $node = $node[0];
            $config = [
                'server' => $node,
                'server_port' => $data['data']['port'],
                'password' => $data['data']['passwd'],
                'method' => $method == "custom_method" ? $data['data']['method'] : $method
            ];
            return json_encode($config);
        }
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
                    array_push($nodes, [
                        'node_name' => $node_data['server'],
                        'node_method' =>
                            ($node_data['ssr_port'] == 0 ? ($node_data['custom_method'] ? "custom_method" : $node_data['method']) : $node_data['add_method'])
                            . ":" .
                            //protocol
                            $node_data['protocol']
                            . ":" .
                            //protocol_param
                            ($node_data['ssr_port'] == 0 ? $node_data['protocol_param'] : $node_data['add_passwd'])
                            . ":" .
                            //obfs
                            $node_data['obfs']
                            . ":" .
                            //obfs_param
                            $node_data['obfs_param']
                            . ":" .
                            //ssr_port
                            $node_data['ssr_port']
                    ]);
            }
            return json_encode(['result' => true, 'data' => $nodes]);
        }
    }

    static public function getSSRConfigBy2645Network($website, $email, $password, $node)
    {
        $url = $website . "/token";
        $post_data = array(
            "email" => $email,
            "passwd" => $password
        );
        $data = self::curl_post($url, $post_data);
        $data = json_decode($data, true);
        if ($data['ret'] == 0)
            return false;
        else
            $token = $data['data']['token'];

        $url = $website . "/user/" . $data['data']['user_id'];
        $get_data = array(
            "access_token" => $token
        );
        $data = self::curl_get($url, $get_data);

        $data = json_decode($data, true);
        if ($data['ret'] == 0)
            return false;
        else {
            $node = explode(':', $node);
            $method = $node[1];
            $protocol = $node[2];
            $protocol_param = $node[3];
            $obfs = $node[4];
            $obfs_param = $node[5];
            $ssr_port = $node[6];
            $node = $node[0];
            $config = [
                'server' => $node,
                'server_port' => $ssr_port == 0 ? $data['data']['port'] : $ssr_port,
                'password' => $ssr_port == 0 ? $data['data']['passwd'] : $protocol_param,
                'method' => $ssr_port == 0 ? ($method == "custom_method" ? $data['data']['method'] : $method) : $method,
                'protocol' => $protocol,
                'protocol_param' => $ssr_port == 0 ? $protocol_param : $data['data']['port'] . ":" . $data['data']['passwd'],
                'obfs' => $obfs,
                'obfs_param' => $obfs_param
            ];
            return json_encode($config);
        }
    }
}
