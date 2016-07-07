@extends('layouts.app')




@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Fake data: {!! $tree !!}</h2>
                <div class="well"> {!! $tags !!}</div>

            </div>

        </div>

    </div>

@endsection