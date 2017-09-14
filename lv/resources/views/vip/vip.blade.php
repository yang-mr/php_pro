@extends('layouts.app')

@section('content')
<link href="{{ asset('/css/vip.css') }}" rel="stylesheet" type="text/css">

<div class="container">
    <div class="content_left">
       @foreach ($vips as $vip)
       <div class="vip_item">
          <div class="vip_item_title">
            <p>{{ $vip['title'] }}</p>
            <a href="#">订阅vip</a>
          </div>
          <div class="vip_item_price">
            {{ $vip['price'] }} 元
          </div>
          <div class="vip_item_description">
            {{ $vip['description'] }}
          </div>
       </div>
       @endforeach
    </div>
    <div class="content_right">
    </div>
</div>
@endsection
