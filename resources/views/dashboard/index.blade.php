@extends('layouts.app')



@section('content')


    @forelse($packages as $package)

        <li>{{$package->name}}</li>
    @empty
            <p>No Packages</p>
            @endforelse

@endsection