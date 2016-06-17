@extends('layouts.app')

@push('scripts')
<script src="/static/vendor/raphael.js"></script>
<script src="/static/vendor/treant/treant.js"></script>
@endpush

@push('stylesheets')
<link rel="stylesheet" href="/static/vendor/treant/treant.css">
@endpush


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('tree._binaryTreeChart')
            </div>

        </div>

    </div>

@endsection