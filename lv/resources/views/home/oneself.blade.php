@extends('layouts.auto_app')
<title>{{ $name }} {{ $home_location }}</title>

@section('left_content')
    <div class="home_left_content">
    &nbsp&nbsp&nbsp
    </div>
@endsection

@section('content')
        <link href="{{ asset('/css/home/stype.css') }}" rel="stylesheet" type="text/css">
        <link href="http://images1.jyimg.com/w4/usercp_new/c/neixindubai.css" rel="stylesheet" type="text/css" />
    <div class="content">
    <!--统计-->
    <script type="text/javascript">
    $(document).ready(function() {
        $(".ico0").mouseover(function() {
            $("#info_div").show();
        });
    });
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
    </script>
    <script type="text/javascript">
    (function() { var my_pv_key = "from_new_reg_page"; var RegConfig = window.RegConfig = window["RegConfig"] = { "baseUrl": "http://www.jiayuan.com", "errorFocus": "", "sysdate": "2013-06-28", "ajaxUrl": "/", "sourcesUrl": "http://images1.jyimg.com/w4", "clientLocal": "11", "province": "", "city": "", "byear": "", "bmonth": "", "bday": "", "configLocal": "11|12|13|14|15|21|22|23|31|32|33|34|35|36|37|41|42|43|44|45|46|50|51|52|53|54|61|62|63|64|65|71|81|82|99", "initEmail": "", "from": "0" }; })();
    </script>
    <script src="http://images1.jyimg.com/w4/usercp/j/jquery-1.4.2.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://images1.jyimg.com/w4/register/j/jquery-note.js"></script>
    <script type="text/javascript">
    var get = function(id) {
        if (typeof(id) != "string" || id == "") return false;
        if (document.getElementById) return document.getElementById(id);
        if (document.all) return document.all(id);
    };

    function validate_note() {
        if ({{ $description_status }} == 2) {
            alert('审核中~');
            return false;
        }
        var noteLen = document.getElementById('Form_new_note').value.length;
        if (noteLen < 20) {
            alert('独白必须超过20字！');
            return false;
        } else if (noteLen > 1000) {
            alert('独白不得超过1000字！');
            return false;
        } else {
            return true;
        }
    }

    function doNoThing() {
        document.getElementById('change_note').submit();
        setDomainForIframe();
    }

    function commitMsg() {
        var data = {};
        data['description'] = $('#Form_new_note').val();
        data["_token"] = "{!! csrf_token() !!}";
        $.ajax({
            type: "post",
            url: "{{ route('edit_oneself') }}",
            dataType: "json",
            data: data,
            error:function(msg){ //处理出错的信息  
                 
              },  
            success: function(data) {
                if (data.status == 1) {
                    window.location.href = "{{ route('edit_img') }}";
                } else if (data.status == 0) {
                    alert('修改失败');
                }
            }
        });
    }

    function noteSubmit() {
        if (validate_note()) {
          // setTimeout("doNoThing()", 100);
           setTimeout("commitMsg()", 100);
        }
    }

    function noteSubmit_payback() {
        get('change_note').target = '';
        noteSubmit();
    }

    function changeNote() {
        var note = document.getElementById("Form_new_note").value;
        var has_write_obj = document.getElementById("has_write");
        var will_write_obj = document.getElementById("will_write");
        has_write_obj.innerHTML = note.length;
        will_write_obj.innerHTML = 1000 - note.length;
    }

    function push_to_textarea(_id) {
        var text_content = document.getElementById(_id).innerHTML;
        var textarea_obj = document.getElementById("Form_new_note");

        text_content = text_content.replace(/<[^>].*?>/g, "");

        textarea_obj.value = text_content;
        changeNote();
        closeDiv('monolog_div');
    }

    window.onload = function() { changeNote(); };

    function skip() {
        if (isChanged) {
            if (confirm("您尚有未保存的资料，确定要离开吗？")) {
                location.href = 'photo.php';
            }
        } else {
            location.href = 'photo.php';
        }
    }

    function change_cat(_obj) {
        var tag_obj = document.getElementById("example_category");
        var tag_element = tag_obj.getElementsByTagName("li");
        var this_id = _obj.getAttribute("id");
        for (var i = 0; i < tag_element.length; i++) {
            var element_id = tag_element[i].getAttribute("id");
            var content_obj = document.getElementById("example_content_" + i);

            tag_element[i].className = "";
            content_obj.style.display = "none";
            if (element_id == this_id) {
                tag_element[i].className = "on";
                content_obj.style.display = "";
            }
        }
    }

    function check_money() {
        jQuery.get('get_balance.php', {}, function(data) {
            var ret = eval(data);
            if (ret < 1) {
                get('change_note').target = 'subifr';
            } else {
                get('change_note').target = '';
            }
            get('stamp_now').innerHTML = ret;
        });
    }
    (function($) {
        $(document).ready(function() {
            $(this).Note("see_other_note", {
                "src": RegConfig.sourcesUrl,
                "css": "nxdb_a",
                "top": null,
                "left": null,
                "onclick": function(data, tab, index) {
                    $("#Form_new_note").focus();
                    $("#Form_new_note").val(data);
                    try {
                        send_jy_pv2("reg_see_other_note_" + tab + "_" + index);
                    } catch (e) {}
                    //显示tab
                    try {
                        photo(1);
                    } catch (e) {}
                },
                "onopen": function() {
                    var sex = 'm';
                    $(".nxdb_a").attr("sex", sex);
                    $("#note_pop_model").css({ left: 0, top: 0 });
                    $("#see_other_note").css('position', 'static');

                }
            });
        });
    })(jQuery);
    </script>

    <div class="my_infomation">
        <div class="navigation"><a href="{{ route('home') }}" >个人中心</a>&nbsp;&gt;&nbsp;基本资料</div>
        <div class="borderbg"><img src="http://images1.jyimg.com/w4/usercp/i/i520/border_top.jpg" /></div>
        <div class="info_content">
            <!-- 左侧开始 -->
            <div class="info_left">
                <ul>
                    <li class="mark" onmousedown="send_jy_pv2('editprofile|category_base|m|168103003');"><a href="{{ route('base_mean')}}">基本资料</a></li>
                    <li class="on"><a href="javascript:;">内心独白</a></li>
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
            <!-- 左侧结束 -->
            <!-- 中间开始 -->
            <div class="info_center" style="position:static">
                <div id="see_other_note" style="height: 1px; top: 0px; left: 0px; z-index: 1000;"></div>
                <div class="title">
                    <strong>内心独白</strong>
                </div>
                <iframe style="display:none;" name="mobile_pay_ifr" id="mobile_pay_ifr" scrolling="no" width="654" height="600" allowTransparency="true" frameborder="0"></iframe>
                <script type="text/javascript" src="http://images.jiayuan.com/w4/msg/j/MaskDiv.js"></script>
                <script type="text/javascript">
                function open_pay(callback) {
                    document.getElementById('mobile_pay_ifr').src = "http://www.jiayuan.com/usercp/mobile_pay.php?js_back=" + callback;
                    document.getElementById('mobile_pay_ifr').style.width = "654px";
                    openMaskDiv('mobile_pay_ifr', 600, 300, 0, 1);
                }
                var reg_host_const_flag = 0;
                var reg_host_const_test = 0;
                var reg_host_domain = document.domain;

                function setDomainForIframe() {
                    if (reg_host_const_flag == 0 || reg_host_const_flag == 7) {
                        if (reg_host_const_test == 1) {
                            document.domain = 'miuu.' + 'cn';
                        } else {
                            document.domain = 'jiayuan.com';
                        }
                    } else {
                        if (reg_host_const_test == 1) {
                            document.domain = 'miuu.' + 'cn';
                        } else {
                            document.domain = 'msn.com.cn';
                        }
                    }
                }
                </script>
                <iframe name="subifr" id="subifr" style="display:none"></iframe>
                <div class="mid_border">
                    <div class="monolog">
                        <p class="info_note">资料越完善，同等条件我们将优先推荐您哦~</p>
                        <div class="heart_monolog">
                            <table width="475" cellpadding="0" cellspacing="0">
                                <form id="change_note" name="change_note" method="post">
                                    <tr>
                                        <td colspan="2">
                                            <textarea id="Form_new_note" name="Form_new_note" onkeyup="changeNote();select_changed();">{{ $description or '快快填写，让别人认识你吧' }}
                                            </textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>限20-1000字，目前已输入<strong id="has_write">0</strong>字，您还可以输入<strong id="will_write">1000</strong>字</td>
                                        <td align="right"><span class="nxdb_a">内心独白撰写规则和技巧</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <input type="button" class="save" value="保存并继续" onClick="noteSubmit();" onmousedown="send_jy_pv2('editprofile|save_note|m|168103003');" />
                                            <input type="button" value="跳过此页" class="skip" onClick="location.href='photo.php'" onmousedown="send_jy_pv2('editprofile|skip_note|m|168103003');" />
                                        </td>
                                    </tr>
                                </form>
                            </table>
                            <div>
                                温馨提示：
                                <br /> 1、内心独白字数需在20-1000字之间。
                                <br /> 2、内心独白中请勿出现QQ、MSN、电话号码以及网址、广告、色情或其他不健康的内容
                                <br /> 3、点击保存后，在我们未审核的24小时内不能再次修改，请认真检查。
                                <br />
                            </div>
                        </div>
                        <!--内心独白结束 -->
                    </div>
                </div>
            </div>
            <!-- 中间结束 -->
            <!-- 右边开始 -->
            <!--[if lte IE 6]>
        <script type="text/javascript" src="http://images1.jyimg.com/w4/msg/js/dd_belatedpng.js?09153"></script>
        <script>
            DD_belatedPNG.fix('.ie6png');
        </script>
        <![endif]-->
            <div class="info_right">
                <h2>资料完整度：<span class="span101203_1">{{ $score }}分</span></h2>
                <div class="integrality">
                    <div class="plan" style="width:{{ $score }}%;">
                        <div class="progress_jindu">{{ $score }}</div>
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
                    <a href="{{ route('base_mean') }}" onmousedown="send_jy_pv2('editprofile|220059_14|m|168103003');">去补充基本资料</a>
                </div>
                <div class="why">
                    <h3>什么是内心独白？</h3>
                    <p>发自内心，真诚真实的自我介绍能够给人留下深刻的第一印象，您可以用丰富的语言描述自己的性格，生活工作情况，择友要求以及对未来的憧憬等等，让异性对您有更加深刻的认识，也是您在世纪佳缘展示自我的好机会。我们主动联系一个人，往往就是因为其内心独白中的一句话打动了自己！</p>
                    <p>内心独白提交后，将由世纪佳缘进行审核，通过审核后，您会获得<strong style="color:red;">15%</strong>的资料完整度。为了维护严肃纯净的交友环境，希望您认真填写。</p>
                </div>
                <div class="whybg"></div>
            </div>
            <!-- 右边结束 -->
        </div>
        <div class="borderbg"><img src="{{ asset('img/home/border_bottom.jpg') }}" /></div>
    </div>
    </div>
@endsection

@section('right_content')
    <div class="home_right_content">
     
    </div>
@endsection
   