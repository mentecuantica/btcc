@extends('layouts.app')

@section('content')

    <div class="header-content">
        <h2><i class="fa fa-home"></i>Dashboard <span>dashboard & statistics</span></h2>
        <div class="breadcrumb-wrapper hidden-xs">
            <span class="label">You are here:</span>
            <ol class="breadcrumb">
                <li class="active">Dashboard</li>
            </ol>
        </div>
    </div>

    <div class="body-content animated fadeIn">

        <div class="row">

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">

                <div class="mini-stat clearfix bg-bitbucket rounded">
                    <span class="mini-stat-icon"><i class="fa fa-suitcase fg-bitbucket"></i></span>
                    <div class="mini-stat-info">
                        <span>{{ $userInfo['wallet']['balance'] or '0.0' }} $ </span>
                        Balance
                    </div>
                </div>

            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">

                <div class="mini-stat clearfix bg-warning rounded">
                    <span class="mini-stat-icon"><i class="fa fa-calculator fg-bitbucket"></i></span>
                    <div class="mini-stat-info">
                        <span>{{ $userInfo['wallet']['transactionsCount'] or '0' }} </span>
                        Transaction Count
                    </div>
                </div>

            </div>
        </div>


    </div>

{{--
    @include('layouts._footer-admin')
--}}

@stop
