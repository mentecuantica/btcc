@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('tree.binary._binaryTreeChart')
            </div>

        </div>

    </div>

@endsection