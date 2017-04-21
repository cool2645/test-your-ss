@extends('master')

@section('content')

    <div class="container">
        <h1>Test Your SS</h1>
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="/">Launch a new Job</a></li>
            <li role="presentation"><a href="/status">Job Status</a></li>
            <li role="presentation"><a href="https://github.com/2645Corp/test-your-ss">About</a></li>
        </ul>

        <form class="form-horizontal" style="padding-top: 20px">
            <div class="form-group">
                <label for="runhost" class="col-sm-2 control-label">Run Host</label>
                <div class="col-sm-10" style="padding-top: 7px">
                    @if(isset($job->run_host))
                        <button class="btn btn-danger">{{ $job->run_host }}</button>
                    @else
                        <p>No Run Host Assigned Yet</p>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Log</label>
                <div class="col-sm-10" style="padding-top: 7px">
                    @if(isset($job->log))
                        <p>{{ $job->log }}</p>
                    @else
                        <p>No log currently.</p>
                    @endif
                </div>
            </div>
        </form>

    </div>

@endsection