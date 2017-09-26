@extends('layouts.auto_app')

<script type="text/javascript">
    function userDesc(path) {
        window.location.href=path;
    }

    function form_search() {
         $.ajax({
                cache: true,
                type: "POST",
                url:"{{ route('user_search') }}",
                data:$('#form_search').serialize(),// 你的formid
                async: false,
                dataType: 'json',
                error: function(request) {
                  alert('修改失败');
                },
                success: function(data) {
                  if (data.status == 1) {
                    //提交成功
                  
                  } else if (data.status == 0) {
                    alert("搜索失败");
                  } else if (data.status == 2) {
                    //补全资料
                  }
                }
            });
    }
</script>
<link href="{{ asset('/css/index.css') }}" rel="stylesheet" type="text/css">
@section('left_content')
    <div class="welcome_left_content">
        <div class="nav_top">
            <a href="./home/edit_msg"><img src="{{ $user_avatar or asset('img/default_avatar.png') }}" onClick="go_personcenter()"/></a>
            <div class="user_description">
                <strong>征友进行中</strong><a href="{{ route('base_mean') }}">修改</a>
            </div>
       </div>
       <div class="nav_bottom">
           <p>服务中心 ></p>
           <p><a href="{{ route('gift_index')}}" class="gift_enter">礼物商城</a>
            <a href="{{ route('vip_index') }}">充值vip</a></p>
       </div>
    </div>
@endsection

@section('content')
    <div class="content">
            <form id="form_search">
             {{ csrf_field() }}
        <div id="search_head">
                <div class='sub_head'>
                    选择性别：
                    <select name="sex" id="sex" style="color:#666;">
                        <option value='0'>男</option>
                        <option value='1'>女</option>
                    </select>
                </div>
                   <div class='sub_head'>
                    年龄&nbsp;<select name="min_age" id="height" style="color:#666;" >
                            <option label="130" value="18">18</option>
                            <option label="131" value="19">19</option>
                            <option label="132" value="20">20</option>
                            <option label="215" value="21">21</option>
                            <option label="216" value="21">21</option>
                            <option label="217" value="23">23</option>
                            <option label="218" value="24">24</option>
                            <option label="220" value="26">26</option>
                            <option label="221" value="27">27</option>
                        </select>&nbsp;--
                        <select name="age_max" id="height" style="color:#666;" >
                            <option label="130" value="18">18</option>
                            <option label="131" value="19">19</option>
                            <option label="132" value="20">20</option>
                            <option label="215" value="21">21</option>
                            <option label="216" value="21">21</option>
                            <option label="217" value="23">23</option>
                            <option label="218" value="24">24</option>
                            <option label="220" value="26">26</option>
                            <option label="221" value="27">27</option>
                        </select>
                    </div>
                    <div class="sub_head">
                        <a href="javascript:;" onClick="form_search()">搜索</a>
                    </div>
        </div>
                    </form>

        <div id="content_area">
             @foreach ($users as $user)
                <div class="user_div" onClick="userDesc('{{ route('user_desc', $user['id']) }}')">
                    <img src="{{ $user->avatar_url or asset('img/default_avatar.png') }}"/>
                <p>{{ $user->name}} {{ $user->sex }} {{ $user->city}} {{ $user->area}}</p>
                </div>
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
