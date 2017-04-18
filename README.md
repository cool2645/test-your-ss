# Test Your Shadowsocks

This project generally contains the following features.

+ Database API
    + The program can be granted with ss-panel v3 database access. 
        - groups
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
            <td>group_name</td>
            <td>string</td>
          </tr>
          <tr>
            <td>user</td>
            <td>string</td>
          </tr>
          <tr>
            <td>password</td>
            <td>string</td>
          </tr>
          <tr>
            <td>database</td>
            <td>string</td>
          </tr>
        </table>
    + Return json config file if user authenticated.
+ User Interface
    + Users can either select address from accessed website or provide a json config file themselves.
      - nodes
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
            <td>address</td>
            <td>string</td>
          </tr>
          <tr>
            <td>ipv4_ip</td>
            <td>string</td>
          </tr>
          <tr>
            <td>group_id</td>
            <td>integer</td>
          </tr>
        </table>
    + Users can choose one docker they prefer from all dockers provided by the program.
      - dockers
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
            <td>name</td>
            <td>string</td>
          </tr>
          <tr>
            <td>description</td>
            <td>text</td>
          </tr>
        </table>
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
              <td>node_id</td>
              <td>integer</td>
            </tr>
            <tr>
              <td>port</td>
              <td>integer</td>
            </tr>
            <tr>
              <td>docker_id</td>
              <td>integer</td>
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
              <td>text</td>
            </tr>
          </table>
    + With administrator key you can create jobs without limit and cut queue.
    + Once jobs are created, status can be synced by the front-end with the polling strategy. 
    In other words, there should be a status api.
    + After job run, success or failure should be judged.
    + Once failure, alert the system administrator.
      - admins
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
              <td>group_id</td>
              <td>integer</td>
            </tr>
            <tr>
              <td>email</td>
              <td>string</td>
            </tr>
          </table>
      
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
+ For each test we use python client and curl myip.ipip.net with proxychains, assert if the result is similar with ipv4_ip/ping result.
+ Cron job could use Travis CI.
+ Captcha is required for launching jobs.
