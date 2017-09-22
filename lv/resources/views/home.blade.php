@extends('layouts.auto_app')

<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@section('left_content')
    <div class="home_left_content">
        <div class="nav_top">
            <a href="./home/edit_msg"><img src="{{ $user_avatar or asset('img/default_avatar.png') }}" onClick="go_personcenter()"/></a>
            <div class="user_description">
                <strong>征友进行中</strong><a href="#">修改</a>
            </div>
            <div class="operate">
                <button><a href="{{ route('in_letter')}}" class="gift_enter">收件箱</a><strong>({{ count($toLetters) }})</strong></button>
                <button><a href="{{ route('gift_index')}}" class="gift_enter">收到的礼物</a><strong>({{ count($toGifts) }})</strong></button>
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

    </div>
@endsection

@section('right_content')
    <div class="home_right_content">
        <div class="home_right_content_hint">
            <strong>谁关注了我</strong>
            <em>更多》</em>
        </div>
        <div id="toattentions">
                 @if (count($toMyAttentions) === 0)
                        暂无人关注我~
                       @else
                             @foreach($toMyAttentions as $toattention)
                                <div class="toattention">
                                    <img src="{{$toattention['avatar_url'] or asset('img/default_avatar.png')}}" />
                                    <div>{{$toattention['name']}}{{$toattention['city']}}</div>
                                </div>
                            @endforeach
                @endif
           
        </div>
          <div class="home_right_content_hint">
            <strong>谁看过了我</strong>
            <em>更多》</em>
        </div>
         <div id="tolooks">
                 @if (count($toMyLooks) === 0)
                        暂无人看过我~
                       @else
                             @foreach($toMyLooks as $toLook)
                                <div class="toattention">
                                    <img src="{{$toLook['avatar_url'] or asset('img/default_avatar.png')}}" />
                                    <div class="name_city_hint">{{$toLook['name']}} {{$toLook['city']}}</div>
                                </div>
                            @endforeach
                @endif
        </div>
    </div>
@endsection