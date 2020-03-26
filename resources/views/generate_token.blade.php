@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <h1>Generated Token</h1>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <hr/>

                {{ $newToken ?? 'No Token Presetn' }}

                <br >

                <div style="font-size: smaller;" class="text-center"><a href="{{ route('home') }}">Home</a></div>
            </div>
        </div>
    </div>
@endsection
