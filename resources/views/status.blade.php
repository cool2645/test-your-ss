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
                            <button class="btn btn-danger">Rejudge</button>
                        </td>
                        <td>
                            <button class="btn btn-danger">Rerun</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        {{ $jobs->links() }}

    </div>

@endsection