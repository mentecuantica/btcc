@extends('layouts.app')

@section('content')
<section id="page-content">

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

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="mini-stat clearfix bg-bitbucket rounded">
                    <span class="mini-stat-icon"><i class="fa fa-bitbucket fg-bitbucket"></i></span>
                    <div class="mini-stat-info">
                        <span>8,932</span>
                        Repository
                    </div>
                </div>
            </div>
        </div>


    </div>

    @include('layouts._footer-admin')

</section>
@stop
