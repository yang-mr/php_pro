@extends('layouts.auto_app')
<title>{{ $name }} {{ $home_location }}</title>
<script type="text/javascript">
$(function() {
    if ('{{ $attention }}' == 'cancel_attention') {
        $('#bt_attention').text('取消关注');
    } else {
        $('#bt_attention').text('添加关注');
    }
});

function add_attention(path) {
    var hint = $('#bt_attention').text();
    if (hint == '取消关注') {
        $.ajax({
            cache: true,
            type: "get",
            url: path,
            // data:['id':id],// 你的formid
            async: false,
            dataType: 'json',
            error: function(request) {
                alert(request);

            },
            success: function(data) {
                if (data == 1) {
                    $('#bt_attention').text('添加关注');
                } else if (data == 0) {
                    alert("取消关注失败");
                }
            }
        });
    } else if (hint == '添加关注') {
        $.ajax({
            cache: true,
            type: "get",
            url: path,
            // data:['id':id],// 你的formid
            async: false,
            dataType: 'json',
            error: function(request) {
                alert(request);

            },
            success: function(data) {
                if (data == 1) {
                    //提交成功
                    $('#bt_attention').text('取消关注');
                    //alert("提交成功");
                } else if (data == 0) {
                    alert("添加关注失败");
                }
            }
        });
    }
}
</script>
@section('left_content')

<nav id="nav_left">
    <div class="inner">
        <img src="{{ $avatar_url or asset('img/default_avatar.png') }}" />
        <p>{{ $name }} {{ $sex }}</p>
        <p>{{ $city }} {{ $area }}</p>
        <div>
            <button id='bt_attention' onClick="add_attention('{{ route($attention, $id) }}')">添加关注</button>
            <a href="{{ route('write_letter', $id) }}">写信</a>
            <a href="../send_email/{{ $id }}">送礼物</a>
        </div>
    </div>
    </div>
</nav>
@endsection @section('content')
<link href="{{ asset('/css/user_desc.css') }}" rel="stylesheet" type="text/css">

<div class="content">
        <meta name="location" content="province=北京;city=朝阳">
        <title>北京交友_妮妮（佳缘ID:138011499）的个人资料_世纪佳缘交友网</title>
                <link href="http://images1.jyimg.com/w4/profile_new/c/layer.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="http://images1.jyimg.com/w4/global/j/common_tools.js"></script>
        <script type='text/javascript' src='http://images1.jyimg.com/w4/mai/j/jy_mai_new.js'></script>
        <script type="text/javascript" src="http://images1.jyimg.com/w4/profile_new2/j/personal.js"></script>
        <script type="text/javascript" src="http://images1.jyimg.com/w4/profile_new2/j/scroll_pic.js"></script>
        <script type="text/javascript" src="http://images1.jyimg.com/w4/profile_new2/j/DNA.js"></script>
        <script type="text/javascript" src="http://images1.jyimg.com/w4/profile_new/j/ajax.js"></script>
        <script type="text/javascript" src="http://images1.jyimg.com/w4/profile_new/j/window.js"></script>
      
        <script type="text/javascript">
        //是否自己看自己
        var is_self = 0;

        if_get_mebers = 1;
        $(window).scroll(function() {
            vi = $(window).scrollTop();
            if (vi >= 108 && if_get_mebers == 1 && is_self == 0) {
                getInterestedMenbers();
                if_get_mebers = 0;
            }
        });

        $(document).ready(function() { //DOM的onload事件
            i = $(window).scrollTop();
            if (i >= 108 && if_get_mebers == 1 && is_self == 0) {
                getInterestedMenbers();
                if_get_mebers = 0;
            }
        });
        var profile_pop_tj_xj = 1;
        var if_band = 1;
        if_band = 0;
        var photo_i = 0;
        photo_i = 1;

        function copy_url() {
            var _url = $("#zydz").val();
            if (navigator.userAgent.indexOf("MSIE") == -1) {
                $('.tjts').html('您的浏览器不支持复制功能,请手工复制文本框中内容');
                $('.tjts').show();
                return false;
            } else {
                window.clipboardData.setData('Text', _url);
                $("#tjhy_ts").html("复制成功");
                $("#tjhy_ts").fadeIn(1000).fadeOut(2000);
            }
        }

        //弹层显示
        function show_photo_lj_tc(type) {
            //爱情密码弹层
            if (type == 4) {
                $('.pop_btm_line').css("padding", '20px 0 18px');
                $('.mmts').hide();
                $('#opa_70').css({
                    'display': 'block',
                    'opacity': '0'
                }).animate({
                    'opacity': 0.4
                }, 450, function() {
                    $(".pop_password_box").slideDown(600);
                });
            } else if (type == 13) { //推荐给好友弹层
                if (navigator.userAgent.indexOf("MSIE") == -1) {
                    $('.tjts').html('您的浏览器不支持复制功能,请手工复制文本框中内容');
                    $('.tjts').show();
                    $("#copy_an").hide();
                } else {
                    $("#copy_an").unbind().click(function() {
                        copy_url();
                        return false;
                    });
                }
                $('#opa_70').css({
                    'display': 'block',
                    'opacity': '0'
                }).animate({
                    'opacity': 0.4
                }, 450, function() {
                    $("#open_commend").slideDown(600);
                });
            } else { //其他弹层
                var wenan_arr = new Array();
                wenan_arr[1] = '系统繁忙，请稍后重试！';
                wenan_arr[2] = '密码错误，请重试！';
                wenan_arr[3] = '对方照片设置为“星级会员可见”，您不是星级会员，故没有权限查看照片。快去升级为<a target="_blank" style="color: #2c81d6;" href="http://www.jiayuan.com/usercp/validateemail/certificate.php">星级会员</a>吧！';
                wenan_arr[5] = '对方照片设置为“有照片会员可见”，您还没有上传照片，故没有权限查看照片。快去<a target="_blank" style="color: #2c81d6;" href="http://www.jiayuan.com/usercp/photo.php">上传照片吧</a>吧！';
                wenan_arr[6] = '发送成功';
                wenan_arr[7] = '发送失败，请重试';
                wenan_arr[8] = '关注成功！你可以到<a class="col_blue" href="http://www.jiayuan.com/usercp/friends_new.php"  target="_blank">我关注的人</a>中查看';
                wenan_arr[9] = '不能添加已在好友列表中的会员！请到<a class="col_blue" href="http://www.jiayuan.com/usercp/friends_new.php"  target="_blank">我关注的人</a>中查看';
                wenan_arr[10] = '不能添加在阻止名单中的会员';
                wenan_arr[11] = '不能添加相同性别的会员';
                wenan_arr[12] = '参数错误';
                wenan_arr[14] = '您已经退出登录，请刷新页面后登录重试！';
                JY_Alert('提示', wenan_arr[type]);
            }
        }
        //关闭弹层
        function close_photo_lj_tc(type, have_follow) {
            //其他弹层
            if (type == 1) {
                $("#public_tc").slideUp(350, function() {
                    $('#opa_70').animate({
                        'opacity': '0'
                    }, 550, function() {
                        $(this).css('display', 'none');
                    });
                });
            } else if (type == 2) { //爱情密码弹层
                $(".pop_password_box").slideUp(350, function() {
                    $('#opa_70').animate({
                        'opacity': '0'
                    }, 550, function() {
                        $(this).css('display', 'none');
                    });
                });
            } else if (type == 3) { //推荐给好友弹层
                $("#open_commend").slideUp(350, function() {
                    $('#opa_70').animate({
                        'opacity': '0'
                    }, 550, function() {
                        $(this).css('display', 'none');
                    });
                });
            }
        }
        send_jy_pv2('|1017943_4|168103003');
        send_jy_pv2('|1017943_75|168103003');
        send_jy_pv2('|1017943_5|');
        send_jy_pv2('|1017943_6|138011499');
        send_jy_pv2('|1017943_79|168103003_138011499');
        send_jy_pv2('|1017943_22|138011499');
        //设置域 为了弹出页面的js操作
        var reg_host_const_flag = 0;
        var reg_host_const_test = 0;
        var reg_host_domain = document.domain;


