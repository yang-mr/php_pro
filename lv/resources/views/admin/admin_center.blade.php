<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>管理员中心</title>

    <!-- Styles -->
    <link href="{{ asset('css/admin_center.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/admin_center.js') }}"></script>
</head>
<body>
    <div id="app">
        <header>
            <div class="inner">
                <p>管理员中心</p>
                <a href="#">退出</a>
            </div>            
        </header>
        <nav>
           <div class="inner">
               <ul>
                   <li><a href="{{ route('admin_center') }}">所有用户</a></li>
                   <li><a href="{{ route('admin_vip') }}">VIP服务</a></li>
               </ul>
           </div>
        </nav>
        <div id="content">
            @if(!empty($users))
            <div id="content_users">
                @foreach ($users as $user)
                <li>{{ $user['name'] }}</li>
                @endforeach
            </div>
            @else
            <div id="content_vips">
                @foreach ($vips as $vip)
                    <div class="vip">
                        <p>{{ $vip['title'] }}</p>
                        <div class="price_and_discount">
                            {{ $vip['price'] }} {{ $vip['discount'] }}
                        </div>
                        <p>{{ $vip['description'] }}</p>
                    </div>
                @endforeach
                 <div id="pull_right">
                       <div class="pull-right">
                          {{ $vips->links() }}
                       </div>
                 </div>
            </div>
            @endif
        </div>
    </div>
</body>
</html>

