@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1>Verify</h1>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <hr />

                <form action="{{ route('performVerify') }}" method="POST">
                    <div class="form-group">
                        {{ csrf_field() }}

                        <label for="verification">Verification Code</label>
                        <input type="text" name="verification" id="verification" class="form-control"
                               placeholder="123456"
                               aria-describedby="The verification code.">
                        <small id="helpId" class="text-muted">Enter the verification code here.</small>
                    </div>
                    <div class="form-group">
                        <input name="submit" id="submit" class="btn btn-primary" type="submit" value="Verify Code">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
