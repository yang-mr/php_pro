@extends('layouts.app')

@section('content')
<div class="container">
    <nav id="nav">
       <div class="nav_top">
            <a href="./home/edit_msg"><img src="{{ $user_avatar or asset('img/default_avatar.png') }}" onClick="go_personcenter()"/></a>
            <div class="user_description">
                <strong>征友进行中</strong><a href="#">修改</a>
            </div>
            <div class="operate">
                <button>我关注谁<strong>({{ count($myLooks) }})</strong></button>
                <button>谁关注我<strong>({{ count($myAttentions) }})</strong></button>
                  <button>我看过谁<strong>({{ count($myLooks) }})</strong></button>
                <button>谁看过我<strong>({{ count($myAttentions) }})</strong></button>
                 <button>收件箱<strong>({{ count($myAttentions) }})</strong></button>
                <button>礼物<strong>({{ count($myAttentions) }})</strong></button>
            </div>
       </div>

       <div class="nav_bottom">
           <p>服务中心 ></p>
           <p><a href="#" class="gift_enter">礼物商城</a><a href="{{ route('vip_index') }}">充值vip</a></p>
       </div>
    </nav>
    <div id="content">
        <div class="content_left">
            <div class="content_left_head">
                <p>懂你今日推荐</p>
                <a href="#">更多></a>
            </div>
            <div content_left_users>
                
            </div>
             <div class="content_left_head">
                <p>哪些人对我感兴趣</p>
                <a href="#">更多></a>
            </div>
            <div>
                
            </div>
            <div content_left_users>
                
            </div>
             <div class="content_left_head">
                <p>懂你今日推荐</p>
                <a href="#">更多></a>
            </div>
            <div content_left_users>
                
            </div>
        </div>

        <div class="content_right">
            
        </div>
       <!--   <div class="handle_env">
            <a href="#">我关注的人</a>
            @if (count($myAttentions) === 0)
                暂无
            @else
                 @foreach ($myAttentions as $attention)
                    <div onClick="">
                        <img src="{{ $attention['img_avatar'] or asset('img/default_avatar.png') }}" />
                        <p>用户名: {{ $attention['name'] }}</p>
                    </div>
                @endforeach
            @endif
        </div>
        <div>
              <a href="#">我看过的人</a>
               @if (count($myLooks) === 0)
                暂无
               @else
                     @foreach ($myLooks as $look)
                    <img src="{{ $look['img_avatar'] or asset('img/default_avatar.png') }}" />
                    <p>用户名: {{ $look['name'] }}</p>
                    @endforeach
                @endif
        </div> -->
    </div>
</div>
@endsection
