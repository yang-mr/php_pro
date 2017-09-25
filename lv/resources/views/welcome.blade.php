@extends('layouts.auto_app')

<link href="{{ asset('/css/index.css') }}" rel="stylesheet" type="text/css">
@section('left_content')
    <nav id="nav_left">
        <div class="inner">
            fdfd
        </div>
    </nav>
@endsection

@section('content')
    <div class="content">
            <form>
        <div id="search_head">
                <div class='sub_head'>
                    选择性别：
                    <select name="sex" id="sex" style="color:#666;">
                        <option value='0'>男</option>
                        <option value='1'>女</option>
                    </select>
                </div>
                   <div class='sub_head'>
                    年龄<select name="height" id="height" style="color:#666;" >
                            <option label="130" value="130">130</option>
                            <option label="131" value="131">131</option>
                            <option label="132" value="132">132</option>
                            <option label="215" value="215">215</option>
                            <option label="216" value="216">216</option>
                            <option label="217" value="217">217</option>
                            <option label="218" value="218">218</option>
                            <option label="219" value="219">219</option>
                            <option label="220" value="220">220</option>
                            <option label="221" value="221">221</option>
                            <option label="222" value="222">222</option>
                            <option label="223" value="223">223</option>
                            <option label="224" value="224">224</option>
                            <option label="225" value="225">225</option>
                            <option label="226" value="226">226</option>
                        </select>&nbsp;厘米
                    </div>
                    <div class="sub_head">
                        <a href="javascript:;">搜索</a>
                    </div>
        </div>
                    </form>

        <div id="content_area">
             @foreach ($users as $user)
            <a href="{{ route('user_desc', $user['id']) }}">
                <div class="user_div">
                <img src="{{ $user->avatar_url or asset('img/default_avatar.png') }}"/>
                <p>{{ $user->name}} {{ $user->sex }} {{ $user->city}} {{ $user->area}}</p>
                </div>
            </a>
            @endforeach
        </div>
        <div class="paginate">
            {{$users->links()}}
        </div>
</div>
@endsection
@section('right_content')
    <div class="content">
       
</div>
@endsection
