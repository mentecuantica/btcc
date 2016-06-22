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
                <h2>Fake data: {!! $tree !!}</h2>
                <div class="well"> {!! $tags !!}</div>

            </div>

        </div>

    </div>

@endsection