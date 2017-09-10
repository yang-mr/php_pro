<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <link href="{{ asset('css/webcome.css') }}" rel="stylesheet">
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <script >
            $(function() {
                $('#bt_attention').click(function() {
                    var hint = $('#bt_attention').text();
                     if (hint == '取消关注') {
                            $.get('../cancel_attention/{{ $id }}', function(data, status){
                             //   alert(data + "--" + status);
                                if ('success' == status) {
                                    if (data == 1) {
                                        $('#bt_attention').text('添加关注');
                                        return;
                                    }
                                }
                                alert('取消失败');
                            });
                    } else {
                        $.get('../attention/{{ $id }}', function(data, status){
                        if ('success' == status) {
                            if (data  > 0) {
                                $('#bt_attention').text('取消关注');
                                return;
                            }
                        }
                        alert('关注失败');
                    });
                    }
                });


            });


                function bt_attention(attention) {
                      
                }
        </script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel
                </div>
               
                    <div class="user_div">
                        <p>{{ $name }} </p>
                        <img src="{{ $img_avatar }}"/>
                        <div>
                            @if( $attention)
                            <button id='bt_attention' onClick="bt_attention( {{ $attention }})">取消关注</button>
                            @else 
                             <button id='bt_attention' onClick="bt_attention( {{ $attention }})">添加关注</button>
                            @endif
                            <button id='bt_sendemail'>发送邮件</button>
                        </div>
                    </div>
            </div>
        </div>
    </body>
</html>
