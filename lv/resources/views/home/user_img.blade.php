@extends('layouts.auto_app')

<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@section('left_content')
    <div class="home_left_content">
       
    </div>
@endsection

@section('content')
    <div class="content">
         <title>我的相册_    {{ config('app.name') }}</title>
    <link href="{{ asset('css/home/user_img_stype.css') }}" rel="stylesheet">
    <script src="http://open.web.meitu.com/sources/xiuxiu.js" type="text/javascript"></script>
        <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $(".ico0").mouseover(function() {
            $("#info_div").show();
        });

        $('#life_pic .showpic a').lightBox();
    });

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

     var type = 0;

    function beformOpenDiv(_id, _width, _height) {
        if ({{ $user['status'] == 2 }}) {
            alert('审核中，请勿重复提交');
            return;
        }
        this.type = 0;
        openDiv(_id, _width, _height);
    }

    function beformOtherOpenDiv(_id, _width, _height) {
        this.type = 1;
        openDiv(_id, _width, _height);
    }

    function upload_photo(index) {
        //type = 0 上传头像  type=1 上传生活照
        if (index == 0 && this.type == 1) {
            if (check_form(0)) {
                var upload_form = document.getElementById("frm_upload");
                closeDiv("upload_photo");
                openDiv("uploading");
                var formData = new FormData($('#frm_upload')[0]);
                formData.append("type", 1);
                $.ajax({
                    cache: false,
                    type: "POST",
                    url:"{{ route('upload_img') }}",
                    data:formData,// 你的formid
                    async: false,
                    processData: false,  
                    dataType: 'json',
                    contentType: false,  
                    error: function(request) {
                      alert('添加失败');
                      closeDiv("uploading");
                    },
                    success: function(data) {
                        closeDiv("uploading");
                      if (data.status == 1) {
                        //提交成功
                        alert("提交成功");
                        var tmp = "";
                        var files = data.files;
                        for (var i = 0; i < files.data.length; i++) {
                            var file = files[i];
                            tmp += "<li class=\"nopic\">";
                            tmp += "<div class=\"showpic\">";
                            tmp += "<p><img src=\"" + file.img_url + "\" /></p>";
                            tmp += "</div>";
                            tmp += "<div class=\"pic_control_2\" style=\"padding:8px 0;\">";
                            if (file.status == 2) {
                                tmp += "<button class=\"upload_pic\">" + "审核中～" + "</button>";
                            } else if (file.status == 1) {
                                tmp += "<button class=\"upload_pic\">" + "删除" + "</button>";
                            }
                            tmp += "</div>";
                            tmp += "</li>"
                        }
                        $('#life_pic').html(tmp);
                      } else if (data.status == 0) {
                        alert("上传图片失败");
                      } else if (data.status == 2) {
                        //补全资料
                      }
                    }
            });
            }
        } else if (index == 0 && this.type == 0) {
            //上传头像
            if (check_form(0)) {
                var upload_form = document.getElementById("frm_upload");
                closeDiv("upload_photo");
                openDiv("uploading");
                var formData = new FormData($('#frm_upload')[0]);
                formData.append('type', 0);
                $.ajax({
                    cache: false,
                    type: "POST",
                    url:"{{ route('upload_img') }}",
                    data:formData,// 你的formid
                    async: false,
                    processData: false,  
                    dataType: 'json',
                    contentType: false,  
                    error: function(request) {
                      alert('上传头像失败');
                      closeDiv("uploading");
                    },
                    success: function(data) {
                        closeDiv("uploading");
                      if (data.status == 1) {
                        //提交成功
                        alert("上传头像成功");
                        var tmp = "";
                        var file = data.files;
                        alert(file[0]['img_url']);
                        $('#img_mt').attr('src', file[0]['img_url']);
                      } else if (data.status == 0) {
                        alert("上传头像失败");
                      } 
                    }
                });
            }
     }
    }

    function check_form(index) {
        var oP, haveP = 0;
        var patn = /.jpg$|.jpeg$|.gif$|.png$/i;
        var patn_wl = /^http:\/\//i;
        if (index == 0) {
            var files = document.getElementsByName('upload_file[]');
        } else {
            var files = document.getElementsByName('wl_upload_file[]');
        }
        for (var i = 0, j = 0; i < files.length; i++) {
            if (files[i].value.length > 1) {
                haveP = 1;
                if (index == 0) {
                    if (!patn.test(files[i].value)) {
                        j = i + 1;
                        alert('第' + j + '个不是合法的图片文件,请重新选择');
                        return false;
                    }
                } else {
                    if (!patn.test(files[i].value) || !patn_wl.test(files[i].value)) {
                        j = i + 1;
                        alert('第' + j + '个不是合法的图片文件,请重新选择');
                        return false;
                    }
                }
            }
        }
        if (haveP == 0) {
            alert("请选择要上传的照片");
            return false;
        }
        if (my_getbyid('uptitle')) {
            strlength = my_getbyid('uptitle').value.length;
            if (strlength > 20) {
                alert('照片标题文字太长，请控制在20字以内');
                return false;
            }
        }
        return true;
    }

    function check_fileszie(filePicker, index) {
        var patn = /.jpg$|.jpeg$|.gif$|.png$/i;
        var patn_wl = /^http:\/\//i;
        if (patn.test(filePicker.value) && index == 0) {
            img_temp = my_getbyid('oFileChecker');
            img_temp.src = filePicker.value;
        } else if (patn.test(filePicker.value) && patn_wl.test(filePicker.value) && index == 1) {
            img_temp = my_getbyid('wl_oFileChecker');
            img_temp.src = filePicker.value;
        } else {
            alert('您选择的不是合法的图片文件,请重新选择');
            filePicker.value = '';
            return false;
        }
    }

    function check_photo_size(index) {
        if (index == 0) {
            img_temp = my_getbyid('oFileChecker');
        } else {
            img_temp = my_getbyid('wl_oFileChecker');
        }
        var limit = parseInt(my_getbyid('max_file_size').value);
        if (img_temp.fileSize > limit) {
            alert("照片文件过大，请选择5M以下的文件上传");
            return false;
        }
    }
    //添加获取class
    function getElementsByClassName(node, classname) {
        if (node.getElementsByClassName) {
            return node.getElementsByClassName(classname);
        } else {
            return (function getElementsByClass(searchClass, node) {
                if (node == null)
                    node = document;
                var classElements = [],
                    els = node.getElementsByTagName("*"),
                    elsLen = els.length,
                    pattern = new RegExp("(^|\\s)" + searchClass + "(\\s|$)"),
                    i, j;

                for (i = 0, j = 0; i < elsLen; i++) {
                    if (pattern.test(els[i].className)) {
                        classElements[j] = els[i];
                        j++;
                    }
                }
                return classElements;
            })(classname, node);
        }
    }

    var isChanged = true;
    function skip() {
        if (isChanged) {
            if (confirm("您尚有未保存的资料，确定要离开吗？")) {
                location.href = '/usercp/profile.php?action=map';
            }
        } else {
            location.href = '/usercp/profile.php?action=map';
        }
    }

    function edit_photo_desc(_pid) { //photo_describe
        openDiv("photo_describe", 560, 400);
    }

    function close_edit_photo_desc(_pid) {
        document.getElementById("desc_title_" + _pid).className = "";
        document.getElementById("desc_show_" + _pid).style.display = "";
        document.getElementById("desc_edit_" + _pid).style.display = "none";
        document.getElementById("desc_button_" + _pid).style.display = "none";

    }

    var DKL = my_getbyid;
    var start = false;
    var nowFxkjSet = 'off';

    function my_getbyid(id)
            {
               itm = null;
               if (document.getElementById)
               {
                  itm = document.getElementById(id);
               }
               else if (document.all)
               {
                  itm = document.all[id];
               }
               else if (document.layers)
               {
                  itm = document.layers[id];
               }
               
               return itm;
            }

    function get_photo_privacy_set_div_id(value, nowValue) {
        var id;
        if (value == 4) {
            if (nowValue == 4) {
                id = 'photo_set_mask_4_2';
            } else {
                id = 'photo_set_mask_4_1';
            }
            id = 'photo_set_mask_4_1'; //改为密码都用这个层
        } else {
            id = 'photo_set_mask_' + value;
        }
        return id;
    }

    function show_photo_privacy_set_div(value) {
        var id = get_photo_privacy_set_div_id(value, nowPrivacy);
        openDiv(id);
    }

    function close_photo_privacy_set_div(id) {
        closeDiv(id);
        id = 'privacy_' + nowPrivacy;
        DKL(id).checked = 'checked';
    }
    </script>

    <div class="my_infomation">
        <div class="navigation"><a href="{{ route('home') }}" onmousedown="send_jy_pv2('editprofile|my_home|m|168103003');">个人中心</a>&nbsp;&gt;&nbsp;我的照片</div>
        <div class="borderbg"><img src="{{ asset('img/home/border_top.jpg') }}" /></div>
        <div class="info_content">
            <!-- 左侧开始 -->
            <div class="info_left">
                <ul>
                    <li class="mark"><a href="http://www.jiayuan.com/usercp/profile.php?action=base">基本资料</a></li>
                    <li class="ok"><a href="{{ route('oneself')}}">内心独白</a></li>
                    <li class="on"><a href="javascript:;">我的照片</a></li>
                    <li onClick="show_category('detail_hidden');" class=""><a href="javascript:;">详细资料</a></li>
                    <li id="detail_hidden" class="hidden_li">
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=economy">经济实力</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=life">生活方式</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=work">工作学习</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=body">外貌体型</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=marriage">婚姻观念</a>
                        <a class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=interest">兴趣爱好</a>
                    </li>
                </ul>
                <div class="return_index">
                    <a class="return_jy" href="{{ route('home') }}">返回我的佳缘</a>
                </div>
            </div>
            <!-- 左侧结束 -->
            <!-- 中间开始 -->
            <!--mtxx fix-->
            <style>
            .mt2 {
                margin-top: 2px;
            }

            #life_pic li {
                position: relative;
            }

            .new_pic_notice p .new_add_photo {
                width: 115px;
                height: 37px;
                background: url(http://images1.jiayuan.com/w4/usercp/i/new_uploadPic/6.jpg) 0 0 no-repeat;
                text-decoration: none;
                cursor: pointer;
            }

            .new_pic_notice p .new_add_photo:hover {
                background: url(http://images1.jiayuan.com/w4/usercp/i/new_uploadPic/6.jpg) 0 -37px no-repeat;
            }
            </style>
       
            <div class="info_center">
                <div class="title">
                    <strong>我的照片</strong>
                </div>
                <div class="my_photos">
                    <div class="my_userface">
                        <p class="info_note">资料越完善，同等条件我们将优先推荐您哦~</p>
                        <div class="pic" style="position: relative;">
                            <h2>我的头像照</h2>
                            <div class="image">
                                <img id="img_mt" src="{{ $user['img_url'] or asset('img/default_avatar.png') }}" width=120 height=120 />
                            </div>
                            <!--mtxx fix-->
                            <p class="mt2">
                                <a onClick="beformOpenDiv('upload_photo', 560, 400);" href="javascript:;">上传照片</a>
                            </p>
                            <!--mtxx fix-->
                        </div>
                        <!-- new pic_notice begin-->
                        <div class="new_pic_notice" style="float:right;">
                            <p class="WLclearfix">
                                <a href="#" onClick="beformOpenDiv('upload_photo', 560, 400);" class="new_add_photo"></a><a onClick="openDiv('monolog_div', 709, 490);" class="wl-ml13" href="javascript:;">如何上传好照片</a>
                            </p>
                            <ul class="notice-refers">
                                <li>有照片会员，收到的<span>信件</span>比没照片的会员多<span>11倍</span></li>
                            </ul>
                        </div>
                        <!-- new pic_notice end-->
                        <!-- old pic_notice begin-->
                        <div class="pic_notice" style="display:none;">
                            <a onClick="openDiv('upload_photo', 560, 400);" href="javascript:;" class="add_photo">上传照片</a><a onClick="openDiv('monolog_div', 709, 490);" href="javascript:;" style="color:#0066CD; text-decoration:underline;" class="up_photo ">如何上传好照片</a>
                        </div>
                        <!-- 我的生活照 -->
                        <!-- old pic_notice end-->
                        <div class="life_pic">
                            <h2>我的生活照</h2>
                            <ul id="life_pic">
                                @if (!empty($files['data']))
                                    @foreach ($files['data'] as $file)
                                         <li class="nopic">
                                            <div class="showpic">
                                                <p><img src="{{ $file['img_url'] }}" onClick="openDiv('upload_photo', 560, 400);" /></p>
                                            </div>
                                            <div class="pic_control_2" style="padding:8px 0;">
                                                @if ($file['status'] == 2)
                                                    <button class="upload_pic">审核中~</button>
                                                @elseif ($file['status'] == 1)
                                                    <button class="upload_pic">删除</button>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                                @for ($i = 0; $i < 6 - count($files['data']); $i++)
                                     <li class="nopic">
                                        <div class="showpic">
                                            <p><img src="{{ asset('img/default_avatar.png') }}" onClick="beformOtherOpenDiv('upload_photo', 560, 400);" /></p>
                                        </div>
                                        <div class="pic_control_2" style="padding:8px 0;">
                                            <input type="button" class="upload_pic" value="" onClick="beformOtherOpenDiv('upload_photo', 560, 400);" />
                                        </div>
                                    </li>
                                @endfor
                            </ul>
                            <div class="cross"></div>
                        </div>
                        <!-- 照片显示权限设置 -->
                        <dl class="pic_set">
                            <dt>照片显示权限设置</dt>
                            <dd>
                                <label for="privacy_1">
                                    <input id="privacy_1" type="radio" checked="checked" onclick="show_photo_privacy_set_div(this.value);" name="privacy" value="1" />&nbsp;所有人可见</label>
                                （会员和访客都可以看到照片）
                            </dd>
                            <dd>
                                <label for="privacy_2">
                                    <input id="privacy_2" type="radio" onclick="show_photo_privacy_set_div(this.value);" name="privacy" value="2" />&nbsp;会员可见</label>
                                （注册成为会员的人可以看到照片）
                            </dd>
                            <dd>
                                <label for="privacy_3">
                                    <input id="privacy_3" type="radio" onclick="show_photo_privacy_set_div(this.value);" name="privacy" value="3" />&nbsp;星级会员可见</label>
                                （必须是实名星级用户才可看到照片）
                            </dd>
                            <dd>
                                <label for="privacy_5">
                                    <input id="privacy_5" type="radio" onclick="show_photo_privacy_set_div(this.value);" name="privacy" value="5" />&nbsp;有照片会员可见</label>
                                （必须有照片的会员才能看到照片）
                            </dd>
                            <dd>
                                <label for="privacy_4">
                                    <input id="privacy_4" type="radio" name="privacy_no" onclick="checked=defaultChecked;openWindow('','','http://www.jiayuan.com/usercp/service/vip_tstc.php?type=4',600,358);" />&nbsp;需要爱情密码</label>
                                （知道密码才能看到照片，限<a href="http://www.jiayuan.com/usercp/charge/upgrade.php?from=photopwd" class="search_tc01" style="color:red;text-decoration:underline" target="_blank">VIP会员</a>使用）
                            </dd>
                            <dd id="show_password" class="love_key" style="display:none">您现在的爱情密码：<strong id="showPass2"></strong>，如需修改<a style="color:#BC008D" href="javascript:show_photo_privacy_set_div(4);">请点这里</a></dd>
                            <dd class="love_notice">提示：如需删除爱情密码，重新选择其他显示模式即可。</dd>
                            <dd class="set_button">
                                <input type="button" class="save" value="保存并继续" onClick="skip()" onmousedown="send_jy_pv2('editprofile|save_photo|m|168103003');" />
                                <input type="button" value="跳过此页" class="skip" onClick="skip()" onmousedown="send_jy_pv2('editprofile|skip_photo|m|168103003');" />
                            </dd>
                        </dl>
                        <!-- 照片显示权限设置结束 -->
                    </div>
                </div>
            </div>
            <div id="bg" style="display:none"></div>
            <!--弹出层 start-->
            <div class="layer" id="open_window" style="display:none;">
                <div class="layer_box">
                    <div class="layer_title" id="open_window_head">
                        <h2><img src="http://images1.jyimg.com/w4/profile_new/i/op_but_close.gif" onclick="closeWindow()" title="关闭" /><span id="open_window_title"></span></h2></div>
                    <div class="layer_content" id="open_window_content"></div>
                    <div id="iframe_loading" style="position: absolute;"><img id="loading_focus" src="http://images1.jyimg.com/w4/profile_new/i/loading.gif" /></div>
                </div>
            </div>
            <div class="info_right">
                <h2>资料完整度：<span class="span101203_1">{{$score}}分</span></h2>
                <div class="integrality">
                    <div class="plan" style="width:{{$score}}%;">
                        <div class="progress_jindu">{{$score}}</div>
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
                    <a href="{{ route('base_mean') }}">去补充基本资料</a>
                </div>
                <div class="why">
                    <h3>为什么要上传照片？</h3>
                    <p>世纪佳缘统计，有照片的会员征友成功率是无照片会员的<strong style="color:red;">6倍</strong>！清晰生动的照片能为您吸引更多的目光，让更多的异性关注您。</p>
                    <p>头像照是您在世纪佳缘上最常被其他异性看到的头像照片，90%的会员在搜索时会选择有头像照的会员进行联系。您的头像照会出现在：搜索结果里、信件正文里、异性的佳缘首页里、在线聊天频道里、礼物附言里等等，是异性了解您、进而联系您最为关键的第一印象。</p>
                </div>
                <div class="whybg"></div>
                &nbsp;&nbsp;
            </div>
            <!-- 右边结束 -->
        </div>
        <div class="borderbg"><img src="http://images1.jyimg.com/w4/usercp/i/border_bottom.jpg" /></div>
    </div>

    <!-- 上传照片 -->
    <iframe id="upload_photo_iframe" name="upload_photo_iframe" style="width:0px;height:0px;display:none;"></iframe>
    <div class="upload_photo" style="display:none;" id="upload_photo">
        <div class="float_content">
            <div class="div_title"><strong>上传照片</strong><img src="{{ asset('img/home/close.gif') }}" alt="关闭" onClick="closeDiv('upload_photo')" /></div>
            <!--照片导航 B-->
            <div class="clear"></div>
            <div class="uploadNav">
                <ul class="clearfix">
                    <li class="upSelected"><a href="javascript:;">本地照片</a></li>
                </ul>
            </div>
            <!--照片导航 E-->
            <div class="upload_content">
                <!--本地照片 B-->
                <div class="localPic" id="localPic">
                    <form enctype="multipart/form-data" name="frm_upload" id="frm_upload" method="post" action="{{ route('upload_img') }}" target="upload_photo_iframe">
                         {{ csrf_field() }}
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <input type="hidden" name="MAX_FILE_SIZE" id="max_file_size" value="5242880" />
                            <input type="hidden" name="upload_quick" id="upload_quick" value="0" />
                            <img id="oFileChecker" style="width:0px;height:0px" onload="check_photo_size(0)" />
                            <tr>
                                <td width="350">
                                    <dl id="upfile_containter" class="upfile_containter">
                                        <dt>选择要上传的照片：</dt>
                                        <dd>
                                            <input type="file" class="file uploadFile" name="upload_file[]" onchange="check_fileszie(this,0);" size="40" style="width:300px;" />
                                        </dd>
                                    </dl>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="button" class="shangchuan" value="上传照片" onmousedown="" onClick="document.getElementById('upload_quick').value='0';upload_photo(0);" />
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div>
                        <strong>温馨提示：</strong>
                        <p>1、照片支持格式：jpg、jpeg、gif、png。</p>
                        <p>2、每张照片大小不要超过
                            <font style="color:#dd4083; font-weight:bold;">5M</font>，如果您的照片过大不能上传，请点击<a href="http://www.jiayuan.com/helpcenter/list.php?type1=1&type2=1&type3=18#art420" target="_blank">这里</a>。</p>
                        <p>3、已通过手机号认证的会员，可将照片添加至彩信内容，在主题或文字处输入世纪佳缘，发送到10663355即可(仅限中国移动用户)。</p>
                        <p>4、请勿上传：非本人、背影、与现年龄不符、裸露、军装照和带有政治色彩的照片，否则将予以删除，并将取消赠送看信宝。</p>
                        <p>5、为获得更好的征友效果，建议您上传正面或微侧面能够完整露出脸庞的照片，不要让墨镜、帽子或头发挡住脸庞。</p>
                    </div>
                </div>
                <!--本地照片 E-->
            </div>
        </div>
    </div>
   
    <!-- 如何上传好照片 -->
    <div class="monolog_div" id="monolog_div" style="display:none;">
        <!--圆角矩形背景层 开始-->
        <div class="bg100626">
            <b class="bg100625_l l100625_1"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_4"></b>
            <div class="bg100626_in"></div>
            <b class="bg100625_l l100625_4"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_1"></b>
        </div>
        <!--圆角矩形背景层 结束-->
        <div class="float_content">
            <div class="div_title"><strong>如何上传好照片</strong><img src="{{ asset('img/home/close.png') }}" alt="关闭" onClick="closeDiv('monolog_div')" /></div>
            <div class="monolog_content" style="text-align:center">
                <img src="{{ asset('img/home/goodphoto_m.jpg') }}" />
                <br />
                <!--<img src="http://images1.jyimg.com/w4/usercp/i/iknow.jpg" onClick="closeDiv('monolog_div')" style="cursor:pointer;" />-->
                <!--new 我知道了begin-->
                <a style="width:129px; height:25px; background:url(http://images1.jyimg.com/w4/usercp/i/new_uploadPic/r3.gif) no-repeat; font-size:14px; font-weight:bold; color:#fff; cursor:pointer; display:inline-block; text-align:center; line-height:25px;" onClick="closeDiv('monolog_div')">我知道了</a>
                <!--new 我知道了end-->
            </div>
        </div>
    </div>


    <!-- 正在上传 -->
    <div id="uploading" class="uploading" style="display:none;">
        <div class="div_title"><strong>正在上传</strong><img src="{{ asset('img/home/close.gif') }}" alt="关闭" onClick="closeDiv('uploading')" /></div>
        <div class="loading"><img src="{{ asset('img/home/schedule.gif') }}" alt="" />
            <br />文件正在上传，请勿关闭此页</div>
    </div>
    <!--美图秀秀-->
    <!--<div style="display: none;" class="my_hidder_layer">-->
    <div id="mtxx-swf" class="mtxx-swf" style="display: none;">
        <div id="altContent"></div>
    </div>
   
    <div class="photo_purview" id="photo_set_mask_1" style="display:none">
        <!--圆角矩形背景层 开始-->
        <div class="bg100627">
            <b class="bg100625_l l100625_1"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_4"></b>
            <div class="bg100627_in"></div>
            <b class="bg100625_l l100625_4"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_1"></b>
        </div>
        <!--圆角矩形背景层 结束-->
        <div class="float_content">
            <div class="div_title"><strong>照片显示权限设置</strong><img src="http://images1.jyimg.com/w4/usercp/i/close.jpg" alt="关闭" onClick="close_photo_privacy_set_div('photo_set_mask_1')" /></div>
            <div class="div091014inbox">
                <p class="t t14">是否保存照片显示模式为所有人可见？</p>
                <p class="btn"><a href="javascript:save_photo_privacy_set(1, '');">确 定</a><a href="javascript:close_photo_privacy_set_div('photo_set_mask_1')" class="lan">取 消</a></p>
            </div>
        </div>
    </div>
    <div class="photo_purview" id="photo_set_mask_2" style="display:none">
        <!--圆角矩形背景层 开始-->
        <div class="bg100627">
            <b class="bg100625_l l100625_1"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_4"></b>
            <div class="bg100627_in"></div>
            <b class="bg100625_l l100625_4"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_1"></b>
        </div>
        <!--圆角矩形背景层 结束-->
        <div class="float_content">
            <div class="div_title"><strong>照片显示权限设置</strong><img src="http://images1.jyimg.com/w4/usercp/i/close.jpg" alt="关闭" onClick="close_photo_privacy_set_div('photo_set_mask_2')" /></div>
            <div class="div091014inbox">
                <p class="t t14">是否保存照片显示模式为会员可见？</p>
                <p class="btn"><a href="javascript:save_photo_privacy_set(2, '');">确 定</a><a href="javascript:close_photo_privacy_set_div('photo_set_mask_2')" class="lan">取 消</a></p>
            </div>
        </div>
    </div>
    <div class="photo_purview" id="photo_set_mask_5" style="display:none">
        <!--圆角矩形背景层 开始-->
        <div class="bg100627">
            <b class="bg100625_l l100625_1"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_4"></b>
            <div class="bg100627_in"></div>
            <b class="bg100625_l l100625_4"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_1"></b>
        </div>
        <!--圆角矩形背景层 结束-->
        <div class="float_content">
            <div class="div_title"><strong>照片显示权限设置</strong><img src="http://images1.jyimg.com/w4/usercp/i/close.jpg" alt="关闭" onClick="close_photo_privacy_set_div('photo_set_mask_5')" /></div>
            <div class="div091014inbox">
                <p class="t t14">是否保存照片显示模式为有照片会员可见？</p>
                <p class="btn"><a href="javascript:save_photo_privacy_set(5, '');">确 定</a><a href="javascript:close_photo_privacy_set_div('photo_set_mask_5')" class="lan">取 消</a></p>
            </div>
        </div>
    </div>
    <div class="photo_purview" id="photo_set_mask_3" style="display:none">
        <!--圆角矩形背景层 开始-->
        <div class="bg100627">
            <b class="bg100625_l l100625_1"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_4"></b>
            <div class="bg100627_in"></div>
            <b class="bg100625_l l100625_4"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_1"></b>
        </div>
        <!--圆角矩形背景层 结束-->
        <div class="float_content">
            <div class="div_title"><strong>照片显示权限设置</strong><img src="http://images1.jyimg.com/w4/usercp/i/close.jpg" alt="关闭" onClick="close_photo_privacy_set_div('photo_set_mask_3')" /></div>
            <div class="div091014inbox">
                <p class="t t14">据统计，设置星级会员可见的会员收信量会下降90％以上。</p>
                <p class="btn"><a href="javascript:save_photo_privacy_set(3, '');">确 定</a><a href="javascript:close_photo_privacy_set_div('photo_set_mask_3')" class="lan">取 消</a></p>
            </div>
        </div>
    </div>
    <div class="photo_purview" id="photo_set_mask_4_1" style="display:none">
        <!--圆角矩形背景层 开始-->
        <div class="bg100627">
            <b class="bg100625_l l100625_1"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_4"></b>
            <div class="bg100627_in"></div>
            <b class="bg100625_l l100625_4"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_1"></b>
        </div>
        <!--圆角矩形背景层 结束-->
        <div class="float_content">
            <div class="div_title"><strong>照片显示权限设置</strong><img src="http://images1.jyimg.com/w4/usercp/i/close.jpg" alt="关闭" onClick="close_photo_privacy_set_div('photo_set_mask_4_1')" /></div>
            <div class="div091014inbox">
                <p class="t t14">据统计，设置爱情密码的会员收信量会大幅度下降。</p>
                <p class="l">
                    <input style="display:none" name="fxkj_set_1" id="fxkj_set_1" type="checkbox" />
                    <!--我主动发信联系的人，无需密码即可看到我的照片。<br/>-->
                    请输入您的爱情密码：
                    <input name="password_1" id="password_1" type="text" value="" />
                </p>
                <p class="btn"><a href="javascript:save_photo_privacy_password(1);">确 定</a><a href="javascript:close_photo_privacy_set_div('photo_set_mask_4_1')" class="lan">取 消</a></p>
            </div>
        </div>
    </div>
    <div class="photo_purview" id="photo_set_mask_4_2" style="display:none">
        <!--圆角矩形背景层 开始-->
        <div class="bg100627">
            <b class="bg100625_l l100625_1"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_4"></b>
            <div class="bg100627_in"></div>
            <b class="bg100625_l l100625_4"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_1"></b>
        </div>
        <!--圆角矩形背景层 结束-->
        <div class="float_content">
            <div class="div_title"><strong>照片显示权限设置</strong><img src="http://images1.jyimg.com/w4/usercp/i/close.jpg" alt="关闭" onClick="close_photo_privacy_set_div('photo_set_mask_4_2')" /></div>
            <div class="div091014inbox">
                <p class="t t14">据统计，设置爱情密码的会员收信量会大幅度下降。</p>
                <p class="l">
                    <input name="fxkj_set_2" style="display:none" id="fxkj_set_2" type="checkbox" />
                    <!--我主动发信联系的人，无需密码即可看到我的照片。<br/>-->
                    您现在的爱情密码：<strong id='showPass'></strong>，如需修改请<a href="javascript:closeDiv('photo_set_mask_4_2');openDiv('photo_set_mask_4_1')">点这里</a></p>
                <p class="btn"><a href="javascript:save_photo_privacy_password(2);">确 定</a><a href="javascript:close_photo_privacy_set_div('photo_set_mask_4_2')" class="lan">取 消</a></p>
            </div>
        </div>
    </div>
    <div class="photo_purview" id="photo_set_mask_close" style="display:none; height:200px;">
        <!--圆角矩形背景层 开始-->
        <div class="bg100627">
            <b class="bg100625_l l100625_1"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_4"></b>
            <div class="bg100627_in"></div>
            <b class="bg100625_l l100625_4"></b><b class="bg100625_l l100625_3"></b><b class="bg100625_l l100625_2"></b><b class="bg100625_l l100625_1"></b>
        </div>
        <div class="float_content">
            <div class="div_title"><strong>照片显示权限设置</strong><img src="http://images1.jyimg.com/w4/usercp/i/close.jpg" alt="关闭" onClick="close_photo_privacy_set_div('photo_set_mask_close')" /></div>
            <div class="div091014inbox">
                <p class="t t14">照片显示模式保存成功</p>
                <p class="btn"><a href="javascript:close_photo_privacy_set_div('photo_set_mask_close')" class="lan lan102103">关 闭</a></p>
            </div>
        </div>
    </div>
    </div>
@endsection



   