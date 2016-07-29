<header id="header">
    <!-- Start header left -->
    <div class="header-left">
        <div class="navbar-minimize-mobile left">
            <i class="fa fa-bars"></i>
        </div>
        <div class="navbar-header">
            <a class="navbar-brand" href="{{url('/dashboard')}}">BTCC</a>
        </div>

        <div class="navbar-minimize-mobile right">
            <i class="fa fa-cog"></i>
        </div>

        <div class="clearfix"></div>
    </div><!-- /.header-left -->

    <div class="header-right">
        <div class="navbar navbar-toolbar">

            <ul class="nav navbar-nav navbar-left">
                <li class="navbar-minimize">
                    <a href="javascript:void(0);" title="Minimize sidebar">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>

                <currencyinformer></currencyinformer>
            </ul>

            <ul class="nav navbar-nav navbar-right"><!-- /.nav navbar-nav navbar-right -->


                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                @else
                <li class="dropdown navbar-profile">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="meta">
                            <span class="avatar">
                                {{ $userInfo['wallet']['balance'] or '0.0' }} $ {{ $userInfo['profile']['package']->name }}
                            </span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated flipInX">
                        <li class="dropdown-header"> {{ Auth::getUser()->email }} </li>
                        <li><a href="{{ url('/account') }}"><i class="fa fa-user"></i>Account</a></li>
                        <li><a href="{{ url('/account/profile') }}"><i class="fa fa-btn"></i>Profile</a></li>
                        <li>{{ link_to_route('account.password','Change password') }}</li>

                        <li class="dropdown-header"><i class="fa fa-dollar"></i> {{ $userInfo['profile']['package']->name }} </li>
                        <li class="divider"></li>
                        <li><a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i>Logout</a></li>
                    </ul>
                </li>
                @endif
            </ul>

        </div><!-- /.navbar-toolbar -->
    </div><!-- /.header-right -->

</header> <!-- /#header -->
