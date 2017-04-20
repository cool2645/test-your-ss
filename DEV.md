# Test Your Shadowsocks

This project generally contains the following features.

+ User Interface
    + Users can either select address from accessed website with mu api v2 support or provide a json config file themselves.
    + Users can choose one docker they prefer from all dockers provided by the program.
    + Users are able to launch a number of requests in certain time.
      - jobs
          <table>
            <tr>
              <th>Key</th>
              <th>Type</th>
            </tr>
            <tr>
              <td>id</td>
              <td>integer</td>
            </tr>
            <tr>
              <td>node_ip4</td>
              <td>string</td>
            </tr>
            <tr>
              <td>node_ip6</td>
              <td>string</td>
            </tr>
            <tr>
              <td>port</td>
              <td>integer</td>
            </tr>
            <tr>
              <td>docker</td>
              <td>string</td>
            </tr>
            <tr>
              <td>request_ip</td>
              <td>string</td>
            </tr>
            <tr>
              <td>status</td>
              <td>string</td>
            </tr>
            <tr>
              <td>log</td>
              <td>longText</td>
            </tr>
          </table>
    + With administrator key you can create jobs without limit.
    + Once jobs are created, status can be synced by the front-end with the polling strategy. 
    + After job run, success or failure should be judged.
      
+ Docker Image
    + shadowsocks-pip
    + shadowsocks-master
    + shadowsocks-release
    + shadowsocksR-master
    + shadowsocksR-release
+ Cron Job
    + update docker images
    

<hr>

Some other details.

+ Docker images base on Alpine Linux.
+ For each test we use python client and curl [http://ipv4.vm0.test-ipv6.com/ip/](http://ipv4.vm0.test-ipv6.com/ip/) and [http://ipv6.vm0.test-ipv6.com/ip/](http://ipv6.vm0.test-ipv6.com/ip/) with proxychains, assert if the result is similar with ping/ping6 result.
+ Cron job could use Travis CI.
+ Captcha is required for launching jobs.