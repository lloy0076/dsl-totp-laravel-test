@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Dashboard</h1>
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

                <hr/>

                <div>
                    <a href="{{ route('generateToken') }}" class="btn btn-primary">Generate Token</a>
                </div>

                <hr />

                <div style="font-size: smaller;">
                    Copyright &copy; 2020. David Lloyd&nbsp;&lt;<a href="mailto:lloy006 [at] adam.com
                    .au>">lloy0076&nbsp;[at]&nbsp;adam.com.au</a>&gt;.
                </div>
            </div>
        </div>
    </div>
@endsection
