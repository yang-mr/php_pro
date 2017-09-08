<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
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

            form textarea {
                width: 200px;

            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    编辑资料
                </div>
               <!--  @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif -->
                @if ($errors->has('error'))
                        <span class="help-block">
                            <strong>{{ $errors->first('error') }}</strong>
                        </span>
                @endif
                <div class="register_content">
                    <form action="./commit_msg" method="post">
                    {{ csrf_field() }}
                        <fieldset> 
                        <legend>
                            <span>Sign In for Code and Updates</span>
                        </legend> 
                        <section>
                            <label for="description">自我评价</label>
                            <textarea name="description" rows="" cols="" >
                            {{ old('description') }}
                            </textarea>
                             @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                            @endif
                        </section>
                         <section>
                            <label for="requist">要求</label>
                            <input type="text" name="requist" value="{{ old('requist') }}" required autofocus>
                            @if ($errors->has('requist'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('requist') }}</strong>
                                    </span>
                            @endif
                        </section>
                         <section>
                            <input type="submit" name="password" value="注册"/>
                                <p class="signup">Not signed in? 
                                    <a href="#">登录! </a>
                               </p>
                        </section>
                        </fieldset>
                    </form>
                </div>

                 <div class="upload_avator">
                    <form action="./upload_avatar" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <fieldset> 
                        <legend>
                            <span>Sign In for Code and Updates</span>
                        </legend> 
                        <section>
                            <img src="{{ asset('img/default_avator.png') }}">
                            <input name="file" type="file">
                             @if ($errors->has('file'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('file') }}</strong>
                                    </span>
                            @endif
                        </section>
                         <section>
                            <input type="submit" name="password"  value="上传头像"/>
                        </section>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
