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

                <hr />

                <form action="{{ route('generateToken') }}" method="GET">
                    <div class="form-group">
                        <label for="clear_others">Clear Others</label>
                        <input type="checkbox" name="clear_others" id="clear-others" class="form-control"
                               aria-describedby="clear_others" checked="checked">
                        <small id="clear_others" class="text-muted">Clear Other Sanctum Tokens?</small>
                    </div>
                    <div class="form-group">
                        <input name="submit" id="submit" class="btn btn-primary" type="submit" value="Generate Token">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
