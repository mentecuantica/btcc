@extends('layouts.app')




@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('tree._binaryTreeChart',['parent'=>user()])

            </div>

        </div>

    </div>

@endsection