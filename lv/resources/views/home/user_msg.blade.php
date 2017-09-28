@extends('layouts.auto_app')

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <title>基本资料_世纪佳缘交友网</title>
    <link href="{{ asset('/css/home/stype.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/home/ad.js') }}"></script>
    <script type="text/javascript">
   
   var w,h,className, type = 0;
   var showDiv;
    function getSrceenWH(){
      w = $(window).width();
      h = $(window).height();
      $('#dialogBg').width(w).height(h);
    }

    window.onresize = function(){  
      getSrceenWH();
    }  
    $(window).resize();  

    $(function(){
      getSrceenWH();
      $('.claseDialogBtn').click(function(){
            closeDialog();
      });

       $(".ico0").mouseover(function() {
            $("#info_div").show();
        });
       $('.span101203_1').text({{ $score}} + '分');
       $('#show_nickname').text("{{ $name }}");
       $('#show_birthday').text("{{ $birthday }}");
       $('#show_animal').text("{{ get_animal($birthday)}}");
       $('#xingzuo').text("{{ get_constellation($birthday) }}");
       $('#height').val({{$height}});
       $('#education').val({{$education}});
       $('#marriage_status').val({{$marriage_status}});
       $('#bloodtype').val({{ $bloodtype }});
       $('#nation').val({{ $nation }});
       $('#income').val({{ $income }});
       $('#house').val({{ $house }});
       $('#car').val({{ $car }});
    });

    function closeDialog() {
          $('#dialogBg').fadeOut(300,function(){
          $('#dialog').addClass('bounceOutUp').fadeOut();
          $(showDiv).hide();
          $('#mdy_height_tips').hide();
        });
    }

    var ie = navigator.userAgent.toLowerCase().indexOf('msie');
    var isChanged = false;

    function initAjax() {
        var ajax = false;
        if (window.XMLHttpRequest) {
            ajax = new XMLHttpRequest();
        } else {
            try {
                ajax = new ActiveXObject('Msxml2.XMLHTTP');
            } catch (e) {
                ajax = new ActiveXObject('Microsoft.XMLHTTP');
            }
        }
        return ajax;
    }

    function show_category(_id) {
        _obj = document.getElementById(_id);
        if (_obj.style.display == "none") {
            _obj.style.display = "";
        } else {
            _obj.style.display = "none";
        }
    }

     //判断字符串长度
    function strlen(str) {
        var len = 0;
        for (var i = 0; i < str.length; i++) {
            var c = str.charCodeAt(i);
            //单字节加1   
            if ((c >= 0x0001 && c <= 0x007e) || (0xff60 <= c && c <= 0xff9f)) {
                len++;
            } else {
                len += 2;
            }
        }
        return len;
    }

    function DBC2SBC(str) {
        var i;
        var result = '';
        for (i = 0; i < str.length; i++) {
            code = str.charCodeAt(i);

            if (code == 12290) {
                result += String.fromCharCode(46);
            } else if (code == 183) {
                result += String.fromCharCode(64);
            } else if (code >= 65281 && code < 65373) {
                result += String.fromCharCode(str.charCodeAt(i) - 65248);
            } else {
                result += str.charAt(i);
            }
        }
        return result;
    }

    function save_profile(str) {
        $('#mdy_nickname_tips').hide();
        var get_str = '';
        var newMsg = '';
        var key = '';
        //如果修改昵称    
        if (str == 'nickname') {
            var nickname = DBC2SBC($('#new_nickname').val());
            var nickname_len = strlen(nickname);
            if ({{ $name }} == nickname) {
                $('#mdy_nickname_tips').show();
                $('#mdy_nickname_tips').html('温馨提示：您还没有修改昵称~');
                return false;
            }
            if (nickname_len < 2 || nickname_len > 20) {
                $('#mdy_nickname_tips').show();
                $('#mdy_nickname_tips').html('温馨提示：昵称最少2个字母或1个汉字，最多10个汉字或20个字母~');
                return false;
            }
            newMsg = encodeURI(nickname);
            key = 'name';
        //    get_str = 'type=' + str + '&new_nickname=' + encodeURI(nickname);
        } else if (str == 'height') //如果修改身高
        {
            var height = $('#new_height').val();
            if (height > 226 || height < 130) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您输入的身高不正确~');
                return false;
            }
            if ($('#height').val() == height) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您还没有修改身高~');
                return false;
            }
            //get_str = 'type=' + str + '&new_height=' + height;
            newMsg = height;
            key = 'height';
        } else if (str == 'education') //如果修改学历
        {
            var education = $('#new_education').val();
            if ($('#education').val() == education) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您还没有修改学历~');
                return false;
            }
            //get_str = 'type=' + str + '&new_height=' + height;
            newMsg = education;
            key = 'education';
        } else if (str == 'marriage_status') //如果修改学历
        {
            var marriage_status = $('#new_marriage_status').val();
            if ($('#marriage_status').val() == marriage_status) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您还没有修改婚姻状态~');
                return false;
            }
            //get_str = 'type=' + str + '&new_height=' + height;
            newMsg = marriage_status;
            key = 'marriage_status';
        }  else if (str == 'children') //如果修改有无子女
        {
            var children = $('#new_children').val();
            if ($('#children').val() == children) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您还没有修改有无子女~');
                return false;
            }
            //get_str = 'type=' + str + '&new_height=' + height;
            newMsg = children;
            key = 'children';
        }  else if (str == 'bloodtype') //如果修改血型
        {
            var bloodtype = $('#new_bloodtype').val();
            if ($('#bloodtype').val() == bloodtype) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您还没有修改血型~');
                return false;
            }
            //get_str = 'type=' + str + '&new_height=' + height;
            newMsg = bloodtype;
            key = 'bloodtype';
        }  else if (str == 'nation') //如果修改民族
        {
            var nation = $('#new_nation').val();
            if ($('#nation').val() == nation) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您还没有修改民族~');
                return false;
            }
            //get_str = 'type=' + str + '&new_height=' + height;
            newMsg = nation;
            key = 'nation';
        }  else if (str == 'house') //如果修改月薪
        {
            var house = $('#new_house').val();
            if ($('#house').val() == house) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您还没有修改月薪~');
                return false;
            }
            //get_str = 'type=' + str + '&new_height=' + height;
            newMsg = house;
            key = 'house';
        }  else if (str == 'car') //如果修改民族
        {
            var new_car = $('#new_car').val();
            if ($('#car').val() == new_car) {
                $('#mdy_height_tips').show();
                $('#mdy_height_tips').html('温馨提示：您还没有修改车辆信息~');
                return false;
            }
            //get_str = 'type=' + str + '&new_height=' + height;
            newMsg = new_car;
            key = 'car';
        } else if (str == 'age') //如果修改年龄
        {
            var new_age = $('#modify_age_want').html();
            if (new_age == '') {
                return false;
            }
            get_str = 'type=' + str;
        } else if (str == 'location') { //修改地区
            var profile_location = $('#new_profile_location').val();
            var profile_sublocation = $('#new_profile_sublocation').val();
            var change_area_reason = $('#change_area_reason').val();
            if (profile_location == '' || profile_location == '0' || profile_sublocation == '' || profile_sublocation == '0' || profile_sublocation.substr(2, 2) == '00') {
                $('#mdy_location_tips').show();
                $('#mdy_location_tips').html('温馨提示：您还没有修改地区~');
                return false;
            }
            if (change_area_reason == '' || change_area_reason == undefined) {
                $('#mdy_location_tips').show();
                $('#mdy_location_tips').html('温馨提示：您还没有选择修改原因~');
                return false;
            }
            get_str = 'type=' + str + '&work_location=' + profile_location + '&work_sublocation=' + profile_sublocation + '&change_area_reason=' + change_area_reason;
        } else if (str == 'income') //修改收入
        {
            var new_income = $('#new_income').val();
            if (new_income == '' || $('#income').val() == new_income) {
                $('#mdy_income_tips').show();
                $('#mdy_income_tips').html('温馨提示：您还没有修改收入~');
                return false;
            }
            if ((new_income - '10') > 10) {
                $('#mdy_income_tips').show();
                $('#mdy_income_tips').html('温馨提示：每次只能向上调一个档次~');
                return false;
            }
            newMsg = new_income;
            key = 'income';
        }
        alert(newMsg);
        var data = {};
        data[key] = newMsg;
        data["_token"] = "{!! csrf_token() !!}";
        $.ajax({
            type: "post",
            url: "{{ route('edit_msg') }}",
            dataType: "json",
            data: data,
            error:function(msg){ //处理出错的信息  
                  var errormessage="再试一次";  
                  $(".loginerror").html(errormessage);  
              },  
            success: function(data) {
                closeDialog();
                if (data.status == 1) {
                    if (str == 'nickname') {
                        $('#show_nickname').html(newMsg);
                     //   $('#modify_nickname_tag').hide();
                      //  $('#show_nickname').css('color', '');
                    } else if (str == 'height') {
                        /*$('#height option').attr("selected", false);
                        $('#height option[value=' + height + ']').attr("selected", true);
                        $('#modify_height_tag').hide();
                        $('#height').attr('disabled', 'disabled');
                        $('#height').css('color', '');*/
                        $('#height').val(newMsg);
                    } else if (str == 'age') {
                        send_jy_pv2('|1027534_11|');
                        $('#modify_age_tag').html(new_age);
                        $('#modify_age_tag').css('color', '');
                        setTimeout("jy_head_function.lbg_hide()", 3000);
                    } else if (str == 'location') {
                        $('#profile_location option').attr("selected", false);
                        $('#profile_sublocation option').attr("selected", false);
                        $('#profile_location option[value=' + profile_location + ']').attr("selected", true);
                        $('#profile_sublocation option[value=' + profile_sublocation + ']').attr("selected", true);
                        $('#modify_location_tag').hide();
                        $('#profile_location').attr('disabled', 'disabled');
                        $('#profile_sublocation').attr('disabled', 'disabled');
                        $('#profile_location').css('color', '');
                        $('#profile_sublocation').css('color', '');
                        send_jy_pv2('|1027534_13|');
                    } else if (str == 'income') {
                        // $('#income option').attr("selected", false);
                        // $('#income option[value=' + new_income + ']').attr("selected", true);
                        // $('#modify_income_tag').hide();
                        // $('#income').attr('disabled', 'disabled');
                        // $('#income').css('color', '');
                        // send_jy_pv2('|1027534_14|');
                        $('#income').val(newMsg);
                    } else if (str == "education") {
                        $('#education').val(newMsg);
                    } else if (str == "marriage_status") {
                        $('#marriage_status').val(newMsg);
                    } else if (str == "children") {
                        $('#children').val(newMsg);
                    } else if (str == "bloodtype") {
                        $('#bloodtype').val(newMsg);
                    } else if (str == "nation") {
                        $('#nation').val(newMsg);
                    } else if (str == "house") {
                        $('#house').val(newMsg);
                    } else if (str == "car") {
                        $('#car').val(newMsg);
                    }
                 //   setTimeout("jy_head_function.lbg_hide()", 3000);
                } else if (data.status == 0) {
                    alert('修改失败');
                }
                // $('#mdy_' + str + '_tips').html(data.msg);
                // $('#mdy_' + str + '_tips').show();
            }
        });
    }

    function openDiv(_id, _width, _height) {
        var m = "mask";
        if (document.getElementById(m)) document.body.removeChild(document.getElementById(m));
        var newMask = document.createElement("div");
        newMask.id = m;
        newMask.style.position = "absolute";
        newMask.style.zIndex = "1";
        _scrollWidth = Math.max(document.body.scrollWidth, document.documentElement.scrollWidth);
        _scrollHeight = Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        newMask.style.width = _scrollWidth + "px";
        newMask.style.height = _scrollHeight + "px";
        newMask.style.top = "0px";
        newMask.style.left = "0px";
        newMask.style.background = "#33393C";
        newMask.style.filter = "alpha(opacity=40)";
        newMask.style.opacity = "0.30";
        document.body.appendChild(newMask);

        _width = _width ? _width : 488;
        _height = _height ? _height : 314;
        showDiv = document.getElementById(_id);
        showDiv.style.display = "block";
        showDiv.style.position = "absolute";
        showDiv.style.zIndex = "9999";
        showDivWidth = _width;
        showDivHeight = _height;
        var scrollTop = document.documentElement.scrollTop || window.pageYOffset || 0;
        showDiv.style.top = String(scrollTop + document.documentElement.clientHeight / 2 - showDivHeight / 2) + "px";
        showDiv.style.left = (document.documentElement.scrollLeft + document.documentElement.clientWidth / 2 - showDivWidth / 2) + "px";
    }

    function closeDiv(_id) {
        document.getElementById("mask").style.display = "none";
        document.getElementById(_id).style.display = "none";
    }

    function select_changed() {
        isChanged = true;
    }

    function change(_num) {
        isChanged = true;
    }

    function check_count(_obj, _num) {
        var chks = document.getElementsByName(_obj.name);
        var count = 0;
        for (var i = 0; i < chks.length; i++) {
            if (chks[i].checked == true) {
                count++;
            }
            if (count > _num) {
                _obj.checked = false;
                alert('最多只能选择' + _num + '项');
                return false;
            }
        }
    }
    var AJXhttp;

    function build_second(first_value, second_id, second_array) {
        document.getElementById(second_id).innerHTML = "";
        var k = 1000;
        for (key in second_array[first_value]) {
            var sOption = document.createElement("OPTION");
            sOption.text = second_array[first_value][key];
            sOption.value = key;
            document.getElementById(second_id).options.add(sOption, k);
        }
        k--;
    }

    function build_select(first_id, second_id, first_array, second_array, def_value) {
        if (def_value == "" || def_value == "0") {
            var k = 1000;
            for (key in first_array) {
                var sOption = document.createElement("OPTION");
                sOption.text = first_array[key];
                sOption.value = key;
                document.getElementById(first_id).options.add(sOption, k);
                k--;
            }
        } else {
            pro_key = def_value.substr(0, 2);
            var k = 1000;
            for (key in first_array) {
                var sOption = document.createElement("OPTION");
                sOption.text = first_array[key];
                sOption.value = key;
                if (pro_key == key) {
                    sOption.id = "sele_pro" + first_id;
                }
                document.getElementById(first_id).options.add(sOption, k);
                k--;
            }
            document.getElementById("sele_pro" + first_id).selected = true;
            var k = 1000;
            for (key in second_array[pro_key]) {
                var sOption = document.createElement("OPTION");
                sOption.text = second_array[pro_key][key];
                sOption.value = key;
                if (def_value == key) {
                    sOption.id = "sele_city" + second_id;
                }
                document.getElementById(second_id).options.add(sOption, k);
            }
            k--;
            document.getElementById("sele_city" + second_id).selected = true;
        }
    }

    function show_info_div(_type, _id) {
        var _info_div = document.getElementById(_id);
        var _info_div_content = document.getElementById(_id + "_content");
        if (ie > 0) {
            var _info_div_top = 220;
        } else {
            var _info_div_top = 210;
        }
        var _info_div_left = 290;
        var _content = "";
        var _up_top = 0;

        switch (_type) {
            case 1:
                if (ie > 0) {
                    _up_top = 56;
                } else {
                    _up_top = 68;
                }
                $("#profile_sublocation").next().after($("#info_div"));
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>1、您距离上次修改所在地未满30天</dd><dd>2、您正在“光明榜”服务期内</dd>';
                break;
            case "usercp_profile_incometime":
                if (ie > 0) {
                    _up_top = 170;
                } else {
                    _up_top = 180;
                }
                $("#income").after($("#info_div"));
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>30天内只能向上调整一次月薪</dd>';
                break;
            case "usercp_profile_incometoomuch":
                if (ie > 0) {
                    _up_top = 170;
                } else {
                    _up_top = 180;
                }
                $("#income").after($("#info_div"));
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>每次只能向上调一个档次</dd>';
                break;
            case "usercp_profile_valueerr":
                if (ie > 0) {
                    _up_top = 314;
                } else {
                    _up_top = 327;
                }
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>错误的选项</dd>';
                break;
            case "idcard":
                if (ie > 0) {
                    _up_top = 343;
                } else {
                    _up_top = 355;
                }
                $("#id_card_id").after($("#info_div"));
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>' + alertmsg + '</dd>';
                break;
            case "mobile":
                if (ie > 0) {
                    _up_top = 615;
                } else {
                    _up_top = 564;
                }
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>请填写正确的手机号码</dd>';
                break;
            case "qq":
                if (ie > 0) {
                    _up_top = 398;
                } else {
                    _up_top = 410;
                }
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>请填写正确的qq</dd>';
                break;
            case "msn":
                if (ie > 0) {
                    _up_top = 428;
                } else {
                    _up_top = 440;
                }
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>请填写正确的msn</dd>';
                break;
            default:
                if (ie > 0) {
                    _up_top = 56;
                } else {
                    _up_top = 68;
                }
                $("#profile_sublocation").next().after($("#info_div"));
                _content = '<dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt><dd>1、您距离上次修改所在地未满30天</dd><dd>2、您正在“光明榜”服务期内</dd>';
        }

        // _info_div.style.top = _info_div_top + _up_top + "px";
        // _info_div.style.left = _info_div_left + "px";
        _info_div_content.innerHTML = _content;
        _info_div.style.display = "";
    }

    function validate(_id, _value) {
        AJXhttp = initAjax();

        AJXhttp.open("GET", "profile_validate.php?type=" + _id + "&value=" + _value, true);
        AJXhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        AJXhttp.onreadystatechange = function() { validate_callback(_id) };
        AJXhttp.send(null);
    }

    function validate_callback(_id) {
        if (AJXhttp.readyState == 4) {
            var text = AJXhttp.responseText;
            if (text != "success") {
                show_info_div(text, "info_div");
                document.getElementById(_id).value = 30;
            }
        }
    }

    function check_post() {
        var form_obj = document.getElementById("form_base");
        var can_form = false;
        if (document.getElementById("change_area_div").style.display != "none") {
            var radio_obj = document.getElementsByName("change_area_reason");
            for (var i = 0; i < radio_obj.length; i++) {
                if (radio_obj[i].checked == true) {
                    form_obj.change_area_reason.value = radio_obj[i].value;
                    can_form = true;
                }
            }

            if (!can_form) {
                alert("请选择变更原因");
                return false;
            }
        }

        if (sub_idcard == false) {
            alert("请填写正确的身份证！");
            document.getElementById("id_card_id").focus();
            return false;
        }

        if (sub_mobile == false) {
            alert("请填写正确的手机号码！");
            document.getElementById("mobile").focus();
            return false;
        }

        if (sub_qq == false) {
            alert("请填写正确的qq！");
            document.getElementById("qq").focus();
            return false;
        }

        if (sub_msn == false) {
            alert("请填写正确的msn！");
            document.getElementById("msn").focus();
            return false;
        }

        if (document.getElementById("profile_location").value == '0' || document.getElementById("profile_sublocation").value.substr(2, 2) == '00' || document.getElementById("profile_sublocation").value.substr(2, 2) == '') { //省市有一个不选择都不可以
            alert("请选择正确的所在地区！");
            document.getElementById("profile_location").focus();
            return false;
        }

        if (document.getElementById("home_location").value != '0' && (document.getElementById("home_sublocation").value.substr(2, 2) == '00' || document.getElementById("home_sublocation").value.substr(2, 2) == '')) { //如果选择了省，市必须得选
            alert("请选择正确的家乡所在地区！");
            document.getElementById("home_sublocation").focus();
            return false;
        }

        if (can_form) {
            openDiv('to_change_match');
            return false;
        }
    }

    function skip() {
        if (isChanged) {
            if (confirm("您尚有未保存的资料，确定要离开吗？")) {
                location.href = 'note.php';
            }
        } else {
            location.href = 'note.php';
        }
    }

    function check_mobile(_input) {
        if (_input == "") {
            return true;
        }

        var reg2 = /^((13[0-9]{9})|(14[0-9]{9})|(15[0-9]{9})|(18[0-9]{9}))$/;
        if (!_input.match(reg2)) {
            return false;
        }
        return true;
    }

    function check_qq(_input) {
        if (_input == "") {
            return true;
        }

        var reg2 = /[0-9]{5,11}/;
        if (!_input.match(reg2)) {
            return false;
        }
        return true;
    }

    //检测email
    function check_msn(_input) {
        if (_input == "") {
            return true;
        }

        var reg2 = /^[_a-zA-Z0-9\-\.]+@([\-_a-zA-Z0-9]+\.)+[a-zA-Z0-9]{2,3}$/;
        if (!_input.match(reg2)) {
            return false;
        }
        return true;
    }

    var sub_idcard = true;
    var sub_mobile = true;
    var sub_qq = true;
    var sub_msn = true;

    function check_validate(_type, _input) {
        switch (_type) {
            case "idcard":
                if (!checkIdcard(_input, "yes")) {
                    show_info_div("idcard", "info_div");
                    sub_idcard = false;
                    $('#id_card_id').after($('#info_div'));
                } else {
                    document.getElementById("info_div").style.display = "none";
                    sub_idcard = true;
                }
                break;
            case "mobile":
                if (!check_mobile(_input)) {
                    show_info_div("mobile", "info_div");
                    sub_mobile = false;
                    $('#mobile').after($('#info_div'));
                } else {
                    document.getElementById("info_div").style.display = "none";
                    sub_mobile = true;
                }
                break;
            case "qq":
                if (!check_qq(_input)) {
                    show_info_div("qq", "info_div");
                    sub_qq = false;
                    $('#qq').after($('#info_div'));
                } else {
                    document.getElementById("info_div").style.display = "none";
                    sub_qq = true;
                }
                break;
            case "msn":
                if (!check_msn(_input)) {
                    show_info_div("msn", "info_div");
                    sub_msn = false;
                    $('#msn').after($('#info_div'));
                } else {
                    document.getElementById("info_div").style.display = "none";
                    sub_msn = true;
                }
                break;
        }
    }

    function form_submit() {
        closeDiv('to_change_match');
        document.getElementById('form_base').submit();
    }
    //显示手机验证窗口
    function show_verify_phone(str) {
        var data = is_verify_phone();
        if (data == 1) {
            show_modity_profile(str);
        } else {
            jy_head_function.lbg_show('mdy_mobile', { jy_tpl: true, jy_tpl_close: true, jy_tpl_title: "手机验证" });
        }
    }

    //查看是否验证手机号码
    function is_verify_phone() {
        var result = 2;
        $.ajax({
            type: "get",
            url: "/usercp/modify_profile.php?type=is_verify_phone",
            async: false,
            success: function(msg) {
                result = msg;
            }
        });
        return result;
    }
    //显示身份认证窗口
    function show_verify_idcard() {
        $.ajax({
            type: "get",
            url: "/usercp/modify_profile.php?type=is_verify_certify",
            data: {},
            success: function(data) {
                if (data == 1) {
                    show_modity_profile('age');
                } else {
                    jy_head_function.lbg_show('mdy_idcard', { jy_tpl: true, jy_tpl_close: true, jy_tpl_title: "身份验证" });
                }
            }
        });
    }

    function show_modity_profile(str) {
        $('#dialogBg').fadeIn(300);
        $('#dialog').removeAttr('class').addClass('animated bounceIn').fadeIn();
        if (str == 'nickname') {
           // jy_head_function.lbg_show('mdy_nickname', { jy_tpl: true, jy_tpl_close: true, jy_tpl_title: "修改昵称" });
            showDiv = $('#mdy_nickname');
            $('#mdy_nickname').show();
        } else if (str == 'age') {
            $('#mdy_location').show();
            showDiv = $('#mdy_location');
            // jy_head_function.lbg_show('mdy_birthday', { jy_tpl: true, jy_tpl_close: true, jy_tpl_title: "修改年龄" });
        } else if (str == 'height') {
            $('#mdy_height').show();
            showDiv = $('#mdy_height');
            $('#new_height').val($('#height').val());
            // jy_head_function.lbg_show('mdy_height', { jy_tpl: true, jy_tpl_close: true, jy_tpl_title: "修改身高" });
            
        } else if (str == 'location') {
            $('#mdy_location').show();
            showDiv = $('#mdy_location');
            // jy_head_function.lbg_show('mdy_location', { jy_tpl: true, jy_tpl_close: true, jy_tpl_title: "修改所在地" });
            // init_location(11, 1108, 'new_profile');
        } else if (str == 'income') {
            $('#mdy_income').show();
            showDiv = $('#mdy_income');
            $('#new_income').val($('#income').val());
            // jy_head_function.lbg_show('mdy_income', { jy_tpl: true, jy_tpl_close: true, jy_tpl_title: "修改收入" });
        } else if (str == 'education') {
            $('#mdy_education').show();
            showDiv = $('#mdy_education');
            $('#new_education').val($('#education').val());
        } else if (str == 'marriage_status') {
            $('#mdy_marriage_status').show();
            showDiv = $('#mdy_marriage_status');
            $('#new_marriage_status').val($('#marriage_status').val());
        } else if (str == 'children') {
            $('#mdy_children').show();
            showDiv = $('#mdy_children');
            $('#new_children').val($('#children').val());
        } else if (str == 'bloodtype') {
            $('#mdy_bloodtype').show();
            showDiv = $('#mdy_bloodtype');
            $('#new_bloodtype').val($('#bloodtype').val());
        } else if (str == 'nation') {
            $('#mdy_nation').show();
            showDiv = $('#mdy_nation');
            $('#new_nation').val($('#nation').val());
        } else if (str == 'house') {
            $('#mdy_house').show();
            showDiv = $('#mdy_house');
            $('#new_house').val($('#house').val());
        } else if (str == 'car') {
            $('#mdy_car').show();
            showDiv = $('#mdy_car');
            $('#new_car').val($('#car').val());
        }
    }
    </script>

    <div class="my_infomation">
        <div class="navigation"><a href=" {{ route('home') }}">个人中心</a>&nbsp;&gt;&nbsp;基本资料</div>
        <div class="borderbg"><img src="http://images1.jyimg.com/w4/usercp/i/i520/border_top.jpg" /></div>
        <div class="info_content">
            <!-- 左侧开始 -->
            <div class="info_left">
                <ul>
                    <li class="on"><a href="javascript:;">基本资料</a></li>
                    <li class="ok" onmousedown="send_jy_pv2('editprofile|category_note|m|168103003');"><a href="http://www.jiayuan.com/usercp/note.php">内心独白</a></li>
                    <li class="mark" onmousedown="send_jy_pv2('editprofile|category_photo|m|168103003');"><a href="http://www.jiayuan.com/usercp/photo.php">我的照片</a></li>
                    <li class="mark" onmousedown="send_jy_pv2('editprofile|category_map|m|168103003');"><a href="http://www.jiayuan.com/usercp/profile.php?action=map">我的地图</a></li>
                    <li onClick="show_category('detail_hidden');" class=""><a href="javascript:;">详细资料</a></li>
                    <li id="detail_hidden" class="hidden_li">
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=economy" onmousedown="send_jy_pv2('editprofile|category_economy|m|168103003');">经济实力</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=life" onmousedown="send_jy_pv2('editprofile|category_life|m|168103003');">生活方式</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=work" onmousedown="send_jy_pv2('editprofile|category_work|m|168103003');">工作学习</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=body" onmousedown="send_jy_pv2('editprofile|category_body|m|168103003');">外貌体型</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=marriage" onmousedown="send_jy_pv2('editprofile|category_marriage|m|168103003');">婚姻观念</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=interest" onmousedown="send_jy_pv2('editprofile|category_interest|m|168103003');">兴趣爱好</a>
                    </li>
                </ul>
                <div class="return_index">
                    <a class="return_jy" href="{{ route('home') }}" onmousedown="send_jy_pv2('editprofile|return_home|m|168103003');">返回我的佳缘</a>
                </div>
            </div>
            <div class="info_center">
                <div class="title">
                    <strong>基本资料</strong>
                </div>
                <div class="mid_border">
                    <div class="base_infomation" id="w_base_infomation">
                        <p class="info_note">资料越完善，同等条件我们将优先推荐您哦~</p>
                         <form id="form_base" name="form_base" action="{{ route('edit_msg') }}" method="post" onsubmit="return check_post();">
                            <!-- 基本资料 -->
                            <div class="base_info">
                                <h2>为保证资料真实有效，灰色字体信息不得随意修改，<a href="http://www.jiayuan.com/helpcenter/list.php?type1=1&type2=1&type3=17#art413 " target="_blank">查看修改技巧</a>。<!--，如有需要，请<a href="http://www.jiayuan.com/helpcenter/postmail.php" target="_blank" onmousedown="send_jy_pv2('editprofile|contract_service|f|113987332');">联系客服</a>。--></h2>
                                <table colspan="3" width="450" cellpadding="0" cellspacing="0" class="f-table">
                                    <tr>
                                        <td class="item"><span style="color:#666;">昵称：</span></td>
                                        <!--如果手机没有验证-->
                                        <td id="show_nickname" style="color:#666;">
                                            <a href="javascript:;" class="tips_link" id="modify_nickname_tag" onclick="show_modity_profile('nickname');">修改</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span style="color:#666;">性别：</span></td>
                                        <td id="show_sex">男</td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">出生日期：</span></td>
                                        <td id="show_birthday" style="color:#666;">1980-08-11
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"> <span style="color:#666;">生肖：</span></td>
                                        <td id="show_animal"></td>
                                    </tr>
                                    <tr>
                                        <td class="item"> <span style="color:#666;">星座：</span></td>
                                        <td id="xingzuo"></td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">身高：</span></td>
                                        <td id="show_height">
                                            <select name="height" id="height" class="select1" onChange="select_changed()" disabled="disabled;" style="color: #666;">
                                                <option label="130" value="130">130</option>
                                                <option label="131" value="131">131</option>
                                                <option label="132" value="132">132</option>
                                                <option label="133" value="133">133</option>
                                                <option label="134" value="134">134</option>
                                                <option label="135" value="135">135</option>
                                                <option label="136" value="136">136</option>
                                                <option label="137" value="137">137</option>
                                                <option label="138" value="138">138</option>
                                                <option label="139" value="139">139</option>
                                                <option label="140" value="140">140</option>
                                                <option label="141" value="141">141</option>
                                                <option label="142" value="142">142</option>
                                                <option label="143" value="143">143</option>
                                                <option label="144" value="144">144</option>
                                                <option label="145" value="145">145</option>
                                                <option label="146" value="146">146</option>
                                                <option label="147" value="147">147</option>
                                                <option label="148" value="148">148</option>
                                                <option label="149" value="149">149</option>
                                                <option label="150" value="150">150</option>
                                                <option label="151" value="151">151</option>
                                                <option label="152" value="152">152</option>
                                                <option label="153" value="153">153</option>
                                                <option label="154" value="154">154</option>
                                                <option label="155" value="155">155</option>
                                                <option label="156" value="156">156</option>
                                                <option label="157" value="157">157</option>
                                                <option label="158" value="158">158</option>
                                                <option label="159" value="159">159</option>
                                                <option label="160" value="160">160</option>
                                                <option label="161" value="161">161</option>
                                                <option label="162" value="162">162</option>
                                                <option label="163" value="163">163</option>
                                                <option label="164" value="164">164</option>
                                                <option label="165" value="165">165</option>
                                                <option label="166" value="166">166</option>
                                                <option label="167" value="167">167</option>
                                                <option label="168" value="168">168</option>
                                                <option label="169" value="169">169</option>
                                                <option label="170" value="170">170</option>
                                                <option label="171" value="171">171</option>
                                                <option label="172" value="172">172</option>
                                                <option label="173" value="173">173</option>
                                                <option label="174" value="174">174</option>
                                                <option label="175" value="175" selected="selected">175</option>
                                                <option label="176" value="176">176</option>
                                                <option label="177" value="177">177</option>
                                                <option label="178" value="178">178</option>
                                                <option label="179" value="179">179</option>
                                                <option label="180" value="180">180</option>
                                                <option label="181" value="181">181</option>
                                                <option label="182" value="182">182</option>
                                                <option label="183" value="183">183</option>
                                                <option label="184" value="184">184</option>
                                                <option label="185" value="185">185</option>
                                                <option label="186" value="186">186</option>
                                                <option label="187" value="187">187</option>
                                                <option label="188" value="188">188</option>
                                                <option label="189" value="189">189</option>
                                                <option label="190" value="190">190</option>
                                                <option label="191" value="191">191</option>
                                                <option label="192" value="192">192</option>
                                                <option label="193" value="193">193</option>
                                                <option label="194" value="194">194</option>
                                                <option label="195" value="195">195</option>
                                                <option label="196" value="196">196</option>
                                                <option label="197" value="197">197</option>
                                                <option label="198" value="198">198</option>
                                                <option label="199" value="199">199</option>
                                                <option label="200" value="200">200</option>
                                                <option label="201" value="201">201</option>
                                                <option label="202" value="202">202</option>
                                                <option label="203" value="203">203</option>
                                                <option label="204" value="204">204</option>
                                                <option label="205" value="205">205</option>
                                                <option label="206" value="206">206</option>
                                                <option label="207" value="207">207</option>
                                                <option label="208" value="208">208</option>
                                                <option label="209" value="209">209</option>
                                                <option label="210" value="210">210</option>
                                                <option label="211" value="211">211</option>
                                                <option label="212" value="212">212</option>
                                                <option label="213" value="213">213</option>
                                                <option label="214" value="214">214</option>
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
                                             <a href="javascript:;" class="tips_link" id="modify_height_tag" onclick="show_modity_profile('height');">修改</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">学历：</span></td>
                                        <td>
                                          <select style="width:135px" id="education" disabled="disabled" style="color: #666">
                                                <option value="0">-未选择-</option>
                                                <option value="1">小学</option>
                                                <option value="2">初中</option>
                                                <option value="3">高中</option>
                                                <option value="4">专科</option>
                                                <option value="5">本科</option>
                                                <option value="6">硕士</option>
                                                <option value="7">博士</option>
                                                <option value="8">博士后</option>
                                            </select>
                                             <a href="javascript:;" class="tips_link" id="modify_education_tag" onclick="show_modity_profile('education');">修改</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">婚姻状况：</span></td>
                                        <td id="show_marriage_status">
                                             <select style="width:135px" id="marriage_status" disabled="disabled" style="color:#666">
                                                <option value="0">-未选择-</option>
                                                <option value="1">未婚</option>
                                                <option value="2">离异</option>
                                                <option value="3">丧偶</option>
                                            </select>
                                            <a href="javascript:;" class="tips_link" id="modify_marriage_status_tag" onclick="show_modity_profile('marriage_status');">修改</a>
                                        </td>
                                          
                                    </tr>
                                    <tr>
                                        <td class="item"><a id="l_pos" name="l_pos"></a><span class="ico_stars">*</span><span style="color:#666;">有无子女：</span></td>
                                        <td>
                                            <select name="children" id="children" onChange="select_changed();" style="color:#666;" disabled="disabled">
                                                <option value="0">--请选择--</option>
                                                <option label="无小孩" value="1">无小孩</option>
                                                <option label="有小孩归自己" value="2" selected="selected">有小孩归自己</option>
                                                <option label="有小孩归对方" value="3">有小孩归对方</option>
                                            </select>
                                        <a href="javascript:;" class="tips_link" id="modify_nickname_tag" onclick="show_modity_profile('children');">修改</a>
                                        </td>
                                          
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">所在地区：</span></td>
                                        <td>
                                            <!--如果手机没有验证-->
                                            <select name="work_location" style="color:#666;" id="profile_location" class="select1" onchange="build_second(this.value,'profile_sublocation',LOK);select_changed();" disabled="disabled"></select>&nbsp;&nbsp;
                                            <select disabled="disabled" style="color:#666;" name="work_sublocation" id="profile_sublocation" class="select2" onChange="document.getElementById('change_area_div').style.display='';select_changed()"></select>
                                            <script type="text/javascript">
                                            init_location(11, 1108, 'profile');
                                            </script>
                                            <a href="javascript:;" class="tips_link" id="modify_location_tag" onclick="show_modity_profile('location');">修改</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span>户口：</span></td>
                                        <td>
                                            <select onchange="build_second(this.value,'home_sublocation',LOK);select_changed();" class="select1" id="home_location" name="home_location"></select>&nbsp;&nbsp;
                                            <select onchange="select_changed()" class="select2" id="home_sublocation" name="home_sublocation"></select>
                                            <a href="javascript:;" class="tips_link" id="modify_bloodtype_tag" onclick="show_modity_profile('location');">修改</a>
                                        </td>
                                        <script type="text/javascript">build_select("home_location","home_sublocation",LSK,LOK,"");</script>
                                        <script type="text/javascript">
                                        init_location(0, 0, 'home');
                                        </script>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span>血型：</span></td>
                                        <td>
                                            <select name="bloodtype" id="bloodtype" onChange="select_changed();" disabled="disabled" style="color:#666;">
                                                <option value="0">--请选择--</option>
                                                <option label="A型" value="1">A型</option>
                                                <option label="B型" value="2">B型</option>
                                                <option label="O型" value="3">O型</option>
                                                <option label="AB型" value="4">AB型</option>
                                                <option label="其它" value="5">其它</option>
                                                <option label="保密" value="6">保密</option>
                                            </select>
                                              <a href="javascript:;" class="tips_link" id="modify_bloodtype_tag" onclick="show_modity_profile('bloodtype');">修改</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">民族：</span></td>
                                        <td>
                                            <select name="nation" id="nation" style="color:#666; " onChange="select_changed();" disabled="disabled">
                                                <option value="0">--请选择--</option>
                                                <option label="汉族" value="1" selected="selected">汉族</option>
                                                <option label="藏族" value="2">藏族</option>
                                                <option label="朝鲜族" value="3">朝鲜族</option>
                                                <option label="蒙古族" value="4">蒙古族</option>
                                                <option label="回族" value="5">回族</option>
                                                <option label="满族" value="6">满族</option>
                                                <option label="维吾尔族" value="7">维吾尔族</option>
                                                <option label="壮族" value="8">壮族</option>
                                                <option label="彝族" value="9">彝族</option>
                                                <option label="苗族" value="10">苗族</option>
                                                <option label="其它民族" value="11">其它民族</option>
                                            </select>
                                             <a href="javascript:;" class="tips_link" id="modify_nation_tag" onclick="show_modity_profile('nation');">修改</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">月薪：</span></td>
                                        <td>
                                            <!--如果手机没有验证-->
                                            <select id="income" name="income" onChange="validate('income', this.value)" disabled="disabled" style="color:#666;">
                                                <option label="2000元以下" value="0" selected="selected">-未选择-</option>
                                                <option label="2000元以下" value="10" selected="selected">2000元以下</option>
                                                <option label="2000～5000元" value="20">2000～5000元</option>
                                                <option label="5000～10000元" value="30">5000～10000元</option>
                                                <option label="10000～20000元" value="40">10000～20000元</option>
                                                <option label="20000～50000元" value="50">20000～50000元</option>
                                                <option label="50000元以上" value="60">50000元以上</option>
                                            </select>
                                            <a href="javascript:;" class="tips_link" id="modify_nation_tag" onclick="show_modity_profile('income');">修改</a>
                                        </td>
                                    </tr>
                                    <tr class="f-tips">
                                        <td colspan="2">
                                            <h2 class="f-note">上次修改时间：2017-09-25 10:46:24</h2></td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">居住情况：</span></td>
                                        <td>
                                            <select id="house" name="house" onChange="select_changed();" style="color:#666;" disabled="disabled">
                                                <option value="0">--请选择--</option>
                                                <option label="暂未购房" value="1">暂未购房</option>
                                                <option label="需要时购置" value="2">需要时购置</option>
                                                <option label="已购房（有贷款）" value="3">已购房（有贷款）</option>
                                                <option label="已购房（无贷款）" value="4">已购房（无贷款）</option>
                                                <option label="与人合租" value="5">与人合租</option>
                                                <option label="独自租房" value="6">独自租房</option>
                                                <option label="与父母同住" value="7">与父母同住</option>
                                                <option label="住亲朋家" value="8">住亲朋家</option>
                                                <option label="住单位房" value="9">住单位房</option>
                                            </select>
                                            <a href="javascript:;" class="tips_link" id="modify_house_tag" onclick="show_modity_profile('house');">修改</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span class="ico_stars">*</span><span>购车情况：</span></td>
                                        <td>
                                            <select id="car" name="car" onChange="select_changed();" disabled="disabled" style="color:#666;">
                                                <option value="0">--请选择--</option>
                                                <option label="暂未购车" value="1">暂未购车</option>
                                                <option label="已购车（经济型）" value="2">已购车（经济型）</option>
                                                <option label="已购车（中档型）" value="3">已购车（中档型）</option>
                                                <option label="已购车（豪华型）" value="4">已购车（豪华型）</option>
                                                <option label="单位用车" value="5">单位用车</option>
                                                <option label="需要时购置" value="6">需要时购置</option>
                                            </select>
                                            <a href="javascript:;" class="tips_link" id="modify_car_tag" onclick="show_modity_profile('car');">修改</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- 联系信息 -->
                            <div class="contact">
                                <h1>联系信息</h1>
                                <h2>以下资料我们将为您保密，不会显示在您的个人资料页面上。</h2>
                                <table width="450" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="item" width="70"><span>真实姓名：</span></td>
                                        <td width="360">
                                            <input type="text" name="true_name" class="text" onChange="select_changed()" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span>身份证号：</span></td>
                                        <td>
                                            <input type="text" class="text" id="id_card_id" name="id_card" value="" onChange="select_changed()" onblur="check_validate('idcard', this.value)" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item" style="color:#666;">邮箱：</td>
                                        <td><a href="validateemail/certificate.php?menu=5">填写邮箱地址并验证>></a></td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span>QQ：</span></td>
                                        <td>
                                            <input type="text" class="text" id="qq" name="qq" value="" onChange="select_changed()" onblur="check_validate('qq', this.value)" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span>MSN：</span></td>
                                        <td>
                                            <input type="text" class="text" id="msn" name="msn" value="" onChange="select_changed()" onblur="check_validate('msn', this.value)" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span>通讯地址：</span></td>
                                        <td>
                                            <input type="text" class="text" name="address" value="" onChange="select_changed()" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="item"><span>邮政编码：</span></td>
                                        <td>
                                            <input type="text" class="text" name="postcode" value="" onChange="select_changed()" />
                                        </td>
                                    </tr>
                                    <tr style='display:none;'>
                                        <td colspan="2">
                                            <div style="border-top:1px #DDE0E5 solid; margin:10px 0 0 0; padding:10px 0 0 0;"><b style="color:#000; font-size：14px;">分享资料</b><img src="http://images1.jyimg.com/w4/usercp/i/icon_rec.gif" align="absmiddle" /></div>
                                        </td>
                                    </tr>
                                    <tr style='display:none;'>
                                        <td colspan="2" class="item" style="color:#999898; line-height:1.6; width:439px; ">分享内容包括：昵称、所在城市、年龄、职业、内心独白。
                                            <br>佳缘，爱建议：想爱，大声说出来！
                                        </td>
                                    </tr>
                                    <tr style='display:none;'>
                                        <td class="item">您的选择：</td>
                                        <input type="hidden" name="share" value="1">
                                        <!-- <td class="item" style="width:200px; "><input type="checkbox" name="share" value="1"  checked="checked"  style="vertical-align:middle"/> 同意分享</td>-->
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input type="submit" class="save" value="保存并继续" onmousedown="send_jy_pv2('editprofile|save_base|m|168103003');" />
                                            <input type="button" value="跳过此页" class="skip" onClick="skip()" onmousedown="send_jy_pv2('editprofile|skip_base|m|168103003');" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- 联系信息结束 -->
                            <!-- 弹层开始 -->
                            <div id="info_div" class="info_div" style="display:none;">
                                <!-- 此处火狐的初始top是242像素，每个加28像素，IE的初始TOP是250像素，每个加30像素 -->
                                <dl id="info_div_content">
                                    <dt>为保证您的征友严肃性，您现在不能修改此项，可能的原因是：</dt>
                                    <dd>1、您距离上次修改所在地未满30天</dd>
                                    <dd>2、您正在“光明榜”服务期内</dd>
                                </dl>
                                <img id="info_div_close" src="http://images1.jyimg.com/w4/usercp/i/close2.jpg" alt="关闭" onClick="document.getElementById('info_div').style.display='none'" />
                            </div>
                            <!-- 弹层结束 -->
                            <!-- 弹层开始 -->
                            <div id="change_area_div" class="info_div" style="display:none;">
                                <dl id="info_div_content">
                                    <strong>请选择修改原因：</strong>
                                    <br/>
                                    <label for="change_area_reason1">
                                        <input type="radio" name="change_area_reason" id="change_area_reason1" value="1" />工作地区变更</label>
                                    <br/>
                                    <label for="change_area_reason2">
                                        <input type="radio" name="change_area_reason" id="change_area_reason2" value="2" />定居地区变更</label>
                                    <br/>
                                    <label for="change_area_reason3">
                                        <input type="radio" name="change_area_reason" id="change_area_reason3" value="3" />求学地区变更</label>
                                    <br/>
                                    <label for="change_area_reason4">
                                        <input type="radio" name="change_area_reason" id="change_area_reason4" value="4" />误操作导致地区填写错误</label>
                                    <br/>
                                    <p style="color:#333;margin-top:3px;">温馨提示：为保证您的征友严肃性，所在地区仅支持每30天修改一次。</p>
                                </dl>
                            </div>
                            <!-- 弹层结束 -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="popup" id="to_change_match" style="display:none">
                <h3><a href = "javascript:;" onclick="form_submit();" class = "closed">关闭</a>提示</h3>
                <p>您修改了所在地区，是否需要修改择友要求中的所在地区？</p>
                <a href="http://www.jiayuan.com/usercp/condition.php" target="_blank" onclick="form_submit();" class="modify"></a>
                <a href="javascript:;" onclick="form_submit();" class="later"></a>
                <span class="baseline1"></span>
                <div class="baseline2"><span class="baseline2_inner1"><span class = "baseline2_inner2"></span></span>
                </div>
            </div>
            <div id="mdy_mobile" style="display:none;">
                <div class="mdy_layer" style="padding-top:10px">
                    <ul>
                        <li>
                            <label>手机号：</label>
                            <input type="text" name="" id="mobile-num" style="width:150px" />
                        </li>
                        <li>
                            <label>验证码：</label>
                            <input type="text" name="" id="mobile-code" style="width:51px" />&nbsp;
                            <a onclick="send_msg();" href="javascript:;" class="send-code-btn code">获取验证码</a></li>
                        <li id="mdy_mobile_tips">温馨提示：完成手机验证，可马上修改一次哦~</li>
                        <li style="margin:5px auto;padding:2px 93px">
                            <input type="image" onclick="verify_phone();" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                        </li>
                    </ul>
                </div>
            </div>
            <div id="mdy_idcard" style="display:none;">
                <div class="mdy_layer" style="padding-top:10px">
                    <ul style="margin:0;padding:0;text-align:center;list-style:none">
                        <li>
                            <label>真实姓名：</label>
                            <input type="text" name="real_name" id="real_name" style="width:150px" />
                        </li>
                        <li>
                            <label>身份证号：</label>
                            <input type="text" name="real_identity" id="real_identity" style="width:150px" />
                        </li>
                        <li>
                           <!--  <label>验证码：</label>
                            <input type="text" name="antispam" id="antispam" style="width: 137px;">
                            <br/>
                            <span class="yzm" style="padding-right: 5px;display: inline-block;margin: 5px 0px 0 35px;"><img src="/antispam_v2.php?hash=gd_gen" style="width:75px;height:18px;vertical-align: middle;" alt="" id="antispam_v2"/><script type="text/javascript">function con_code(){var ran= Math.round((Math.random()) * 100000000);document.getElementById("antispam_v2").src = "/antispam_v2.php?r=" + ran;}</script></span><a href="javascript:con_code();">换一张</a> -->
                        </li>
                        <li id="mdy_idcard_tips">温馨提示：身份认证需要花费2佳缘宝，手机验证完成可以获得一次免费验证机会~</li>
                        <li style="margin:5px auto" id="mdy_idcard_click">
                            <input type="image" onclick="verify_identity('m',1,'1');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                        </li>
                    </ul>
                </div>
            </div>
            <div id="mdy_birthday" style="display:none;">
                <div class="mdy_layer">
                    <div id="mdy_tips_infos">
                        <span style="color:#000">根据身份认证信息修改出生日期为：</span><span id="modify_age_want"></span>
                    </div>
                    <div>
                        <input type="image" onclick="save_profile('age');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                    <div id="mdy_age_tips" class="tips" style="display:none">
                    </div>
                </div>
            </div>
             <div id="dialogBg"></div>
              <div id="dialog" class="animated">
              <img class="dialogIco" width="50" height="50" src="{{ asset('img/ico.png') }}" alt="" />
              <div class="dialogTop">
                <a href="javascript:;" class="claseDialogBtn">关闭</a>
              </div>

            <div id="mdy_height" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>身高：</label>
                        <select style="width:100px" id="new_height">
                            <option label="130" value="130">130</option>
                            <option label="131" value="131">131</option>
                            <option label="132" value="132">132</option>
                            <option label="133" value="133">133</option>
                            <option label="134" value="134">134</option>
                            <option label="135" value="135">135</option>
                            <option label="136" value="136">136</option>
                            <option label="137" value="137">137</option>
                            <option label="138" value="138">138</option>
                            <option label="139" value="139">139</option>
                            <option label="140" value="140">140</option>
                            <option label="141" value="141">141</option>
                            <option label="142" value="142">142</option>
                            <option label="143" value="143">143</option>
                            <option label="144" value="144">144</option>
                            <option label="145" value="145">145</option>
                            <option label="146" value="146">146</option>
                            <option label="147" value="147">147</option>
                            <option label="148" value="148">148</option>
                            <option label="149" value="149">149</option>
                            <option label="150" value="150">150</option>
                            <option label="151" value="151">151</option>
                            <option label="152" value="152">152</option>
                            <option label="153" value="153">153</option>
                            <option label="154" value="154">154</option>
                            <option label="155" value="155">155</option>
                            <option label="156" value="156">156</option>
                            <option label="157" value="157">157</option>
                            <option label="158" value="158">158</option>
                            <option label="159" value="159">159</option>
                            <option label="160" value="160">160</option>
                            <option label="161" value="161">161</option>
                            <option label="162" value="162">162</option>
                            <option label="163" value="163">163</option>
                            <option label="164" value="164">164</option>
                            <option label="165" value="165">165</option>
                            <option label="166" value="166">166</option>
                            <option label="167" value="167">167</option>
                            <option label="168" value="168">168</option>
                            <option label="169" value="169">169</option>
                            <option label="170" value="170">170</option>
                            <option label="171" value="171">171</option>
                            <option label="172" value="172">172</option>
                            <option label="173" value="173">173</option>
                            <option label="174" value="174">174</option>
                            <option label="175" value="175">175</option>
                            <option label="176" value="176">176</option>
                            <option label="177" value="177">177</option>
                            <option label="178" value="178">178</option>
                            <option label="179" value="179">179</option>
                            <option label="180" value="180">180</option>
                            <option label="181" value="181">181</option>
                            <option label="182" value="182">182</option>
                            <option label="183" value="183">183</option>
                            <option label="184" value="184">184</option>
                            <option label="185" value="185">185</option>
                            <option label="186" value="186">186</option>
                            <option label="187" value="187">187</option>
                            <option label="188" value="188">188</option>
                            <option label="189" value="189">189</option>
                            <option label="190" value="190">190</option>
                            <option label="191" value="191">191</option>
                            <option label="192" value="192">192</option>
                            <option label="193" value="193">193</option>
                            <option label="194" value="194">194</option>
                            <option label="195" value="195">195</option>
                            <option label="196" value="196">196</option>
                            <option label="197" value="197">197</option>
                            <option label="198" value="198">198</option>
                            <option label="199" value="199">199</option>
                            <option label="200" value="200">200</option>
                            <option label="201" value="201">201</option>
                            <option label="202" value="202">202</option>
                            <option label="203" value="203">203</option>
                            <option label="204" value="204">204</option>
                            <option label="205" value="205">205</option>
                            <option label="206" value="206">206</option>
                            <option label="207" value="207">207</option>
                            <option label="208" value="208">208</option>
                            <option label="209" value="209">209</option>
                            <option label="210" value="210">210</option>
                            <option label="211" value="211">211</option>
                            <option label="212" value="212">212</option>
                            <option label="213" value="213">213</option>
                            <option label="214" value="214">214</option>
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
                        </select>厘米&nbsp;
                        <input type="image" onclick="save_profile('height');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>
            
            <div id="mdy_location" style="display:none;">
                <div class="mdy_layer" style="padding-top:10px">
                    <ul>
                        <li>
                            <label>所在地区：</label>
                            <select style="width:70px" onchange="build_second(this.value,'new_profile_sublocation',LOK);select_changed();" name="new_work_location" id="new_profile_location">
                            </select>
                            <select style="width:70px" name="new_work_sublocation" id="new_profile_sublocation" onChange="select_changed()">
                            </select>
                        </li>

                         <!--如果手机没有验证-->
                      
                        <script type="text/javascript">
                        init_location(11, 1108, 'profile');
                        </script>

                        <li>
                            <label>修改原因：</label>
                            <select name="change_area_reason_new" id="change_area_reason">
                                <option value="0">--请选择--</option>
                                <option value="1">工作地区变更</option>
                                <option value="2">定居地区变更</option>
                                <option value="3">求学地区变更</option>
                                <option value="4">误操作地区填写错误</option>
                            </select>
                        </li>
                        <li class="tips" id="mdy_location_tips">温馨提示：为保证您的征友严肃性，所在地区仅支持每30天修改一次。</li>
                        <li style="margin:5px auto;padding:2px 93px">
                            <input type="image" onclick="save_profile('location');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                        </li>
                    </ul>
                </div>
            </div>

            <div id="mdy_education" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>学历：</label>
                        <select style="width:135px" id="new_education">
                            <option value="0" selected="selected">-未选择-</option>
                            <option value="1">小学</option>
                            <option value="2">初中</option>
                            <option value="3">高中</option>
                            <option value="4">专科</option>
                            <option value="5">本科</option>
                            <option value="6">硕士</option>
                            <option value="7">博士</option>
                            <option value="8">博士后</option>
                        </select>
                        <input type="image" onclick="save_profile('education');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>

            <div id="mdy_bloodtype" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>血型：</label>
                        <select style="width:135px" id="new_bloodtype">
                            <option value="0" selected="selected">-未选择-</option>
                            <option label="A型" value="1">A型</option>
                            <option label="B型" value="2">B型</option>
                            <option label="O型" value="3">O型</option>
                            <option label="AB型" value="4">AB型</option>
                            <option label="其它" value="5">其它</option>
                            <option label="保密" value="6">保密</option>
                        </select>
                        <input type="image" onclick="save_profile('bloodtype');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>

             <div id="mdy_nation" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>民族：</label>
                        <select style="width:135px" id="new_nation">
                            <option value="0">-未选择-</option>
                            <option label="汉族" value="1" selected="selected">汉族</option>
                            <option label="藏族" value="2">藏族</option>
                            <option label="朝鲜族" value="3">朝鲜族</option>
                            <option label="蒙古族" value="4">蒙古族</option>
                            <option label="回族" value="5">回族</option>
                            <option label="满族" value="6">满族</option>
                            <option label="维吾尔族" value="7">维吾尔族</option>
                            <option label="壮族" value="8">壮族</option>
                            <option label="彝族" value="9">彝族</option>
                            <option label="苗族" value="10">苗族</option>
                            <option label="其它民族" value="11">其它民族</option>
                        </select>
                        <input type="image" onclick="save_profile('nation');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>

             <div id="mdy_income" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>月薪：</label>
                        <select style="width:135px" id="new_income">
                            <option value="0">-未选择-</option>
                            <option label="2000元以下" value="10" selected="selected">2000元以下</option>
                            <option label="2000～5000元" value="20">2000～5000元</option>
                            <option label="5000～10000元" value="30">5000～10000元</option>
                            <option label="10000～20000元" value="40">10000～20000元</option>
                            <option label="20000～50000元" value="50">20000～50000元</option>
                            <option label="50000元以上" value="60">50000元以上</option>
                        </select>
                        <input type="image" onclick="save_profile('income');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>

            <div id="mdy_house" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>居住情况：</label>
                        <select style="width:135px" id="new_house">
                            <option value="0">-未选择-</option>
                            <option label="暂未购房" value="1">暂未购房</option>
                            <option label="需要时购置" value="2">需要时购置</option>
                            <option label="已购房（有贷款）" value="3">已购房（有贷款）</option>
                            <option label="已购房（无贷款）" value="4">已购房（无贷款）</option>
                            <option label="与人合租" value="5">与人合租</option>
                            <option label="独自租房" value="6">独自租房</option>
                            <option label="与父母同住" value="7">与父母同住</option>
                            <option label="住亲朋家" value="8">住亲朋家</option>
                            <option label="住单位房" value="9">住单位房</option>
                        </select>
                        <input type="image" onclick="save_profile('house');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>

             <div id="mdy_car" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>购车情况：</label>
                        <select style="width:135px" id="new_car">
                            <option value="0">-未选择-</option>
                            <option label="暂未购车" value="1">暂未购车</option>
                            <option label="已购车（经济型）" value="2">已购车（经济型）</option>
                            <option label="已购车（中档型）" value="3">已购车（中档型）</option>
                            <option label="已购车（豪华型）" value="4">已购车（豪华型）</option>
                            <option label="单位用车" value="5">单位用车</option>
                            <option label="需要时购置" value="6">需要时购置</option>
                        </select>
                        <input type="image" onclick="save_profile('car');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>

            <div id="mdy_children" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>有无子女：</label>
                        <select style="width:135px" id="new_children">
                                <option value="0">--请选择--</option>
                                <option label="无小孩" value="1">无小孩</option>
                                <option label="有小孩归自己" value="2">有小孩归自己</option>
                                <option label="有小孩归对方" value="3">有小孩归对方</option>
                        </select>
                        <input type="image" onclick="save_profile('children');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>

            <div id="mdy_marriage_status" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>婚姻状态：</label>
                        <select style="width:135px" id="new_marriage_status">
                            <option value="0">-未选择-</option>
                            <option value="1">未婚</option>
                            <option value="2">离异</option>
                            <option value="3">丧偶</option>
                        </select>
                        <input type="image" onclick="save_profile('marriage_status');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
                    </div>
                </div>
            </div>

            <div id="mdy_nickname" style="display:none;">
                <div class="mdy_layer">
                    <div>
                        <label>昵称：</label>
                        <input type="text" name="" id="new_nickname" style="width:135px" />&nbsp;
                        <input type="image" class="button" onclick="save_profile('nickname');" src="{{ asset('img/home/alert_btn.png')}}" />
                    </div>
                    <div id="mdy_nickname_tips">
                        温馨提示：昵称修改成功之后，请重新登录~
                    </div>
                </div>
            </div>
             <div id="mdy_height_tips" class="tips" style="display:none">
                    </div>
            </div>
            <div class="info_right">
                <h2>资料完整度：<span class="span101203_1">46分</span></h2>
                <div class="integrality">
                    <div class="plan" style="width:{{ $score}}%;">
                        <div class="progress_jindu">{{ $score}}</div>
                        &nbsp;
                    </div>
                    <div style="left:90%;" class="progress_modelMain">
                        <div class="progress_model ie6png">
                        </div>
                        <div class="progress_modelNum ie6png">
                            90
                        </div>
                    </div>
                </div>
                <div class="pre_fen">
                    达到90分可得到优先推荐的资格哦~
                </div>
                <div class="preview">
                    <a href="http://www.jiayuan.com/usercp/profile.php?action=base" onmousedown="send_jy_pv2('editprofile|220059_14|m|168103003');">去补充基本资料</a>
                </div>
                <div class="why">
                    <h3>填写基本资料的重要性</h3>
                    <p> 您在注册过程中已经填写了大部分的基本资料，只需要将空白内容填写完整，就可以获得<strong style="color:red;">20%</strong>的资料完整度。完整的基本资料是会员搜索到您的重要保证，更是让异性能够初步了解您的基础。</p>
                    <p> 我们建议您将联系方式填写完整，这部分信息仅在世纪佳缘客服与您联系和邮寄礼品时使用，不会展示在任何公开页面上。</p>
                </div>
                <div class="whybg"></div>
            </div>
            <!-- 右边结束 -->
        </div>
        <div class="borderbg"><img src="{{ asset('img/home/border_bottom.jpg')}}" /></div>
    </div>
@endsection
