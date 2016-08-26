@extends('layouts.app')

@section('content')
    <section id="page-content">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="page-header">
                    <h1>Account<small>{{ $user->email }}</small></h1>
                </div>


                <table class="table table-striped table-hover table-bordered dashboard-table">
                     <tr>
                        <th>{{ trans('name') }}</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('email') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('Registered') }}</th>
                        <td>{{ $user->created_at }} ({{ $user->created_at->diffForHumans() }})</td>
                    </tr>
                    <tr>
                        <th>{{ trans('last_updated') }}</th>
                        <td>{{ $user->updated_at }} ({{ $user->updated_at->diffForHumans() }})</td>
                    </tr>
                    <tr>
                        <th>{{ trans('Actions') }}</th>
                        <td>
                            {{ link_to_route('account.profile', trans('update profile'), [], ['class' => 'btn btn-primary btn-xs']) }}

                            {{ link_to_route('account.password', trans('change password'), [], ['class' => 'btn btn-warning btn-xs']) }}
                        </td>
                    </tr>
                </table>
            </div>



        </div>
    </div>
    </section>
@endsection