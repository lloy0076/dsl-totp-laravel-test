@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h1>Dashboard</h1>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <hr />

                <form action="{{ route('generateToken') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <input name="submit" id="submit" class="btn btn-primary" type="submit" value="Generate Token">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
