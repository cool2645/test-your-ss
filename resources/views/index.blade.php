@extends('master')

@section('content')

    <div class="container col-md-6 col-md-offset-3">
        <h1>Test Your SS</h1>
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#">Launch a new Job</a></li>
            <li role="presentation"><a href="/status">Job Status</a></li>
            <li role="presentation"><a href="/about">About</a></li>
        </ul>
        <form class="form-horizontal">
            <div class="form-group" style="margin-top: 20px">
                <div class="col-sm-12">
                    <select id="config-select" class="form-control">
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
                        <input type="text" class="form-control" id="website" placeholder="The http/https must be correct! Eg. https://ss-panel.org/mu">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="selectNode" class="col-sm-2 control-label">Node</label>
                    <div class="col-sm-10">
                        <select id="selectNode" class="form-control">
                        </select>
                    </div>
                </div>
            </section>
            <section id="config-box-json" style="display: none">
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="3" placeholder="Paste your json config here"></textarea>
                    </div>
                </div>
            </section>
            <div class="form-group">
                <label for="selectDocker" class="col-sm-2 control-label">Docker</label>
                <div class="col-sm-10">
                    <select id="selectDocker" class="form-control">
                        <option>SS</option>
                        <option>SSR</option>
                    </select>
                </div>
            </div>
        </form>

    </div>

    <script>
        $(document).ready(function () {
            $("#config-select").change(function () {
                if($("#config-select").val() == "mu_api_v2") {
                    $("#config-box-mu").css("display", "initial");
                    $("#config-box-json").css("display", "none");
                }
                else if($("#config-select").val() == "2645network_ssr") {
                    $("#config-box-mu").css("display", "initial");
                    $("#config-box-json").css("display", "none");
                }
                else if($("#config-select").val() == "json") {
                    $("#config-box-mu").css("display", "none");
                    $("#config-box-json").css("display", "initial");
                }
            });
        })
    </script>

@endsection