/*
 鍏ㄧ珯鍏敤alert鏇挎崲鍑芥暟
 http://images1.jyimg.com/w4/popup/JY_Alert/
*/
function JY_Alert(title, content, zIndex) {
    var oBody = document.getElementsByTagName("body")[0], oHtml = document.getElementsByTagName("html")[0], JY_alert, alert_close, alert_title, alert_bg, alert_btn, title = title || "\u6e29\u99a8\u63d0\u793a", content = content || '', minHeight = 140, zIndex = zIndex || 99999, isIE6 = !-[1, ] && !window.XMLHttpRequest, setCss = function (obj, json) {
        var arr = ["Webkit", "Moz", "O", "ms", ""];
        for (var attr in json) {
            if (attr.charAt(0) == "$") {
                for (var i = 0; i < arr.length; i++) {
                    obj.style[arr[i] + attr.substring(1)] = json[attr]
                }
            } else {
                if (typeof json[attr] == "number") {
                    switch (attr) {
                        case "opacity":
                            if (value < 0) value = 0;
                            obj.style.filter = "alpha(opacity:" + value + ")";
                            obj.style.opacity = value / 100;
                            break;
                        case "zIndex":
                            obj.style[attr] = json[attr];
                            break;
                        default:
                            obj.style[attr] = json[attr] + "px";
                    }
                } else {
                    if (typeof json[attr] == "string") obj.style[attr] = json[attr];
                }
            }
        }
    }, addEvent = function (obj, sEv, callBak) {
        obj.attachEvent ? obj.attachEvent("on" + sEv, callBak) : obj.addEventListener(sEv, callBak, false)
    }, removeEvent = function (obj, sEv, callBak) {
        obj.detachEvent ? obj.attachEvent("on" + sEv, callBak) : obj.removeEventListener(sEv, callBak, false)
    }, getViewSize = function () {
        var result = {};
        if (window.innerWidth) {
            result.winW = window.innerWidth;
            result.winH = window.innerHeight
        } else {
            if (document.documentElement.offsetWidth == document.documentElement.clientWidth) {
                result.winW = document.documentElement.offsetWidth;
                result.winH = document.documentElement.offsetHeight
            } else {
                result.winW = document.documentElement.clientWidth;
                result.winH = document.documentElement.clientHeight;
            }
        }
        result.docW = Math.max(document.documentElement.scrollWidth, document.body.scrollWidth, document.documentElement.offsetWidth);
        result.docH = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight, document.documentElement.offsetHeight);
        return result;
    }, range = function (iCurr, iMin, iMax) {
        return iCurr < iMin ? iMin : iCurr > iMax ? iMax : iCurr
    }, drag = function (popupID, moveID) {
        var popup = document.getElementById(popupID), move = document.getElementById(moveID), disX = disY = 0;
        move.onmouseover = function () {this.style.cursor = "move";};
        move.onmouseout = function () {this.style.cursor = "default"};
        move.onmousedown = function (ev) {
            var ev = ev || event;
            disX = ev.clientX - popup.offsetLeft;
            disY = ev.clientY - popup.offsetTop;
            document.onmousemove = function (ev) {
                var ev = ev || event;
                setCss(popup, {left: range(ev.clientX - disX, 0, getViewSize().docW - popup.offsetWidth), top: range(ev.clientY - disY, 0, getViewSize().winH - popup.offsetHeight)});
            };
            document.onmouseup = function () {this.onmousemove = null; this.onmouseup = null;};
            return false;
        }
    }, init = function () {
        if(!document.getElementById('JY_alert')){
            var JY_alert_main = document.createElement("div");
            JY_alert_main.id = "JY_alert";
            JY_alert_main.style.cssText = "width:400px; padding: 1px 1px 50px 1px; background: #fff; position: absolute; top: 0px; left: 0px; z-index:" + (zIndex + 1) + ";";
            setCss(JY_alert_main, {position: isIE6 ? "absolute" : "fixed"});
            var JY_alert_bg = document.createElement("div");
            JY_alert_bg.id = "JY_alert_bg";
            JY_alert_bg.style.cssText = "background: #000; opacity: 0.4; filter:alpha(opacity=40); position:absolute; top:0; left:0; z-index:" + zIndex + ";";
            oBody.appendChild(JY_alert_bg);
            var createEle = function (tagName, cssText, innerHTML, id) {
                var newEle = document.createElement(tagName);
                newEle.style.cssText = cssText;
                newEle.innerHTML = innerHTML;
                if (id) newEle.id = id;
                JY_alert_main.appendChild(newEle);
            };
            createEle("h2", "height: 30px; line-height: 30px; margin: 0; padding: 0 10px; text-align:left; color: #fff;  font-size: 14px; background: url(http://images1.jyimg.com/w4/popup/JY_alert/i/title_bg.jpg) repeat-x; position: relative;", title + '<a id="JY_alert_close" href="javascript:;" style="width: 15px; height: 15px; position: absolute; top: 7px; right: 10px; background: url(http://images1.jyimg.com/w4/popup/JY_alert/i/alert_close.png); overflow: hidden; display: block; font-size: 0;">\u5173\u95ed</a>', "JY_alert_title");
            createEle("div", "width: 90%; line-height:18px; margin: 0 auto; padding: 20px 0; font-size: 12px; color: #666; word-wrap: break-word; word-break: break-all;", content, 'jy_alert_content');
            createEle("div", "width: 73px; height: 28px; margin: 0; padding:0; position:absolute; bottom:20px; left:163px; text-align: center; cursor: pointer;", '<img src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png">', "JY_alert_btn");
            oBody.appendChild(JY_alert_main);
            JY_alert = document.getElementById("JY_alert");
            alert_close = document.getElementById("JY_alert_close");
            alert_title = document.getElementById("JY_alert_title");
            alert_content = document.getElementById("jy_alert_content");
            alert_bg = document.getElementById("JY_alert_bg");
            alert_btn = document.getElementById("JY_alert_btn");
            addEvent(window, "resize", function () {setCss(JY_alert_bg, {width: 0, height: 0}); reset(); });
            addEvent(window, "scroll", function () {setCss(JY_alert_bg, {width: 0, height: 0});reset(); });
            addEvent(alert_close, "click", remove_alert);
            addEvent(alert_btn, "click", function () {remove_alert();});
            addEvent(alert_close, "mouseover", function () {setCss(alert_close, {backgroundPosition: "0 -16px"})});
            addEvent(alert_close, "mouseout", function () {setCss(alert_close, {backgroundPosition: "0 0"})});
            drag("JY_alert", "JY_alert_title");
            reset();
            if (typeof(JY_alert.onselectstart) != "undefined") {
                JY_alert.onselectstart = new Function("return false")
            } else {
                JY_alert.onmousedown = new Function("return false");
                JY_alert.onmouseup = new Function("return true")
            };
        };
    }, remove_alert = function () {
        oBody.removeChild(JY_alert);
        oBody.removeChild(alert_bg);
        if(isIE6) oHtml.style.overflowX = '';
        return false;
    }, reset = function () {
        setCss(alert_bg, {width: getViewSize().docW, height: getViewSize().docH});
        setCss(JY_alert, {top: isIE6 ? (getViewSize().winH - JY_alert.offsetHeight) / 2 + document.documentElement.scrollTop || document.body.scrollTop: (getViewSize().winH - JY_alert.offsetHeight) / 2, left: isIE6 ? (getViewSize().winW - JY_alert.offsetWidth) / 2 + document.documentElement.scrollLeft || document.body.scrollLeft : (getViewSize().winW - JY_alert.offsetWidth) / 2
        });
        setCss(alert_content, {textAlign: JY_alert.offsetHeight > minHeight ? 'left' : 'center'});
        if(isIE6) oHtml.style.overflowX = 'hidden';
    };
    init();
};



        function setDomainForIframe() {
            if (reg_host_const_flag == 0 || reg_host_const_flag == 7) {
                if (reg_host_const_test == 1) {
                    document.domain = 'miuu.cn';
                } else {
                    document.domain = 'jiayuan.com';
                }
            } else {
                if (reg_host_const_test == 1) {
                    document.domain = 'miuu.cn';
                } else {
                    document.domain = 'msn.com.cn';
                }
            }
        }
        setDomainForIframe();
        var look_user = 168103003;
        var getID = function(objName) { if (document.getElementById) { return eval('document.getElementById("' + objName + '")') } else { return false } };
        var chat_url = location.href;
        var can_send_msg = 1;
        var can_gz = 1;

        //判断是不是同性
        var isgay = 0;
        //判断是否显示照片弹层
        var no_show = 0;
        no_show = 1;
        //判断是否第一张为方头像，不是则显示第二张照片
        var show_second_photo = 0;
        show_second_photo = 1;
        var is_online = 0;
        var is_link = 0;
        var uid_disp = 138011499;
        send_jy_pv2('|1009622_4|138011499');
        if (document.location.href.substring(document.location.href.length - 7) == "#cp_kpd") {
            openWindow('', '', 'http://www.jiayuan.com/profile/reliable.php?uid=138011499', 570, 380);
            document.location.href = document.location.href + "#1";
        }

        function init_chaturl() {
            chat_url = chat_url.replace('cnm=', 'flt=');
            chat_url = chat_url.replace('from=art', 'flt=art');
            chat_url = chat_url.replace('from=story', 'flt=story');
            chat_url = chat_url.replace('?t=0&', '?flt=search&');
            chat_url = chat_url.replace('?fr=o&', '?flt=online&');
            chat_url = chat_url.replace('?lt=msgbox', '?flt=msgbox');
            chat_url = chat_url.replace('&t=0&s=', '&flt=search&s=');
            chat_url = chat_url.replace('&fr=o&', '&flt=online&');
        }
        init_chaturl();
        //获取感兴趣的人
        function getInterestedMenbers() {
            var parm = encodeURIComponent('pid:personalmatch_profile_new|count:6|cachesql:3600|sim_uid:138011499');
            url = 'http://www.jiayuan.com/ajax/interested.php?r=' + Math.random() + '&ad_param[]=' + parm;
            ajax(url, '', getInterestedMenbersCallback);
        }

        function getInterestedMenbersCallback(data) {
            if (data) {
                getID('interested').innerHTML = eval(data);
            }
        }
        //礼物推荐
        function getCommendGift(uid) {
            if (uid) ajaxGET('gift_commend', 'http://www.jiayuan.com/gift_commend.php?uid=' + uid + '&new=2&is_new=10');
        }
        //提交爱情密码
        function photo_pwd() {
            var pwd = $(".pop_btn").val();
            if (pwd == '') {
                $('.mmts').html('密码不能为空');
                $('.pop_btm_line').css("padding", '20px 0 4px');
                $('.mmts').show();
                return false;
            } else {
                $.ajax({
                    url: 'http://www.jiayuan.com/profile/dynmatch/ajax/check_photo_pwd.php',
                    type: 'GET',
                    data: { uid: 138011499, pwd: pwd },
                    timeout: 10000,
                    error: function() {
                        $('.mmts').html('系统繁忙，请稍后重试！');
                        $('.pop_btm_line').css("padding", '20px 0 4px');
                        $('.mmts').show();
                    },
                    success: function(data) {
                        if (data == 1) {
                            location.reload();
                        } else {
                            $('.mmts').html('密码错误，请重试');
                            $('.pop_btm_line').css("padding", '20px 0 4px');
                            $('.mmts').show();
                        }
                    }
                });
            }
        }
        //发信
        function send_msg(id, msg_type) {
            if (can_send_msg == 1) {
                can_send_msg = 0;
                if (msg_type == 1) { //索要密码发信
                    $.ajax({
                        url: 'http://www.jiayuan.com/profile/dynmatch/ajax/send_msg.php',
                        type: 'GET',
                        data: { uid: 138011499, id: id, msg_type: msg_type, fxly: '' },
                        timeout: 10000,
                        error: function() {
                            $("#mmtc_ts").html("系统繁忙");
                            $("#mmtc_ts").fadeIn(1000).fadeOut(2000);
                            can_send_msg = 1;
                            $('#mmfs').unbind().click(function() {
                                $(this).unbind();
                                send_msg(65, 1);
                            });
                        },
                        success: function(data) {
                            if (data == 1) {
                                $("#mmfs").html('已发送');
                                $("#mmtc_ts").html("发送成功");
                                $("#mmtc_ts").fadeIn(1000).fadeOut(2000);
                                can_send_msg = 1;
                            } else if (data == 0) {
                                alert("您已经退出登录，请先登录！");
                                location.reload();
                            } else {
                                $("#mmtc_ts").html("发送失败");
                                $("#mmtc_ts").fadeIn(1000).fadeOut(2000);
                                can_send_msg = 1;
                                $('#mmfs').unbind().click(function() {
                                    $(this).unbind();
                                    send_msg(65, 1);
                                });
                            }
                        }
                    });
                } else if (msg_type == 2) { //照片弹层发信
                    can_send_msg = 1;
                    var msg_val = $("#i_say").val();
                    if (msg_val == '' || msg_val == "在这里输入你想对她说的话..." || msg_val.replace(/\s+/g, "") == '') {
                        $(".zpfxts").html("发信内容不能为空！").show();
                        $('#zptc_fxan').unbind().click(function() {
                            $(this).unbind();
                            send_msg(0, 2);
                        });
                        return false;
                    }
                    send_jy_pv2('|1017943_18|168103003');
                    send_jy_pv2('|1017943_19|');
                    send_jy_pv2('|1017943_76|168103003');
                    send_jy_pv2('|1017943_80|168103003_138011499');
                    $(".zpfxts").hide();
                    $('#zptc_fx_form').submit();
                    $("#zptc_fxan").html('已发送');
                    hf_wa();
                    /*$.ajax({
                            url: 'http://www.jiayuan.com/profile/dynmatch/ajax/send_msg.php',
                            type: 'GET',
                            data: {uid:138011499,msg_value:msg_val,msg_type:msg_type,fxly:''},
                            timeout: 10000,
                            error: function(){
                                    $("#zptc_ts").html("系统繁忙");
                                    $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                                    can_send_msg = 1;
                                    $('#zptc_fxan').unbind().click(function(){
                                            $(this).unbind();
                                            send_msg(0,2);
                                    });
                            },
                            success: function(data){
                                    if(data == 1){
                                            send_jy_pv2('|1017943_18|168103003');
                                            send_jy_pv2('|1017943_19|');
                                            $("#zptc_fxan").html('已发送');
                                            $("#zptc_ts").html("发送成功");
                                            $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                                            hf_wa();
                                            can_send_msg = 1;
                                    }else if(data == 0){
                                            alert("您已经退出登录，请先登录！");
                                            location.reload();
                                    }else{
                                            $("#zptc_ts").html("发送失败");
                                            $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                                            can_send_msg = 1;
                                            $('#zptc_fxan').unbind().click(function(){
                                                    $(this).unbind();
                                                    send_msg(0,2);
                                            });
                                    }
                            }
                    });*/
                } else if (msg_type == 3) { //询问信息发信
                    var a_obj = $("em.info_null[msg_id=" + id + "]");
                    $.ajax({
                        url: 'http://www.jiayuan.com/profile/dynmatch/ajax/send_msg.php',
                        type: 'GET',
                        data: { uid: 138011499, id: id, msg_type: msg_type, fxly: '' },
                        timeout: 10000,
                        error: function() {
                            a_obj.html('--').unbind();
                            a_obj.parent().append('<div class="tip6">发送失败，请重试</div>');
                            a_obj.parent().find(".tip6").stop().animate({ 'opacity': 1 }, 1000, function() {
                                $(this).animate({ 'opacity': 0 }, 2000, function() {
                                    $(this).remove();
                                });
                            });
                            can_send_msg = 1;
                        },
                        success: function(data) {
                            if (data == 1) {
                                a_obj.html('--').unbind();
                                a_obj.parent().append('<div class="tip1">你的心意已传达</div>');
                                a_obj.parent().find(".tip1").stop().animate({ 'opacity': 1 }, 1000, function() {
                                    $(this).animate({ 'opacity': 0 }, 2000, function() {
                                        $(this).remove();
                                    });
                                });
                                can_send_msg = 1;
                            } else if (data == 0) {
                                a_obj.html('--');
                                $(".info_null").unbind();
                                can_send_msg = 1;
                                alert("您已经退出登录，请先登录！");
                                location.reload();
                            } else {
                                a_obj.html('--').unbind();
                                a_obj.parent().append('<div class="tip6">发送失败，请重试</div>');
                                a_obj.parent().find(".tip6").stop().animate({ 'opacity': 1 }, 1000, function() {
                                    $(this).animate({ 'opacity': 0 }, 2000, function() {
                                        $(this).remove();
                                    });
                                });
                                can_send_msg = 1;
                            }
                        }
                    });
                } else if (msg_type == 5) { //询问信息发信
                    var a_obj = $("p.gn_text[msg_id=" + id + "]");
                    $.ajax({
                        url: 'http://www.jiayuan.com/profile/dynmatch/ajax/send_msg.php',
                        type: 'GET',
                        data: { uid: 138011499, id: id, msg_type: 3, fxly: '' },
                        timeout: 10000,
                        error: function() {
                            a_obj.append('<font class="tip7_a">发送失败，请重试</font>');
                            a_obj.find(".tip7_a").stop().animate({ 'opacity': 1 }, 1000, function() {
                                $(this).animate({ 'opacity': 0 }, 2000, function() {
                                    $(this).remove();
                                });
                            });
                            a_obj.find("a").click(function() {
                                $(this).unbind();
                                send_msg(id, 5);
                            });
                            can_send_msg = 1;
                        },
                        success: function(data) {
                            if (data == 1) {
                                a_obj.html('你已经发送了邀请');
                                a_obj.append('<font class="tip4_a">你的心意已传达</font>');
                                a_obj.find(".tip4_a").stop().animate({ 'opacity': 1 }, 1000, function() {
                                    $(this).animate({ 'opacity': 0 }, 2000, function() {
                                        $(this).remove();
                                    });
                                });
                                can_send_msg = 1;
                            } else if (data == 0) {
                                a_obj.html('--');
                                $(".info_null").unbind();
                                can_send_msg = 1;
                                alert("您已经退出登录，请先登录！");
                                location.reload();
                            } else {
                                a_obj.append('<font class="tip7_a">发送失败，请重试</font>');
                                a_obj.find(".tip7_a").stop().animate({ 'opacity': 1 }, 1000, function() {
                                    $(this).animate({ 'opacity': 0 }, 2000, function() {
                                        $(this).remove();
                                    });
                                });
                                a_obj.find("a").click(function() {
                                    $(this).unbind();
                                    send_msg(id, 5);
                                });
                                can_send_msg = 1;
                            }
                        }
                    });
                } else if (msg_type == 6) { //询问信息发信
                    var a_obj = $("p.gn_text[msg_id=" + id + "]");
                    $.ajax({
                        url: 'http://www.jiayuan.com/profile/dynmatch/ajax/send_msg.php',
                        type: 'GET',
                        data: { uid: 138011499, id: id, msg_type: 3, fxly: '' },
                        timeout: 10000,
                        success: function(data) {
                            if (data == 1) {
                                send_jy_pv2('|profile_sendmsg|168103003');
                                can_send_msg = 1;
                            } else if (data == 0) {
                                can_send_msg = 1;
                                alert("您已经退出登录，请先登录！");
                                location.reload();
                            } else {
                                can_send_msg = 1;
                            }
                        }
                    });
                }
            }
        }
        //加关注
        function add_follow(type) {
            if (type == 1) {
                $.ajax({
                    url: 'http://www.jiayuan.com/follow.php',
                    type: 'GET',
                    data: { uid: 138011499 },
                    timeout: 10000,
                    error: function() {
                        show_photo_lj_tc(1);
                    },
                    success: function(data) {
                        if (data == 1) {
                            send_jy_pv2('|1017943_7|168103003');
                            send_jy_pv2('|1017943_8|');
                            show_photo_lj_tc(8);
                        } else if (data == -1) {
                            show_photo_lj_tc(9);
                        } else if (data == -2) {
                            show_photo_lj_tc(10);
                        } else if (data == -3) {
                            show_photo_lj_tc(11);
                        } else if (data == 0) {
                            show_photo_lj_tc(14);
                        } else {
                            show_photo_lj_tc(12);
                        }
                    }
                });
            } else {
                if (can_gz == 1) {
                    can_gz = 0;
                    $.ajax({
                        url: 'http://www.jiayuan.com/follow.php',
                        type: 'GET',
                        data: { uid: 138011499 },
                        timeout: 10000,
                        error: function() {
                            $("#zptc_ts").html("系统繁忙");
                            $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                            setTimeout(function() { can_gz = 1; }, 3000);
                        },
                        success: function(data) {
                            if (data == 1) {
                                send_jy_pv2('|1017943_73|168103003');
                                send_jy_pv2('|1017943_74|');
                                get_gz_people();
                                $("#zptc_ts").html("关注成功");
                                $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                                setTimeout(function() { can_gz = 1; }, 3000);
                            } else if (data == -1) {
                                $("#zptc_ts").html("不能重复关注");
                                $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                                setTimeout(function() { can_gz = 1; }, 3000);
                            } else if (data == -2) {
                                $("#zptc_ts").html("不能关注黑名单");
                                $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                                setTimeout(function() { can_gz = 1; }, 3000);
                            } else if (data == -3) {
                                $("#zptc_ts").html("不能关注同性");
                                $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                                setTimeout(function() { can_gz = 1; }, 3000);
                            } else {
                                $("#zptc_ts").html("参数错误");
                                $("#zptc_ts").fadeIn(1000).fadeOut(2000);
                                setTimeout(function() { can_gz = 1; }, 3000);
                            }
                        }
                    });
                }
            }
        }

        function if_show_pic(type) {
            /**点击的是头像的部分**/
            if (type == 1) {}
            openWindow('', '', 'http://www.jiayuan.com/usercp/photo_scyd_tc.php?tc_type=1&touid=138011499', 572, 222);
            return false;
        }
        //人脸搜索更变照片url
        function face_url_change(type, id) {
            if (id == 0) {
                if (type == 1) {
                    var like_photo_url = $("#bigImg li").eq(id).find("img").attr('src');
                    //                        $("#like_ta1").attr('href','http://www.jiayuan.com');
                    $("#like_ta1").attr('href', 'http://case.jiayuan.com/face/?s=0&url=' + like_photo_url + '&fr=profile&sex=f');
                    $("#like_ta1").show();
                } else {
                    var like_photo_url = $("#pBigImg li").eq(id).find("img").attr('src');
                    $("#like_ta2").attr('href', 'http://case.jiayuan.com/face/?s=0&url=' + like_photo_url + '&fr=profile&sex=f');
                    $("#like_ta2_p").show();
                }
            } else {
                if (type == 1) {
                    var like_photo_url = $("#bigImg li").eq(id).find("img").attr('_src');
                    $("#like_ta1").attr('href', 'http://case.jiayuan.com/face/?s=0&url=' + like_photo_url + '&fr=profile&sex=f');
                    $("#like_ta1").show();
                } else {
                    var like_photo_url = $("#pBigImg li").eq(id).find("img").attr('_src');
                    $("#like_ta2").attr('href', 'http://case.jiayuan.com/face/?s=0&url=' + like_photo_url + '&fr=profile&sex=f');
                    $("#like_ta2_p").show();
                }
            }
        }
        //右侧注册
        function profile_register() {
            var sex = radio_value('sex');
            var year = getID('year').value;
            var month = getID('month').value;
            var day = getID('day').value;
            var province = getID('province').value;
            var city = getID('city').value;
            var marriage = radio_value('marriage');
            var email = getID('username').value;

            var mydomain = "";
            var host_name = location.hostname;
            var host_arr = host_name.split(".");
            var host_length = host_arr.length;

            //当前域名
            if (host_arr[1] == 'com' || host_arr[1] == 'cn') {
                for (var i = 0; i < host_length; i++) {
                    mydomain += "." + host_arr[i];
                }
            } else {
                for (var i = 1; i < host_length; i++) {
                    mydomain += "." + host_arr[i];
                }
            }

            window.open('http://reg' + mydomain + '/?hao123=1&sex=' + sex + '&year=' + year + '&month=' + month + '&day=' + day + '&email=' + email + '&province=' + province + '&city=' + city + '&marriage=' + marriage + '&bd=9');
        }

        function radio_value(radio_name) {
            var radio_arr = document.getElementsByName(radio_name);
            for (var i = 0; i < radio_arr.length; i++) {
                if (radio_arr[i].checked == true) {
                    return radio_arr[i].value;
                }
            }
            return 0;
        }
        //照片弹层发信文案清除
        function hf_wa() {
            $("#i_say").val('');
            $("#i_say").attr("disabled", "disabled");
        }
        //获取关注的人
        function get_gz_people() {
            get_gz_people_num();
            ajax('http://www.jiayuan.com/profile/dynmatch/ajax/get_fans.php?uid=138011499', '', function(data) {
                if (data != -1) {
                    $("#gz_people ul").html(data);
                    $("#gz_people img").each(function(i, ele) {
                        loadImg($(ele).attr('_src'), function() {
                            $(ele).attr('src', $(ele).attr('_src'));
                            if ($.browser.safari) {
                                setTimeout(function() {
                                    cutImage($(ele), 30, 30);
                                }, 10)
                            } else {
                                cutImage($(ele), 30, 30);
                            }
                        })
                    });
                    //关注的浮层
                    $(".gz_num").unbind('mouseenter').unbind('mouseleave').hover(function() {
                        if (window.timeY) {
                            clearTimeout(window.timeY);
                        }
                        $('.gz_tip').show();
                    }, function() {
                        window.timeY = setTimeout(function() {
                            $('.gz_tip').hide();
                        }, 500);
                    });
                } else {
                    $(".gz_num").unbind('mouseenter').unbind('mouseleave');
                }
            });
        }
        //获取关注的人数
        function get_gz_people_num() {
            ajax('http://www.jiayuan.com/profile/dynmatch/ajax/get_fans_count.php?uid=138011499', '', function(data) {
                if (data == 0) {
                    $("#gz_people_a").html("关注");
                } else {
                    $("#gz_people_a").html("关注(" + data + "人)");
                }
            });
        }
        $(function() {
            $('#mmfs').unbind().click(function() {
                send_msg(65, 1);
            });
            $('#zptc_fxan').unbind().click(function() {
                send_msg(0, 2);
            });
            $(".info_null").hover(function() {
                var msg_id = $(this).attr("msg_id");
                $(this).html('<a onclick="send_msg(' + msg_id + ',3);return false;" onmousedown="send_jy_pv2(\'|1017867_20|\');send_jy_pv2(\'|1017867_21|168103003\');send_jy_pv2(\'|1017943_76|168103003\');send_jy_pv2(\'|1017943_80|168103003_138011499\');" style="cursor:pointer" class="col_blue">我想知道</a>');
            }, function() {
                $(this).html('--');
            });
            $("#yqbcshxg").unbind().click(function() {
                send_msg(21, 5);
            });
            $("#yqbcjwnl").unbind().click(function() {
                send_msg(26, 5);
            });
            $("#yqbcycw").unbind().click(function() {
                send_msg(29, 5);
            });
            $("#yqbcgz").unbind().click(function() {
                send_msg(38, 5);
            });
            $("#yqbcxx").unbind().click(function() {
                send_msg(42, 5);
            });
            $("#yqbcjt").unbind().click(function() {
                send_msg(61, 5);
            });
            $("#yqbcgx").unbind().click(function() {
                send_msg(64, 5);
            });
            $('#opa_70').css({
                'height': document.documentElement.scrollHeight
            });
            $("#pSmallImg img").each(function(i, ele) {
                loadImg($(ele).attr('_src'), function() {
                    $(ele).attr('src', $(ele).attr('_src'));
                    if ($.browser.safari) {
                        setTimeout(function() {
                            cutImage($(ele), 52, 52);
                        }, 10)
                    } else {
                        cutImage($(ele), 52, 52);
                    }
                })
            });
            loadImg($("#gs_ava").attr('_src'), function() {
                $("#gs_ava").attr('src', $("#gs_ava").attr('_src'));
                if ($.browser.safari) {
                    setTimeout(function() {
                        cutImage($("#gs_ava"), 80, 80);
                    }, 10)
                } else {
                    cutImage($("#gs_ava"), 80, 80);
                }
            });
            $.ajax({
                url: "http://qinggan.jiayuan.com/mobile/dongni.php",
                type: "GET",
                data: { uid: 138011499 },
                dataType: "jsonp",
                jsonp: 'callback',
                timeout: 5000,
                success: function(res) {
                    if (res) {
                        $('#qinggan_div').show();
                        var dom = $(res[0]['content']);
                        var text = dom.text();
                        if (text.length >= 120) {
                            text = text.slice(0, 120);
                            text += '……';
                        }
                        $('#qinggan').html(text);
                        $('#qinggan_div #qinggan_link').attr('href', res[0]['link']);
                    }

                },
                error: function() {
                    send_jy_pv2('|1035098_0|');
                }
            })
        })

        function show_yueliao_pop() {

            jy_head_function.lbg_tpl = '<iframe id="ly_yueliao" src="" width="480" height="230" scrolling="no" frameborder="no"></iframe>';

            jy_head_function.lbg_show({ lbg_z_index: '10000' });
            $("#ly_yueliao").attr("src", "http://www.jiayuan.com/usercp/yueliao/popup.php?uid=138011499");

        }
        //问答邮件
        function sendmail(id, muid, uid) {
            var id = id;
            $.ajax({
                url: "http://www.jiayuan.com/wenda/ajax_send_dnamail.php",
                type: "post",
                data: { muid: muid, uid: uid, mailid: id },
                success: function(msg) {
                    if (msg == 1) {
                        $("#" + id + "").html("邀请邮件已发送");
                    } else { JY_Alert('提示', '发送失败，请重试！'); }
                }
            })
        }

        //跳转到缘分圈
        function jump_to_fate() {
            jy_head_function.lbg_show("blogwrap", { lbg_sec: "3", lbg_sec_id: "blogwrap_secs", hide_callback: "fate_page()" });
        }

        function fate_page() {
            window.location.href = "http://fate.jiayuan.com/myfate.php?hash=70f455121ae268b189ef637556b65c0d&show=2";
        }

        function show_mobile_vali() {
            jy_head_function.lbg_tpl = '<iframe src="http://www.jiayuan.com/usercp/validateemail/gmcglj_checkmobile.php?domain_type=1&tj_key=profile_sjljc&has_close=1&title=验证手机查看最近登录时间　" id="ifr_tpl" width="450" height="300" scrolling="no" frameborder="0"></iframe>';
            jy_head_function.lbg_show({ lbg_z_index: '1000' });
        }

        //签到后访客人数统计
        </script>
      
      <!--   <div class="subnav_box yh">
            <div class="main_1000 fn-clear">
                <ul class="nav_l">
                    <li class="cur"><a href="javascript:;">她的资料</a></li>
                    <li><a onclick="if(!if_show_pic()){return false;}" href="http://photo.jiayuan.com/showphoto.php?uid_hash=f4701255e21ab168ef897563b6560d5c&tid=0&cache_key=" target="_blank">她的照片(2)</a></li>
                    <li><a href="http://www.jiayuan.com/more_gift.php?uid=138011499&tid=0&cache_key=" target="_blank" onmousedown="send_jy_pv2('|1017864_0|');send_jy_pv2('|1017864_1|168103003');">她的礼物(1)</a></li>
                    <li><a href="http://fate.jiayuan.com/myfate.php?hash=70f455121ae268b189ef637556b65c0d&show=2" target="_blank" onmousedown="send_jy_pv2('|1017864_2|');send_jy_pv2('|1017864_3|168103003');">她的缘分圈</a></li>
                </ul>
                <div class="nav_next"><a href="http://www.jiayuan.com/138011499?n=1" onmousedown="send_jy_pv2('|1017864_4|');send_jy_pv2('|1017864_5|168103003');">下一个有缘人</a></div>
            </div>
        </div> -->
        <!--subnav end -->
        <!--main start -->
        <!--top start -->
        <div class="main_1000 bg_white mt15">
            <div class="top_box fn-clear">
                <!--会员信息 start-->
                <div class="member_box">
                    <!--轮播图 start-->
                    <div class="pic_box">
                        <!--芝麻信用 start-->
                        <a class="credit_bg credit_no" href="http://www.jiayuan.com/usercp/approve/zmxyentity.php" onclick="send_msg(67,6);" target="_blank">芝麻信用分</a>
                        <!--芝麻信用 end-->
                        <div class="pic_btm_bg"></div>
                        <div class="pho_ico">
                            2
                        </div>
                        <div class="big_pic fn-clear" id="bigImg">
                            <ul style="margin-left: -250px;">
                                <li style="overflow:hidden;">
                                    <table cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="center" onclick="if(!if_show_pic(1)){return false;}"><a onmousedown="send_jy_pv2('|1017864_6|');send_jy_pv2('|1017864_7|168103003');" target="_blank" href="http://photo.jiayuan.com/showphoto.php?uid_hash=f4701255e21ab168ef897563b6560d5c"><img class="img_absolute" style="position: absolute;height: 31px;width: 31px;" src="http://images1.jyimg.com/w4/parties/app/yfsp/i/loading.gif" _src="http://at4.jyimg.com/f4/5c/701255e21ab168ef897563b6560d/701255e21_1_avatar_p.jpg"/></a></td>
                                        </tr>
                                    </table>
                                </li>
                                <li style="overflow:hidden;">
                                    <table cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="center" onclick="if(!if_show_pic()){return false;}"><a onmousedown="send_jy_pv2('|1017864_6|');send_jy_pv2('|1017864_7|168103003');" target="_blank" href="http://photo.jiayuan.com/showphoto.php?uid_hash=f4701255e21ab168ef897563b6560d5c&p=0"><img class="img_absolute" style="position: absolute;height: 31px;width: 31px;" src="http://images1.jyimg.com/w4/parties/app/yfsp/i/loading.gif" _src="http://t4.jyimg.com/f4/5c/701255e21ab168ef897563b6560d/118097853d.jpg"/></a></td>
                                        </tr>
                                    </table>
                                </li>
                            </ul>
                        </div>
                        <div class="small_pic_box fn-clear" id="smallImg">
                            <a class="prev"></a>
                            <div class="small_pic fn-clear">
                                <ul>
                                    <li style="overflow:hidden;position: relative;" onclick="if(!if_show_pic(1)){return false;}"><a target="_blank" href="http://photo.jiayuan.com/showphoto.php?uid_hash=f4701255e21ab168ef897563b6560d5c"><img class="img_absolute_list" style="position: absolute;height: 31px;width: 31px;" src="http://images1.jyimg.com/w4/parties/app/yfsp/i/loading.gif" _src="http://at4.jyimg.com/f4/5c/701255e21ab168ef897563b6560d/701255e21_1_avatar_s.jpg"/></a></li>
                                    <li style="overflow:hidden;position: relative;" onclick="if(!if_show_pic()){return false;}"><a target="_blank" href="http://photo.jiayuan.com/showphoto.php?uid_hash=f4701255e21ab168ef897563b6560d5c&p=0"><img class="img_absolute_list" style="position: absolute;height: 31px;width: 31px;" src="http://images1.jyimg.com/w4/parties/app/yfsp/i/loading.gif" _src="http://t4.jyimg.com/f4/5c/701255e21ab168ef897563b6560d/118097853t.jpg"/></a></li>
                                </ul>
                            </div>
                            <a class="next"></a>
                        </div>
                        <script type="text/javascript">
                        $("#smallImg img").each(function(i, ele) {
                            loadImg($(ele).attr('_src'), function() {
                                $(ele).attr('src', $(ele).attr('_src'));
                                if ($.browser.safari) {
                                    setTimeout(function() {
                                        cutImage($(ele), 50, 50);
                                    }, 10)
                                } else {
                                    cutImage($(ele), 50, 50);
                                }
                            })
                        });

                        $("#bigImg img").each(function(i, ele) {
                            loadImg($(ele).attr('_src'), function() {
                                $(ele).attr('src', $(ele).attr('_src'));
                                if ($.browser.safari) {
                                    setTimeout(function() {
                                        cutImage($(ele), 250, 250);
                                    }, 10)
                                } else {
                                    cutImage($(ele), 250, 250);
                                }
                            })
                        });
                        </script>
                    </div>
                    <!--信息 start-->
                    <div class="member_info_r yh">
                        <div class="ml_ico">
                            <a target="_blank" onmousedown="send_jy_pv2('|1017864_12|');send_jy_pv2('|1017864_13|168103003');" href="http://www.jiayuan.com/meilistar/"><h6>30</h6>
                            <p>魅力值</p></a>
                        </div>
                        <h4>{{ $name }}<span>ID:{{ $id }}</span></h4>
                        <p class="member_ico_box">会员身份：<span class="member_dj">普通会员</span></p>
                        <p class="member_ico_box fn-clear">
                            <span class="wt_ico"><a title="说明" href="javascript:openWindow('','','http://www.jiayuan.com/about.php',560,610)"><i class="member_ico25"></i></a></span>
                            <a style="cursor:pointer" onclick="openWindow('','','http://www.jiayuan.com/profile/reliable.php?uid=138011499',570,390);" onmousedown="send_jy_pv2('|1017864_28|');send_jy_pv2('|1017864_29|168103003');" class="col_blue_1">[ 查看靠谱度 ]</a></p>
                        <p class="col_999">认证信息是会员自愿提供，目前中国无完整渠道确保100%真实，请理性对待。</p>
                        <h6 class="member_name">{{ calcAge($birthday)}}岁，{{ $marriage}}，来自<a onMouseDown="send_jy_pv2('|profile_loc_search|');" href="http://search.jiayuan.com/v2/?key=北京&sex=f&f=search" class="col_blue" target="_blank">北京</a><a onMouseDown="send_jy_pv2('|profile_subloc_search|');" href="http://search.jiayuan.com/v2/?key=朝阳&sex=f&f=search" class="col_blue" target="_blank">朝阳</a></h6>
                        <ul class="member_info_list fn-clear">
                            <li>
                                <div class="fl f_gray_999">学历：</div>
                                <div class="fl pr">
                                    <em>{{ $education }}</em>
                                </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">身高：</div>
                                <div class="fl pr">
                                    <em>{{ $height }}厘米</em>
                                </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">购车：</div>
                                <div class="fl pr">
                                    <em class="info_null" msg_id="6">{{ $car }}</em> </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">月薪：</div>
                                <div class="fl pr">
                                    <em>{{ $income }}</em> </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">住房：</div>
                                <div class="fl pr">
                                    <em class="info_null" msg_id="5">{{ $house }}</em> </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">体重：</div>
                                <div class="fl pr">
                                    <em class="info_null" msg_id="7">--</em>
                                </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">星座：</div>
                                <div class="fl pr">
                                    <em>{{ get_constellation( $birthday) }} </em>
                                </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">民族：</div>
                                <div class="fl pr">
                                    <em class="info_null" msg_id="4">{{ $nation }}</em>
                                </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">属相：</div>
                                <div class="fl pr">
                                    <em>{{ get_animal($birthday) }}</em>
                                </div>
                            </li>
                            <li>
                                <div class="fl f_gray_999">血型：</div>
                                <div class="fl pr">
                                    <em class="info_null" msg_id="3">{{ $bloodtype }}</em>
                                </div>
                            </li>
                        </ul>
                        <div class="fn-clear mt15">
                            <a class="member_btn1" style="cursor:pointer" onclick="window.open('http://www.jiayuan.com/msg/send.php?uhash=f4701255e21ab168ef897563b6560d5c&src=none&cnj=profile3&cache_key=')" onmousedown="send_jy_pv2('|1017864_20|');send_jy_pv2('|1017864_21|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');send_jy_pv2('|1017869_20|');send_jy_pv2('|1017869_21|168103003');">发信</a>
                            <a class="member_btn2" style="cursor:pointer" onclick="openWindow('','','http://www.jiayuan.com/msg/hello.php?type=20&src=none&cache_key=&uhash=f4701255e21ab168ef897563b6560d5c&cnj=profile2',610,600);" onmousedown="send_jy_pv2('|1017864_22|');send_jy_pv2('|1017864_23|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');send_jy_pv2('|1017869_22|');send_jy_pv2('|1017869_23|168103003');">打招呼</a>
                            <a class="member_btn3" style="cursor:pointer" onclick=" openWindow('','','http://gift.jiayuan.com/send_gift.php?uid=138011499',610,610); " onmousedown="send_jy_pv2('|1017864_24|');send_jy_pv2('|1017864_25|168103003');">送礼物</a>
                            <a class="member_btn3" style="cursor:pointer" onclick="add_follow(1);return false;" onmousedown="send_jy_pv2('|1017864_30|');send_jy_pv2('|1017864_31|168103003');">加关注</a>
                        </div>
                    </div>
                    <!--信息  end-->
                </div>
                <!--会员信息 end -->
                <!--广告 start -->
                <div class="banner_box yh">
                    <div class="slide_banner" id="ad_pos_pcweb_11"></div>
                    <div class="ems_bg col_blue">
                        <a target="_blank" href="http://www.jiayuan.com/msgapp/ems/?uid_hash=f4701255e21ab168ef897563b6560d5c&from=profile" class="col_blue">喜欢她就发特快专递&gt;&gt;</a>
                    </div>
                    <div class="jb_text col_999">
                        <a style="cursor:pointer" onclick="show_photo_lj_tc(13);return false;">推荐</a>&nbsp;&nbsp;&nbsp;｜
                        <a href="http://www.jiayuan.com/complain/?uid_hash=f4701255e21ab168ef897563b6560d5c&old=1" target="_blank">举报</a>
                    </div>
                </div>
                <!--广告 end -->
            </div>
        </div>
        <!--top end -->
        <div class="main_1000 mt15 fn-clear">
            <!--左侧内容区 end -->
            <div class="content_705">
                <!--打招呼跟随层 start -->
                <div class="member_layer">
                    <div class="fl pic80" onclick="if(!if_show_pic(1)){return false;}"><a target="_blank" href="http://photo.jiayuan.com/showphoto.php?uid_hash=f4701255e21ab168ef897563b6560d5c"><img class="img_absolute_b" id="gs_ava" src="{{ $avatar_url or asset('img/default_avatar.png') }}" _src="http://at4.jyimg.com/f4/5c/701255e21ab168ef897563b6560d/701255e21_1_avatar_p.jpg"></a></div>
                    <div class="member_layer_con yh">
                        <h4>{{ $name }}<span><a href="http://search.jiayuan.com/v2/?key=北京&sex=f&f=search" class="col_blue" target="_blank">北京</a><a href="http://search.jiayuan.com/v2/?key=朝阳&sex=f&f=search" class="col_blue" target="_blank">朝阳</a>，{{ calcAge($birthday) }}，{{ $height }}CM，{{ $marriage }}</span></h4>
                        <div class="fn-clear">
                            <a class="member_btn1" style="cursor:pointer" onclick="window.open('http://www.jiayuan.com/msg/send.php?uhash=f4701255e21ab168ef897563b6560d5c&src=none&cnj=profile3&cache_key=')" onmousedown="send_jy_pv2('|1017867_10|');send_jy_pv2('|1017867_11|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');send_jy_pv2('|1017869_20|');send_jy_pv2('|1017869_21|168103003');">发信</a>
                            <a class="member_btn2" style="cursor:pointer" onclick="openWindow('','','http://www.jiayuan.com/msg/hello.php?type=20&src=none&cache_key=&uhash=f4701255e21ab168ef897563b6560d5c&cnj=profile2',610,600);" onmousedown="send_jy_pv2('|1017867_12|');send_jy_pv2('|1017867_13|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');send_jy_pv2('|1017864_22|');send_jy_pv2('|1017869_22|');send_jy_pv2('|1017869_23|168103003');">打招呼</a>
                            <a class="member_btn3" style="cursor:pointer" onclick=" openWindow('','','http://gift.jiayuan.com/send_gift.php?uid=138011499',610,610); " onmousedown="send_jy_pv2('|1017867_14|');send_jy_pv2('|1017867_15|168103003');">送礼物</a>
                            <a class="member_btn3" style="cursor:pointer" onmousedown="send_jy_pv2('|1017867_34|');send_jy_pv2('|1017867_35|168103003');" onclick="add_follow(1);return false;">加关注</a>
                        </div>
                    </div>
                </div>
                <!--打招呼跟随层 end -->
                <!--自我介绍 end -->
                <div class="bg_white">
                    <div class="js_box">
                        <h4>自我介绍</h4>
                        <div class="js_text">
                            找一个合适靠谱的人就结婚。 又是一个花钱看信的网站。 生活如此摧残我却坚持努力活着。 很想知道有免费信息吗
                        </div>
                    </div>
                </div>
                <!--自我介绍 end -->
                <!--dna start -->
                <!--自己看自己-->
                <!--浏览同性或者未登录浏览-->
              <!--   <div class="bg_white fn-clear mt15">
                    <div class="zl_DNA_a">
                        <h3><img src="http://images1.jyimg.com/w4/profile_new2/i/personal/DNA_icon.gif"/><img src="http://images1.jyimg.com/w4/profile_new2/i/personal/DNA_text_1.gif"/>爱情DNA</h3>
                        <div class="DNA_xq DNA_content self_tags fn-clear">
                            <h5 class="mt15 f_999"><strong class="f_999">她的兴趣爱好：很想了解她的兴趣爱好吧，</strong><b id="mail_14"><a class="col_blue" onmousedown="send_jy_pv2('|1017867_22|');send_jy_pv2('|1017867_23|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');" onclick="sendmail('mail_14',168103003,138011499)" style="cursor:pointer">邀请她补充</a></b></h5>
                        </div>
                        <div class="DNA_gx_pag DNA_content self_tags fn-clear">
                            <h5 class="mt20">
                        </div>
                        <div class="DNV_rr DNV_rr_mt">
                            <h6>以上内容由<span class="blue bg"> <a href="http://www.jiayuan.com/wenda" onmousedown="send_jy_pv2('|1017996_2|');" target="_blank">懂你</a></span>根据<b class="blue"><a href="http://www.jiayuan.com/wenda" onmousedown="send_jy_pv2('|1017996_2|');" target="_blank">个性匹配问答</a></b>生成</h6>
                        </div>
                    </div>
                </div> -->
                <!--dna end -->
                <!-- 成功故事 start -->
                <div id="qinggan_div" class="bg_white mt15" style="display:none">
                    <div class="js_box">
                        <h4>情感故事</h4>
                        <div></div>
                        <div id="qinggan" class="js_text">
                        </div>
                        <div class="js_a">
                            <a id="qinggan_link" target="_blank" href="#">点击此处查看原文></a>
                        </div>
                    </div>
                </div>
                <!-- 成功故事 end -->
                <!--择偶要求 start -->
                <div class="bg_white mt15">
                    <div class="js_box">
                        <h4>她的择偶要求</h4>
                        <ul class="js_list fn-clear">
                            <li class="fn-clear"><span>年&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;龄：</span>
                                <div class="ifno_r_con">35-43岁之间</div>
                            </li>
                            <li class="fn-clear"><span>身&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;高：</span>
                                <div class="ifno_r_con">156-181厘米</div>
                            </li>
                            <li class="fn-clear"><span>民&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;族：</span>
                                <div class="ifno_r_con">不限</div>
                            </li>
                            <li class="fn-clear"><span>学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;历：</span>
                                <div class="ifno_r_con">不限</div>
                            </li>
                            <li class="fn-clear"><span>相&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;册：</span>
                                <div class="ifno_r_con">不限</div>
                            </li>
                            <li class="fn-clear"><span>婚姻状况：</span>
                                <div class="ifno_r_con">不限</div>
                            </li>
                            <li class="fn-clear">
                                <span>居&nbsp;&nbsp;住&nbsp;地：</span>
                                <div class="ifno_r_con_1"> 北京朝阳</div>
                            </li>
                            <li class="fn-clear"><span>诚&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;信：</span>
                                <div class="ifno_r_con">不限</div>
                            </li>
                            <!--<em class="col_red">*</em>-->
                        </ul>
                        <p class="js_tip_text">标有<span class="col_red">*</span>的择偶条件是必须符合的条件</p>
                    </div>
                </div>
                <!--择偶要求 end -->
                <!--生活方式 start -->
                <div class="bg_white mt15">
                    <div class="js_box">
                        <h4>生活方式</h4>
                        <h6 class="yh">嗜好习惯</h6>
                        <ul class="js_list fn-clear">
                            <p style="color:#999999;" class="gn_text" msg_id="21">你很想了解她的嗜好习惯吧，<a style="cursor:pointer" onmousedown="send_jy_pv2('|1017867_22|');send_jy_pv2('|1017867_23|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');" id="yqbcshxg" class="col_blue">邀请她补充</a></p>
                        </ul>
                        <div class="pt25 fn-clear">
                            <div class="js_tit yh">家务</div>
                            <div class="choice_box pr15 fn-clear">
                                <div class="choice_l"></div>
                                <dl class="choice_m fn-clear">
                                    <dt>水平等级</dt>
                                    <dd>不会</dd>
                                    <dd class="cur">会一些</dd>
                                    <dd>精通</dd>
                                </dl>
                                <div class="choice_r"></div>
                            </div>
                        </div>
                        <div class="about_box fn-clear">
                            <span class="col_999">家务分配：</span>
                            <div class="fl pr">
                                <em class="info_null" msg_id="25">--</em>
                            </div>
                        </div>
                        <div class="pt25 fn-clear">
                            <div class="js_tit yh">宠物</div>
                            <div class="choice_box pr15 fn-clear">
                                <div class="choice_l"></div>
                                <dl class="choice_m fn-clear">
                                    <dt>喜欢程度</dt>
                                    <dd>不喜欢</dd>
                                    <dd class="cur">还可以</dd>
                                    <dd>很喜欢</dd>
                                </dl>
                                <div class="choice_r"></div>
                            </div>
                        </div>
                        <div class="about_box fn-clear">
                            <span class="col_999">关于宠物：</span>
                            <div class="fl pr">
                                <em class="info_null" msg_id="28">--</em>
                            </div>
                        </div>
                    </div>
                </div>
                <!--生活方式 end -->
                <!--经济实力 start -->
                <div class="bg_white mt15">
                    <div class="js_box">
                        <h4>经济实力</h4>
                        <ul class="js_list fn-clear">
                            <li class="fn-clear">
                                <span>月&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;薪：</span>
                                <div class="ifno_r_con">5000～10000元</div>
                            </li>
                            <li class="fn-clear">
                                <span>购&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;房：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="5">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>购&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;车：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="6">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>经济观念：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="10">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>投资理财：</span>
                                <div class="ifno_r_con_1"><em class="info_null" msg_id="8">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>外债贷款：</span>
                                <div class="ifno_r_con_1"><em class="info_null" msg_id="9">--</em></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--经济实力 end -->
                <!--工作学习 start -->
                <div class="bg_white mt15">
                    <div class="js_box">
                        <h4>工作学习</h4>
                        <h6 class="yh">工作</h6>
                        <ul class="js_list fn-clear">
                            <p style="color:#999999;" class="gn_text" msg_id="38">你很想了解她的工作情况吧，<a style="cursor:pointer" onmousedown="send_jy_pv2('|1017867_22|');send_jy_pv2('|1017867_23|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');" id="yqbcgz" class="col_blue">邀请她补充</a></p>
                        </ul>
                        <h6 class="yh">学习</h6>
                        <ul class="js_list fn-clear">
                            <p style="color:#999999;" msg_id="42" class="gn_text">你很想了解她的学习情况吧，<a style="cursor:pointer" onmousedown="send_jy_pv2('|1017867_22|');send_jy_pv2('|1017867_23|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');" id="yqbcxx" class="col_blue">邀请她补充</a></p>
                        </ul>
                    </div>
                </div>
                <!--工作学习 end -->
                <!--婚姻观念 start -->
                <div class="bg_white mt15">
                    <div class="js_box">
                        <h4>婚姻观念</h4>
                        <h6 class="yh">关于自己</h6>
                        <ul class="js_list fn-clear">
                            <li class="fn-clear">
                                <span>籍&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;贯：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="43">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>户&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;口：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="2">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>国&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;籍：</span>
                                <div class="ifno_r_con"><em>中国</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>个性待征：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="45">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>幽&nbsp;&nbsp;默&nbsp;感：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="46">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>脾&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;气：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="47">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>对待感情：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="48">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>是否要小孩：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="49">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>何时结婚：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="50">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>是否能接受异地恋：</span>
                                <div class="ifno_r_con"><em class="info_null" msg_id="51">--</em></div>
                            </li>
                            <li class="fn-clear">
                                <span>理想婚姻：</span>
                                <div class="ifno_r_con_1"><em class="info_null" msg_id="52">--</em></div>
                            </li>
                        </ul>
                        <h6 class="yh mt5">关于家庭</h6>
                        <ul class="js_list fn-clear">
                            <p msg_id="61" style="color:#999999;" class="gn_text">你很想了解她的家庭背景吧，<a style="cursor:pointer" onmousedown="send_jy_pv2('|1017867_22|');send_jy_pv2('|1017867_23|168103003');send_jy_pv2('|1017943_76|168103003');send_jy_pv2('|1017943_80|168103003_138011499');" id="yqbcjt" class="col_blue">邀请她补充</a></p>
                        </ul>
                    </div>
                </div>
                <!--婚姻观念 end -->
                <!--收到的礼物 end -->
                <div class="bg_white mt15">
                    <div class="js_box">
                        <h5><a onmousedown="send_jy_pv2('|1017867_38|');send_jy_pv2('|1017867_39|168103003');" style="display:none;" class="col_blue_1" target="_blank" href="http://www.jiayuan.com/more_gift.php?uid=138011499">查看全部&gt;</a>她收到的礼物</h5>
                        <ul class="gift_list fn-clear">
                            <li>
                                <div class="gift_box" style="cursor:pointer" onclick=" openWindow('','','http://gift.jiayuan.com/send_gift.php?uid=138011499&gid=822',610,610); " onmousedown="send_jy_pv2('|1017867_36|');send_jy_pv2('|1017867_37|168103003');"><img src="http://images.jiayuan.com/msn/index_pic/2012//1330395583.gif" alt="" /></div>
                            </li>
                            <li class="my_gifts" onclick=" openWindow('','','http://gift.jiayuan.com/send_gift.php?uid=138011499',610,610); " onmousedown="send_jy_pv2('|1017867_36|');send_jy_pv2('|1017867_37|168103003');"><a href="#">我要送礼</a></li>
                        </ul>
                    </div>
                </div>
                <!--收到的礼物 end -->
            </div>
            <!--左侧内容区 end -->
            <!--slidebar end -->
            <div class="slidebar bg_white">
                <!--广告位 start -->
                <div id="ad_pos_pcweb_102" w='250' h='60' style="width:100%"></div>
                <!--广告位 end -->
                <!--vip会员 start-->
                <div class="vip_box">
                    <h2 class="yh"> <a class="col_blue" href="http://www.jiayuan.com/usercp/service/upgrade.php?from=menu&src_key=profile_vip_more_A" onmousedown="send_jy_pv2('|1012817_1|');" target="_blank">全部22项特权&gt;</a> VIP会员 </h2>
                    <div class="myVipBd" style="position:relative">
                        <div class="kryb fn-clear">
                            <div class="vipLeft"></div>
                            <div class="vipRight">
                                <p class="vipTi">我寻觅的人</p>
                                <p class="f_gray_666">查看我看过谁</p>
                                <p> 查看最近浏览过的人
                                    <br> 随时了解她的情况 </p>
                            </div>
                        </div>
                        <div class="vipBottom">
                            <p>
                                尝鲜VIP会员1个月 只需<span>16</span>佳缘宝 <a href="http://www.jiayuan.com/usercp/service/st_run.php?product_id=31&src_key=profile_vip" class="vipbtn" target="_blank" onmousedown="send_jy_pv2('|1012817_2|');">马上激活特权</a>
                            </p>
                        </div>
                    </div>
                </div>
                <!--vip会员end-->
                <!--可能感兴趣的人start-->
                <h2 class="slidebar_tit pt20"> <a class="col_blue" href="javascript:getInterestedMenbers();" onmousedown="send_jy_pv2('|1017867_24|');send_jy_pv2('|1017867_25|168103003');">换一组</a> 您可能感兴趣的人 </h2>
                <div id="interested"><img src="http://images1.jyimg.com/w4/profile_new/i/loading_min.gif" /></div>
                <!--可能感兴趣的人end-->
                <!--礼物推荐start-->
                <div style="position:relative">
                    <h2 class="slidebar_tit pt20"> <a class="col_blue"  href="javascript:getCommendGift(138011499);" onmousedown="send_jy_pv2('|1017867_28|');send_jy_pv2('|1017867_29|168103003');">换一组</a> 礼物推荐 </h2>
                    <div id="gift_commend"><img src="http://images1.jyimg.com/w4/profile_new/i/loading_min.gif" />
                        <script type="text/javascript">
                        getCommendGift(138011499);
                        </script>
                    </div>
                </div>
                <!--礼物推荐end-->
            </div>
            <!--slidebar end -->
        </div>
        <!--main end -->
        <!--意见、返回顶部-->
        <div id="goTop" class="goTop">
            <ul>
                <li><a class="feedBack" target="_blank" href="http://www.jiayuan.com/helpcenter/postmail.php">意见反馈</a></li>
                <li><a class="helpCenter" href="http://www.jiayuan.com/helpcenter/list.php?type1=1&type2=1&type3=17" target="_blank">帮助中心</a></li>
                <li><a id="backTop" class="backTop" href="javascript:;">返回顶部</a></li>
            </ul>
        </div>
        <!--意见、返回顶部-->
        <!--弹出层 start-->
        <div id="bg" style="display:none"></div>
        <div class="layer" id="open_window" style="display:none;">
            <div class="layer_box">
                <div class="layer_title" id="open_window_head">
                    <h2><img src="http://images1.jyimg.com/w4/profile_new/i/op_but_close.gif" onclick="closeWindow()" title="关闭" /><span id="open_window_title"></span></h2></div>
                <div class="layer_content" id="open_window_content"></div>
                <div id="iframe_loading" style="position: absolute;"><img id="loading_focus" src="http://images1.jyimg.com/w4/profile_new/i/loading.gif" /></div>
            </div>
        </div>
        <!--弹出层 end-->
        <!--我的地图 start-->
        <div class="layer" id="open_map" style="display:none;">
            <div class="map_layer">
                <div class="mapclose"><a style="cursor:pointer" onclick="closeWindowById('open_map')" class="close"></a>
                    <div class="clear"></div>
                </div>
                <div class="mapiframe">
                    <iframe height="263" width="494" src="" scrolling="no" frameborder="0" id="mapiframe"></iframe>
                </div>
                <div class="mapbotton"><a href="http://www.jiayuan.com/map/result.php?from=profile" target="_blank" onmousedown="send_jy_pv2('|220167_6|');"><img src="http://images1.jyimg.com/w4/profile_new/i/mapbotton.jpg" width="138" height="41" border="0" /></a></div>
            </div>
        </div>
        <!--我的地图 end-->
        <!--弹层-->
        <div class="pop_zl_box" id="public_tc" style="display:none;">
            <div class="pr">
                <div class="pop_colsed" onclick="close_photo_lj_tc(1);" title="关闭"></div>
                <div class="pl20 pt25">
                    <h6 class="f_20 yh">温馨提示：</h6>
                    <p class="f14 f_gray_666 pt5 pb10"></p>
                    <a class="btn_4 mr" onclick="close_photo_lj_tc(1);
                            return false;" style="cursor:pointer">确定</a>
                </div>
            </div>
        </div>
        <!--/弹层-->
        <!--推荐给好友 start-->
        <div class="pop_zl_box" id="open_commend" style="display:none;">
            <div class="pr">
                <div class="tip5" id="tjhy_ts"></div>
                <div class="pop_colsed" onclick="close_photo_lj_tc(3);" title="关闭"></div>
                <div class="pl20 pt25">
                    <h6 class="f_20 yh">将{{ $name }}推荐给好友</h6>
                    <p class="f14 f_gray_666 pt5 pb10">觉得{{ $name }}不错，复制以下链接发送给好友吧！</p>
                    <p class="f14 f_gray_666 pt5 pb10">征友地址：
                        <input type="text" class="tj-input" id="zydz" value="{{ route('user_desc', $id) }}" />
                    </p>
                    <span class="tjts"></span>
                    <a class="btn_4 mr" id="copy_an" style="cursor:pointer">复制</a>
                </div>
            </div>
        </div>
        <!--密码弹层-->
        <div class="pop_password_box" id="aqmm_tc" style="display:none;">
            <div class="pr">
                <div id="mmtc_ts" class="tip3"></div>
                <div class="pop_colsed" onclick="close_photo_lj_tc(2);" title="关闭"></div>
                <h4 class="yh f22 pt20">查看照片需要爱情密码</h4>
                <div class="pop_btm_line clearfix">
                    <div class="hmd_img"><img src="http://images1.jyimg.com/w4/profile_new/i/pop_ico1.png"></div>
                    <div class="fl pl15">
                        <p class="f_14 f_gray_666">对方设置了爱情密码，请输入密码查看照片</p>
                        <div class="clearfix pt5">
                            <div class="fl mt5 pr10">
                                <input name="" type="text" class="pop_btn">
                            </div>
                            <a class="pop_btn_1 fl" onclick="photo_pwd();
                                    return false;" style="cursor:pointer">确认</a>
                        </div>
                        <span class="mmts"></span>
                    </div>
                </div>
                <p class="f14 f_gray_666 pt20 pb10">如果您不知道密码，可以[发信]给对方索取密码</p>
                <div>
                    <textarea name="" cols="" rows="" disabled="disabled" class="pop_msg_btn">你是我心仪的类型，很想知道你的庐山真面目，可以告诉我你照片的爱情密码吗？</textarea>
                </div>
                <a class="btn_4 fl" id="mmfs" style="cursor:pointer">发送</a>
            </div>
        </div>
        <!--/密码弹层-->
        <!--通用弹窗-->
        <div>
            <div id="zptc_ts"></div>
        </div>
        <div id="opa_70" class="mask"></div>

         <div id="profile_good_user_tc" class="jy_lbg_box" style="position: absolute; margin: 0px; padding: 0px; z-index: 9100; left: 659.5px; top: 182.5px;display:none;">
            <div class="new-dredge">
                <div class="new-drTop">
                    <a href="javascript:jy_head_function.lbg_hide();" class="new-drClose"></a>
                    <img src="http://images1.jyimg.com/w4/usercp_new/i/good_user/dredge-diamond.png" class="ie6-img dredge-diamond" alt=""> 开通钻石会员
                </div>
                <div class="new-drMain">
                    <div class="new-drInfo">
                        <p class="new-drP1">尊贵的主人：</p>
                        <p class="new-drP2">
                            白富美优质会员收信量大，开通钻石会员，获得白富美优质会员最高优先级交往机会哦！
                        </p>
                    </div>
                    <div class="new-drBtns">
                        <a href="javascript:jy_head_function.lbg_hide();" class="new-drBtn1">继续单身</a>
                        <a href="http://www.jiayuan.com/usercp/dynmatch/ajax/usercp_good_user.php?buy=1&touid=138011499" class="new-drBtn2" onmousedown="send_jy_pv2('|1035177_18_bfm_o|');">马上联系</a>
                    </div>
                </div>
            </div>
        </div>
        <!--优质会员弹层-->
        <style type="text/css">
        /* 优质会员弹层 */

        .new-dredge {
            width: 540px;
            height: 264px;
            background: #fff;
            font-family: 'Microsoft YaHei';
        }

        .new-drTop {
            position: relative;
            height: 52px;
            text-align: center;
            font-size: 22px;
            color: #fff;
            line-height: 52px;
            background: url({{ "asset('img/home/new-drClose.gif')" }}http://images1.jyimg.com/w4/usercp_new/i/good_user/) no-repeat 0 0;
        }

        .new-drClose {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: url({{ "asset('img/home/new-drClose.gif')" }}) no-repeat center center;
        }

        .dredge-diamond {
            vertical-align: middle;
            margin-right: 3px;
            _margin-top: 15px;
        }

        .new-drInfo {
            padding: 30px;
            font-size: 16px;
            color: #666;
        }

        .new-drP2 {
            line-height: 18px;
            margin-bottom: 6px;
        }

        .new-drP2 {
            text-indent: 2em;
            line-height: 30px;
        }

        .new-drBtns {
            text-align: center;
            font-size: 0;
        }

        .new-drBtn1,
        .new-drBtn2 {
            display: inline-block;
            width: 178px;
            height: 34px;
            line-height: 34px;
            text-align: center;
            font-size: 14px;
        }

        .new-drBtn1:hover,
        .new-drBtn2:hover {
            text-decoration: none;
        }

        .new-drBtn1 {
            color: #8c8c8c;
            background: #fff;
            border: 1px solid #cecece;
            margin-right: 10px;
        }

        .new-drBtn2 {
            color: #fff;
            background: #ff546a;
            border: 1px solid #ff546a;
        }
        </style>
       
</div>
@endsection