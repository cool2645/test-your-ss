@extends('master')

@section('content')

    <div class="container col-md-6 col-md-offset-3">
        <h1>Test Your SS</h1>
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="#">Launch a new Job</a></li>
            <li role="presentation"><a href="#">Job Status</a></li>
            <li role="presentation"><a href="#">About</a></li>
        </ul>
        <form class="form-horizontal">
            <div class="form-group" style="margin-top: 20px">
                <div class="col-sm-12">
                    <select class="form-control">
                        <option>mu api v2</option>
                        <option>json config</option>
                    </select>
                </div>
            </div>
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
            <div class="form-group">
                <div class="col-sm-12">
                    <textarea class="form-control" rows="3" placeholder="Paste your json config here"></textarea>
                </div>
            </div>
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

@endsection