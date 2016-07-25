<nav class="navbar navbar-default navbar-static-top">
 <div class="container">
  <div class="navbar-header">

   <!-- Collapsed Hamburger -->
   <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
           data-target="#app-navbar-collapse">
    <span class="sr-only">Toggle Navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
   </button>

   <!-- Branding Image -->
   <a class="navbar-brand" href="{{ url('/') }}">
    Btcc
   </a>
    <currencyinformer></currencyinformer>
  </div>

  <div class="collapse navbar-collapse" id="app-navbar-collapse">
   <!-- Left Side Of Navbar -->
   <ul class="nav navbar-nav">
    @if (\Auth::getUser())

     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
       Partners <span class="caret"></span>
      </a>

      <ul class="dropdown-menu" role="menu">
       <li><a href="{!! url('/partner')  !!}" >View</a></li>
       <li><a href="{!! url('/partner/create')  !!}" >Create</a></li>
      </ul>
     </li>
     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
       Users <strong>({{ $stat['registeredUsers']  }})</strong> <span class="caret"></span>
      </a>

      <ul class="dropdown-menu" role="menu">
       <li><a href="{!! url('/tree/linear/index')  !!}" >Linear</a></li>
       <li><a href="{!! url('/tree/binary/index')  !!}" >Binary</a></li>
       <li><a href="{!! url('/tree/ternary/index')  !!}" >Ternary</a></li>
      </ul>
     </li>
     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
       Transactions <span class="caret"></span>
      </a>

      <ul class="dropdown-menu" role="menu">
       <li><a href="{!! url('/transaction')  !!}" >List</a></li>
       <li><a href="{!! url('/transaction/refund')  !!}" >Refund</a></li>
       <li><a href="{!! url('/transaction/withdraw')  !!}" >{!! trans('Withdraw') !!}</a></li>
       <li><a href="{!! url('/transaction/transfer')  !!}" >{!! trans('transfer') !!}</a></li>
      </ul>
     </li>
     <li><a href="{{ route('supportTicket.index') }}">Support</a></li>
     <li><a href="{{ url('/faq') }}">FAQ</a></li>
    @endif
   </ul>

   <!-- Right Side Of Navbar -->
   <ul class="nav navbar-nav navbar-right">
    <!-- Authentication Links -->
    @if (Auth::guest())
     <li><a href="{{ url('/login') }}">Login</a></li>

    @else
     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
       {{ Auth::getUser()->email }} ({{ $wallet['balance'] or '0.0' }} $), {{ $profile['package']->name }}   <span class="caret"></span>
      </a>

      <ul class="dropdown-menu" role="menu">
       {{--    <li><a href="{{ url('/invite') }}"><i class="fa fa-btn fa-sign-out"></i>Invite</a></li>--}}
       <li><a href="{{ url('/account') }}"><i class="fa fa-btn fa-sign-out"></i>Account</a></li>
       <li><a href="{{ url('/account/profile') }}"><i class="fa fa-btn fa-sign-out"></i>Profile</a></li>
       <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
      </ul>
     </li>
    @endif
   </ul>
  </div>


 </div>
</nav>
<div class="container">
 <div id="widgets">

 </div>
</div>