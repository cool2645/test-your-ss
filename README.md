**[Deprecated] This project is deprecated and no longer under maintainence. A new alternative to this project is [https://github.com/cool2645/ss-monitor](https://github.com/cool2645/ss-monitor), which is more stable and powerful.**

# Test Your Shadowsocks

A web interface that you can launch jobs testing your shadowsocks/shadowsocksR service.

The program has to work with a test host compatible with the api.

Currently available test host:

+ [test-your-ss-host](https://github.com/2645Corp/test-your-ss-host)

## About SS Config

The program supports several method of getting ss/ssr config.

+ Provide a **JSON FILE**
+ **Mu Api V2** (ss-panel v3)
+ 2645Network SSR

Since different site has their own (but not quite the same) SSR strategy.
We cannot provide a general ssr config method.
You're welcomed to add your ssr mu config method. Just open a pull request.

## About Docker

We currently have those docker images that could be used:

+ [cool2645/shadowsocks-pip](https://hub.docker.com/r/cool2645/shadowsocks-pip/)
+ [cool2645/shadowsocksr-master](https://hub.docker.com/r/cool2645/shadowsocksr-master/)

You're welcomed to make your own docker image.

A sufficient docker should have the following commands available for `/bin/sh`

+ sslocal
+ proxychains
+ curl
