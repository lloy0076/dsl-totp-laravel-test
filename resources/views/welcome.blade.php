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
                    <form action="{{ route('generateToken') }}" method="GET">
                        <div class="form-check">
                            <input type="checkbox" name="keep_others" id="keep_others" class="form-check-input">
                            <label class="form-check-label" for="keep_others">Keep Others</label>
                        </div>
                        <div class="form-group">
                            <input name="submit" id="submit" class="btn btn-primary" type="submit"
                                   value="Generate Token">
                        </div>
                    </form>
                </div>

                <hr/>

                <div style="font-size: smaller;">
                    Copyright &copy; 2020. David Lloyd&nbsp;&lt;<a href="mailto:lloy006 [at] adam.com
                    .au>">lloy0076&nbsp;[at]&nbsp;adam.com.au</a>&gt;.
                </div>
            </div>
        </div>
    </div>
@endsection
