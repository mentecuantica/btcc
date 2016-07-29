<aside id="sidebar-left" class="sidebar-circle">

    <div class="sidebar-content">
        <div class="media">
            {{--<a class="pull-left has-notif avatar" href="{{url('page/profile')}}">
                <img src="http://img.djavaui.com/?create=50x50,4888E1?f=ffffff" alt="admin">
                <i class="online"></i>
            </a>--}}
            <div class="media-body">
                <h4 class="media-heading">Profit: <span> {{ $userInfo['wallet']['balance'] or '0.0' }} $ </span></h4>
                <small>some text</small>
            </div>
        </div>
    </div>
    <ul class="sidebar-menu">

        <li {!! Request::is('dashboard', 'dashboard/index') ? 'class="active"' : NULL !!}>
            <a href="{{url('dashboard/index')}}">
                <span class="icon"><i class="fa fa-home"></i></span>
                <span class="text">Dashboard</span>
                {!! Request::is('dashboard', 'dashboard/index') ? '<span class="selected"></span>' : NULL !!}
            </a>
        </li>

        <li class="sidebar-category">
            <span>Partners</span>
            <span class="pull-right"><i class="fa fa-trophy"></i></span>
            <span class="label label-danger pull-right rounded">{{ $userInfo['stat']['registeredUsers']  }}</span>

        </li>


        <li><a href="{!! url('/partner')  !!}" >View</a></li>
        <li><a href="{!! url('/partner/create')  !!}" >Create</a></li>

        <li {!! Request::is('table','table/*')? 'class="submenu active"' : 'class="submenu"' !!}>
            <a href="javascript:void(0);">
                <span class="icon"><i class="fa fa-table"></i></span>
                <span class="text">Users</span>
                <span class="plus"></span>
                {!! Request::is('tree', 'tree/*') ? '<span class="selected"></span>' : NULL !!}
            </a>
            <ul>
                <li {!! Request::is('tree','tree/linear')? 'class="active"' : NULL !!}><a
                            href="{{url('tree/linear')}}">Linear</a></li>
                <li {!! Request::is('tree','tree/binary')? 'class="active"' : NULL !!}><a
                            href="{{url('tree/binary')}}">Binary</a></li>
                <li {!! Request::is('tree','tree/ternary')? 'class="active"' : NULL !!}><a
                            href="{{url('tree/ternary')}}">Ternary</a></li>
            </ul>
        </li>


        <li {!! Request::is('layout','layout/*')? 'class="submenu active"' : 'class="submenu"' !!}>
            <a href="javascript:void(0);">
                <span class="icon"><i class="fa fa-columns"></i></span>
                <span class="text">Wallet</span>
                <span class="plus"></span>
                {!! Request::is('layout', 'layout/*') ? '<span class="selected"></span>' : NULL !!}
            </a>
            <ul>
                <li {!! Request::is('layout','layout/sidebar-box')? 'class="active"' : NULL !!}>{{ link_to_route('transaction.index','View') }}</li>
                <li {!! Request::is('layout','layout/sidebar-rounded')? 'class="active"' : NULL !!}><a href="{!! url('/transaction/refund')  !!}" >Refund</a></li>
                <li {!! Request::is('layout','layout/sidebar-circle')? 'class="active"' : NULL !!}><a href="{!! url('/transaction/withdraw')  !!}" >{!! trans('Withdraw') !!}</a></li>
                <li {!! Request::is('layout','layout/footer-fixed')? 'class="active"' : NULL !!}><a href="{!! url('/transaction/transfer')  !!}" >{!! trans('transfer') !!}</a></li>
            </ul>
        </li>


        <li class="sidebar-category">
            <span>Support</span>
            <span class="pull-right"><i class="fa fa-cubes"></i></span>
        </li>

        <li><a href="{{ url('/faq') }}">FAQ</a></li>
        <li {!! Request::is('widget','widget/misc')? 'class="active"' : NULL !!}>
            <a href="{{ route('supportTicket.index') }}">
                <span class="icon"><i class="fa fa-puzzle-piece"></i></span>
                <span class="text">Tickets</span>

                {!! Request::is('widget', 'widget/misc') ? '<span class="selected"></span>' : NULL !!}
            </a>
        </li>


    </ul>

    <div class="sidebar-footer hidden-xs hidden-sm hidden-md">
        <a id="setting" class="pull-left" href="javascript:void(0);" data-toggle="tooltip" data-placement="top"
           data-title="Setting"><i class="fa fa-cog"></i></a>
        <a id="fullscreen" class="pull-left" href="javascript:void(0);" data-toggle="tooltip" data-placement="top"
           data-title="Fullscreen"><i class="fa fa-desktop"></i></a>
        <a id="lock-screen" data-url="lock-screen" class="pull-left" href="javascript:void(0);" data-toggle="tooltip"
           data-placement="top" data-title="Lock Screen"><i class="fa fa-lock"></i></a>
        <a id="logout" data-url="signin" class="pull-left" href="javascript:void(0);" data-toggle="tooltip"
           data-placement="top" data-title="Logout"><i class="fa fa-power-off"></i></a>
    </div>

</aside>
