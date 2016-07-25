@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="page-header">
                    <h1>Profile<small>{{ $user->email }}</small></h1>
                </div>

            </div>
            @include('account._profileForm')

        </div>
    </div>

@endsection