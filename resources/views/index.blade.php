@extends('master')

@section('content')

    <div class="container col-md-6 col-md-offset-3">
        <h1>Test Your SS</h1>
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#">Launch a new Job</a></li>
            <li role="presentation"><a href="/status">Job Status</a></li>
            <li role="presentation"><a href="/about">About</a></li>
        </ul>
        <form class="form-horizontal" style="padding-top: 20px">
            <div class="alert alert-warning fade in" style="display: none;">
                <a href="#" class="close" onclick="$('.alert').hide()">&times;</a>
                <span><strong>Warning!</strong> There was a problem with your network connection.</span>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <select id="selectConfig" class="form-control">
                        <option value="mu_api_v2" selected>Mu Api v2</option>
                        <option value="2645network_ssr">2645Network SSR</option>
                        <option value="json">Json config</option>
                    </select>
                </div>
            </div>
            <section id="config-box-mu">
                <div class="form-group">
                    <label for="website" class="col-sm-2 control-label">API URL</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="website" placeholder="Eg. https://ss-panel.org/api The http/https must be correct!">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="selectNode" class="col-sm-2 control-label">Node</label>
                    <div class="col-sm-4">
                        <button type="button" id="refresh-node-list-btn" class="btn btn-primary form-control">Refresh Node List</button>
                    </div>
                    <div class="col-sm-6">
                        <select id="selectNode" class="form-control">
                        </select>
                    </div>
                </div>
            </section>
            <section id="config-box-json" style="display: none">
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea id="json" class="form-control" rows="3" placeholder="Paste your json config here"></textarea>
                    </div>
                </div>
            </section>
            <div class="form-group">
                <label for="selectDocker" class="col-sm-2 control-label">Docker</label>
                <div class="col-sm-10">
                    <select id="selectDocker" class="form-control">
                        <option value="cool2645/shadowsocks-pip">cool2645/shadowsocks-pip</option>
                        <option value="cool2645/shadowsocksr-master">cool2645/shadowsocksr-master</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-4 col-sm-offset-4">
                <button id="launch-btn" type="button" class="btn btn-primary form-control">Launch a test Job</button>
            </div>
        </form>

    </div>

    <script>
        $(document).ready(function () {
            $("#selectConfig").change(function () {
                if($("#selectConfig").val() == "mu_api_v2") {
                    $("#config-box-mu").css("display", "initial");
                    $("#config-box-json").css("display", "none");
                }
                else if($("#selectConfig").val() == "2645network_ssr") {
                    $("#config-box-mu").css("display", "initial");
                    $("#config-box-json").css("display", "none");
                }
                else if($("#selectConfig").val() == "json") {
                    $("#config-box-mu").css("display", "none");
                    $("#config-box-json").css("display", "initial");
                }
            });
            $("#refresh-node-list-btn").click(function () {
                $.ajax({
                    url: "/node/" + $("#selectConfig").val(),
                    method: "POST",
                    data: {
                        website: $("#website").val(),
                        email: $("#email").val(),
                        password: $("#password").val()
                    },
                    success: function (msg) {
                        var dataObj = eval("(" + msg + ")");
                        if(dataObj == null) {
                            $(".alert span").html("A exception occurred, you might inputted a wrong api url.");
                            $(".alert").show();
                        }
                        if(!dataObj.result) {
                            $(".alert span").html(dataObj.msg);
                            $(".alert").show();
                        }
                        else {
                            $("#selectNode").html('');
                            for(i in dataObj.data) {
                                $("#selectNode").append("<option value=" + dataObj.data[i].node_name + ":" + dataObj.data[i].node_method + ">" + dataObj.data[i].node_name + "</option>");
                            }
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status == 422) {
                            $(".alert span").html("You should check in on some of those fields below.");
                            $(".alert").show();
                        } else {
                            $(".alert span").html("Undefined error.");
                            $(".alert").show();
                        }
                    }
                })
            });
            $("#launch-btn").click(function () {
                $("#launch-btn").html("Launching...");
                $("#launch-btn").attr('disabled', 'disabled');
                $.ajax({
                    url: "/api/jobs",
                    method: "POST",
                    data: {
                        config: $("#selectConfig").val(),
                        website: $("#website").val(),
                        email: $("#email").val(),
                        password: $("#password").val(),
                        node: $("#selectNode").val(),
                        json: $("#json").val(),
                        docker: $("#selectDocker").val()
                    },
                    success: function (msg) {
                        var dataObj = eval("(" + msg + ")");
                        if(dataObj == null) {
                            $(".alert span").html("A exception occurred, you might inputted a wrong api url.");
                            $(".alert").show();
                            $("#launch-btn").html("Launch a test Job");
                            $("#launch-btn").removeAttr('disabled');
                        }
                        if(!dataObj.result) {
                            $(".alert span").html(dataObj.msg);
                            $(".alert").show();
                            $("#launch-btn").html("Launch a test Job");
                            $("#launch-btn").removeAttr('disabled');
                        }
                        else {
                            window.location.href = "/status";
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status == 422) {
                            $(".alert span").html("You should check in on some of those fields below.");
                            $(".alert").show();
                            $("#launch-btn").html("Launch a test Job");
                            $("#launch-btn").removeAttr('disabled');
                        } else {
                            $(".alert span").html("Undefined error.");
                            $(".alert").show();
                            $("#launch-btn").html("Launch a test Job");
                            $("#launch-btn").removeAttr('disabled');
                        }
                    }
                })
            });
        })
    </script>

@endsection