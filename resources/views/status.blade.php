@extends('master')

@section('content')

    <div class="container">
        <h1>Test Your SS</h1>
        <ul class="nav nav-tabs">
            <li role="presentation"><a href="/">Launch a new Job</a></li>
            <li role="presentation" class="active"><a href="#">Job Status</a></li>
            <li role="presentation"><a href="https://github.com/2645Corp/test-your-ss">About</a></li>
        </ul>
        <div class="table-responsive" style="padding-top: 20px">
            <table class="table table-hover">
                <tr>
                    <th>Run ID</th>
                    <th>Node Addr</th>
                    <th>Port</th>
                    <th>Docker</th>
                    <th>Status</th>
                    <th>Rejudge</th>
                    <th>Rerun</th>
                </tr>
                @foreach ($jobs as $job)
                    <tr>
                        <td>{{ $job->id }}</td>
                        <td>{{ $job->node_addr }}</td>
                        <td>{{ $job->port }}</td>
                        <td>{{ $job->docker }}</td>
                        <td>
                            @if($job->status == "Queuing")
                                <a href="/status/{{ $job->id }}" class="btn btn-info">Queuing</a>
                            @elseif($job->status == "Starting")
                                <a href="/status/{{ $job->id }}" class="btn btn-warning">Starting</a>
                            @elseif($job->status == "Running")
                                <a href="/status/{{ $job->id }}" class="btn btn-warning">Running</a>
                            @elseif($job->status == "Pending")
                                <a href="/status/{{ $job->id }}" class="btn btn-warning">Pending</a>
                            @elseif($job->status == "Passing")
                                <a href="/status/{{ $job->id }}" class="btn btn-success">Passing</a>
                            @elseif($job->status == "Failing")
                                <a href="/status/{{ $job->id }}" class="btn btn-danger">Failing</a>
                            @else
                                <a href="/status/{{ $job->id }}" class="btn btn-default">Undefined</a>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="rejudge({{ $job->id }})">Rejudge</button>
                        </td>
                        <td>
                            <button class="btn btn-danger" onclick="rerun({{ $job->id }})">Rerun</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{ $jobs->links() }}

    </div>

    <script>
        function rejudge(id) {
            $.ajax({
                url: "/api/jobs/" + id + '/judge',
                method: "GET",
            })
        }
        function rerun(id) {
            $.ajax({
                url: "/api/jobs/" + id,
                data: "_method=PUT",
                method: "POST",
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if (dataObj == null) {
                        $(".alert span").html("A exception occurred.");
                        $(".alert").show();
                    }
                    else if (!dataObj.result) {
                        $(".alert span").html(dataObj.msg);
                        $(".alert").show();
                    }
                    else {
                        history.go(0)
                    }
                }
            })
        }
    </script>
@endsection