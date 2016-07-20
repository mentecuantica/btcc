@extends('layouts.app')



@section('content')
<div class="container">
    <div class="row">
        <h2>Packages available</h2>
        @forelse($packages as $package)

            <li>{{$package->name}}</li>
        @empty
            <p>No Packages</p>
        @endforelse
    </div>
</div>




@endsection