<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auto_app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script>
         $(function() {
                Pusher.logToConsole = true;
                var pusher = new Pusher('02a1cc0f2b863b11a348', {
                  cluster: 'eu',
                  encrypted: true
                });

                var channel = pusher.subscribe('UserAttention.' + {{ auth()->user()->id }});
                channel.bind('pusher:subscription_succeeded', function(data) {
                 // alert(data.message);
                });
                channel.bind('App\\Events\\AttentionEvent', function(data) {
                  alert(data.name);
                });
        });
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>
                  
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                      <ul class="nav navbar-nav navbar-right">
                        <li><a href="{{ route('index') }}">首页</a></li>
                        <li><a href="{{ route('home') }}" id="person_center">个人中心</a></li>
                        <li><a href="#">1111</a></li>
                        <li><a href="#">1111</a></li>
                        <li><a href="#">1111</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="left_content">
            @yield('left_content')
        </div>
        <dir id="content">
            @yield('content')
        </dir>
         <dir id="right_content">
            @yield('right_content')
        </dir>
        <div id="clear"></div>
            <footer>
                我是底部的。。。
            </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
