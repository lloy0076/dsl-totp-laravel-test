@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Info</h1>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Sanctum Token</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td scope="row">{{ $token}}</td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Label</th>
                        <th>Now</th>
                        <th>Secret</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td scope="row">{{ $label }}</td>
                        <td>{{ $now }}</td>
                        <td>{{ $secret }}</td>
                    </tr>
                    <tr>
                        <td scope="row" colspan="3">{{ $provisioning_uri }}</td>
                    </tr>
                    </tbody>
                </table>
                <div style="font-size: smaller;" class="text-center"><a href="{{ route('home') }}">Home</a></div>
            </div>
        </div>
    </div>
@endsection
