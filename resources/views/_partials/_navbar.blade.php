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
       <li><a href="{!! url('/partner')  !!}" class="">View</a></li>
       <li><a href="{!! url('/partner/create')  !!}" class="">Create</a></li>
      </ul>
     </li>
     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
       Trees <span class="caret"></span>
      </a>

      <ul class="dropdown-menu" role="menu">
       <li><a href="{!! url('/tree/linear/index')  !!}" class="">Linear</a></li>
       <li><a href="{!! url('/tree/binary/index')  !!}" class="">Binary</a></li>
       <li><a href="{!! url('/tree/ternary/index')  !!}" class="">Ternary</a></li>
      </ul>
     </li>
     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
       Transactions <span class="caret"></span>
      </a>

      <ul class="dropdown-menu" role="menu">
       <li><a href="{!! url('/transactions/index')  !!}" class="">List</a></li>
       <li><a href="{!! url('/transactions/refund')  !!}" class="">Refund</a></li>
       <li><a href="{!! url('/transactions/withdraw')  !!}" class="">{!! trans('Withdraw') !!}</a></li>
       <li><a href="{!! url('/transactions/transfer')  !!}" class="">{!! trans('transfer') !!}</a></li>
      </ul>
     </li>
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
       {{ Auth::getUser()->email }} <span class="caret"></span>
      </a>

      <ul class="dropdown-menu" role="menu">
       {{--    <li><a href="{{ url('/invite') }}"><i class="fa fa-btn fa-sign-out"></i>Invite</a></li>--}}
       <li><a href="{{ url('/account') }}"><i class="fa fa-btn fa-sign-out"></i>Account</a></li>
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
   <currencyinformer></currencyinformer>
 </div>
</div>