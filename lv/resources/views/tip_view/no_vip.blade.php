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
       只有vip才能写信 <a href="{{ route('vip_index') }}">请充值vip</a>
</div>
@endsection
@section('right_content')
    <div class="content">
</div>
@endsection
