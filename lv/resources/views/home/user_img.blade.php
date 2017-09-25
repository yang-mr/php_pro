<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta name="keywords" content="交友,交友网,征婚交友,网上交友,征婚,征婚网,征婚交友网,交友中心,婚恋交友" />
    <meta name="description" content="青春不常在，抓紧谈恋爱！缘分可遇也可求，该出手时就出手。世纪佳缘是国内领先的在线婚恋交友平台，提供丰富多彩的交友征婚活动，1.7亿会员，让缘分千万里挑一！" />
    <title>我的相册_    {{ config('app.name') }}</title>
    <link href="{{ asset('css/home/user_img_stype.css') }}" rel="stylesheet">

    <script type="text/javascript" src="http://images1.jyimg.com/w4/usercp/j/jquery.lightbox-0.5.js"></script>
    <script type="text/javascript" src="http://images1.jyimg.com/w4/profile_new/j/window.js"></script>
    
    <script src="http://images1.jyimg.com/w4/case/common/j/jquery.fancybox.js" type="text/javascript"></script>
    <script src="http://images1.jyimg.com/w4/case/common/j/case_uicommon.js" type="text/javascript"></script>
    <script src="http://open.web.meitu.com/sources/xiuxiu.js" type="text/javascript"></script>
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
    var mtxx_type = "" //判断编辑的是头像还是生活照
    var pid = ""; //当前照片id
    var mtxx_img_url = "";
    var mtxx_upload_url = "";
    var xiuxiuInited = false;
    var mtxx_pos = 0; //我的生活照中，当前编辑的美化图的位置
    xiuxiu.setLaunchVars("uploadBtnLabel", "保存", "lite");
    xiuxiu.setLaunchVars("language", "zh");
    xiuxiu.embedSWF("altContent", 1, 700, 600, "lite");
    xiuxiu.onInit = function(id) { xiuxiuInited = true; }
    xiuxiu.onBeforeUpload = function(data, id) {
        if (mtxx_type == 'pro') {
            send_jy_pv2('|meitu_pro_m_submit|168103003');
        }
        if (mtxx_type == 'album') {
            send_jy_pv2('|meitu_album_m_submit|168103003');
        }
        var size = data.size;
        var limit = parseInt(my_getbyid('max_file_size').value);
        if (size > limit) {
            alert("优化后照片文件过大,图片不能超过5M");
            return false;
        }

        if (upload_pic_limit < 0) {
            alert("亲，超出上传图片数目限制，请开通vip");
            return false;
        }
        //        openDiv("uploading");
        return true;
    }


    xiuxiu.onUploadResponse = function(data) {
        //        closeDiv("uploading");
        //        alert(data);
        alert(data);
        var ptn_succes = /照片上传成功/i; //danten
        if (ptn_succes.test(data)) {
            //            alert(mtxx_pos);
            if (pid) {
                //                document.getElementById("upload_photo_iframe").src="/usercp/photodel.php?type=js&pid="+pid;
                var url = "http://upload.jiayuan.com/usercp/photodel.php?type=js&pid=" + pid;
                //                alert(url);
                //                var xmlHttp;
                //                try {    // Firefox, Opera 8.0+, Safari
                //                    xmlHttp=new XMLHttpRequest();
                //                } catch (e) {    // Internet Explorer
                //                    try {
                //                        xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                //                    } catch (e) {
                //                        try {
                //                            xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                //                        } catch (e) {
                //                            alert("Your browser does not support AJAX!");
                //                            return false;
                //                        }
                //                    }
                //                }
                //                xmlHttp.onreadystatechange=function(){
                //                    if(xmlHttp.readyState==4 || xmlHttp.status==200){
                //                        alert('响应成功'+xmlHttp.responseText);
                //                    }else{alert('响应失败!');}
                //                }
                //                xmlHttp.open('GET',url,true);
                ////                httpAdapter.setRequestHeader();
                //                xmlHttp.send(null);
            }
            var jump_url = "http://upload.jiayuan.com/usercp/photo.php?mtxx_pos=" + mtxx_pos.toString(); //重刷是为了获得审核的图片
            //            alert(jump_url);
            //            document.write("\<script type=\'text\/javascript\'\>alert('照片上传成功，请等待审核~')\;self.parent.location.href=\'"+jump_url+"\';\<\/script\>");//避免删除弹窗
            alert('照片上传成功，请等待审核~');
            self.location.href = jump_url;
        }

    }

    xiuxiu.onDebug = function(data, id) {
        alert("错误响应" + data);
    }
    xiuxiu.onClose = function(id) {
        //        $.uicommon.myself_fancybox_close();
        closeDiv("mtxx-swf");
        $("#mtxx-swf").html("<div id='altContent'></div>");
        xiuxiuInited = false;
    }

    function mtxx_click(obj) {
        if (mtxx_type == 'pro') {
            send_jy_pv2('|meitu_pro_m_edit|168103003');
        }
        if (mtxx_type == 'album') {
            send_jy_pv2('|meitu_album_m_edit|168103003');
        }

        mtxx_img_url = $(obj).parent().parent().find("#img_mt").attr('src');
        var patn_img = /t.jpg|t.jpeg|t.gif|t.png/i; //t、d分别是审核后的小，大图，o是上传的原图(2010/11/03之后才有)。
        var patn_pid = /\/([0-9]+)(t.jpg|t.jpeg|t.gif|t.png)/i;
        if (patn_pid.test(mtxx_img_url)) {
            pid = mtxx_img_url.match(patn_pid)[1];
        }
        var pos = mtxx_img_url.search(patn_img);
        if (patn_img.test(mtxx_img_url)) {
            mtxx_img_url = mtxx_img_url.substr(0, pos) + "d" + mtxx_img_url.substr(pos + 1);
        }
        if (pid) {
            mtxx_upload_url = "http://upload.jiayuan.com/usercp/photoupload.php?type=js&pid=" + pid;
        } else {
            mtxx_upload_url = "http://upload.jiayuan.com/usercp/photoupload.php?type=js&uid=168103003";
        }

        //me test
        mtxx_upload_url = ""

        if (xiuxiuInited) {
            var id = 'lite';
            xiuxiu.loadPhoto(mtxx_img_url, false, id);
            xiuxiu.setUploadURL(mtxx_upload_url, id);
            xiuxiu.setUploadType(2, id); //表单上传 Content-type:multipart/form-data；
            xiuxiu.setUploadDataFieldName("upload_file[]");
            xiuxiu.setUploadArgs({}, id);
        } else {

            xiuxiu.setLaunchVars("uploadBtnLabel", "保存", "lite");
            xiuxiu.setLaunchVars("language", "zh");
            xiuxiu.embedSWF("altContent", 1, 700, 600, "lite");
            xiuxiu.onInit = function(id) {
                xiuxiu.loadPhoto(mtxx_img_url, false, id);
                xiuxiu.setUploadURL(mtxx_upload_url, id);
                xiuxiu.setUploadType(2, id); //表单上传 Content-type:multipart/form-data；
                xiuxiu.setUploadDataFieldName("upload_file[]");
                xiuxiu.setUploadArgs({}, id);
            }
        }

        //        $.uicommon.myself_fancybox("#mtxx-swf");
        openDiv("mtxx-swf", 700, 600); //弹层
    }
    </script>
    <script type="text/javascript">
    send_jy_pv2('|vip_entry_scgdzp_show|'); //统计上传更多照片vip入口的展示次数
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
    //add by liuwei at 2010-05-07
    function clickCount(args) {

        //var ajax = initAjax();
        var url2 = location.href;
        var i = "www";
        if (/msn/.test(url2)) {
            i = "msn";
        }
        if (/sina/.test(url2)) {
            i = "sina";
        }
        //alert(i);
        send_jy_pv2(i + "_change_button_upload_" + args);
        //ajax.open("GET","/register/notecount.class.php?method=addPhotoChange&data="+args+"&url="+i,true);
        //ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //ajax.send(null);
    }

    function delavatar() {
        if (confirm('删除头像照后，如果不上传新的头像照，在＂搜索有照片的人＂时，您将不会被搜到')) {
            document.getElementById("upload_photo_iframe").src = '/usercp/avatardel.php?type=js&new=1';
        }
    }

    function delphoto(pid) {
        if (confirm('确认删除?')) {
            document.getElementById("upload_photo_iframe").src = '/usercp/photodel.php?type=js&pid=' + pid;
        }
    }

    function dohide(id, status) {
        var xmlHttp;
        try { // Firefox, Opera 8.0+, Safari    
            xmlHttp = new XMLHttpRequest();
        } catch (e) { // Internet Explorer    
            try {
                xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    alert("Your browser does not support AJAX!");
                    return false;
                }
            }
        }
        status = status ? 1 : 0;
        url = '/usercp/photohide.php?id=' + id + '&hide=' + status;
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4) {
                if (xmlHttp.responseText == 1) {
                    alert('私密照片设置成功');
                    my_getbyid('hidden_display_' + id).style.display = "";
                    my_getbyid('hidden_control_' + id).style.display = "none";
                    my_getbyid('unhidden_control_' + id).style.display = "";
                    //my_getbyid('hidden_'+id).onclick=function(){dohide(id,0);}
                } else if (xmlHttp.responseText == 0) {
                    alert('公开照片设置成功');
                    my_getbyid('hidden_display_' + id).style.display = "none";
                    my_getbyid('hidden_control_' + id).style.display = "";
                    my_getbyid('unhidden_control_' + id).style.display = "none";
                    //my_getbyid('hidden_'+id).onclick=function(){dohide(id,1);}
                } else {
                    alert('操作失败');
                }
            }
        }
        xmlHttp.open("GET", url, true);
        xmlHttp.send(null);
    }

    function upload_photo(index) {
        alert(check_form(0) + '');
        if (index == 0) {
            if (check_form(0)) {
                var upload_form = document.getElementById("frm_upload");
                closeDiv("upload_photo");
                openDiv("uploading");
                upload_form.submit();
            }
        } else {
            if (check_form(1)) {
                var upload_form = document.getElementById("wl_frm_upload");
                closeDiv("upload_photo");
                openDiv("uploading");
                upload_form.submit();
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

    function add_file_item(index) //index是序列,对应获取元素ID
    {
        var wrap;
        var text;
        var htmlStr;
        if (index == 0) {
            wrap = document.getElementById('localPic');
            htmlStr = '照片';
            text = '：<input type="file" class="file uploadFile" name="upload_file[]" onchange="check_fileszie(this,0);" size="40" style="width:300px;" />'
            add_item(wrap, 't')
        } else if (index == 1) {
            wrap = document.getElementById('networkPic');
            htmlStr = '照片地址：';
            text = '<input type="text" class="file uploadFile inputBg" name="wl_upload_file[]" onchange="check_fileszie(this,1);" size="40" style="width:275px;" />'
            add_item(wrap)
        }

        function add_item(w, num) //w:父元素,num:是否显示index_num
        {
            var files = getElementsByClassName(w, 'uploadFile')
            var index_num;
            if (num) { index_num = files.length + 1 } else { index_num = '' }
            var newfile = document.createElement("dd");
            var oContainter = getElementsByClassName(w, 'upfile_containter')[0]
            oContainter.appendChild(newfile);
            htmlStr += index_num + text;
            newfile.innerHTML = htmlStr;
        }
    }

    function del_file_item() {
        var oContainter = document.getElementById("upfile_containter");
        var lastChild = oContainter.lastChild;
        oContainter.removeChild(lastChild);
    }

    $(function() {
        $('#life_pic .showpic a').lightBox();
    });

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

    function photo_desc_sub(_uid, _pid) {
        var _desc = document.getElementById("desc_text_" + _pid).value;
        if (_desc == "") {
            alert("请输入照片描述！");
            document.getElementById("desc_text_" + _pid).focus();
            return false;
        }

        if (_desc.length > 20) {
            alert("照片描述最多只能输入20个字符！");
            document.getElementById("desc_text_" + _pid).focus();
            return false;
        }

        var _rd = Math.ceil((new Date().getTime() - 1262164318867) / 100);

        var xmlHttp_desc = initAjax();
        var url = 'photodesc.php?uid=' + _uid + '&pid=' + _pid + '&rd=' + _rd;
        xmlHttp_desc.onreadystatechange = function() {
            if (xmlHttp_desc.readyState == 4) {
                if (xmlHttp_desc.status == 200) {
                    var text = xmlHttp_desc.responseText;
                    if (text == "1") {
                        alert("操作成功！通过审核后显示！");
                        close_edit_photo_desc(_pid);
                        document.getElementById("desc_title_" + _pid).className = "on";
                        document.getElementById("desc_title_" + _pid).onclick = "";
                        document.getElementById("desc_title_" + _pid).innerHTML = "修改描述";
                        document.getElementById("desc_show_span_" + _pid).innerHTML = _desc;
                    } else {
                        alert("操作失败！");
                        alert(text);
                        close_edit_photo_desc(_pid);
                    }
                }
            }
        };
        xmlHttp_desc.open("POST", url, true);
        xmlHttp_desc.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlHttp_desc.send("photo_desc=" + _desc);
    }
    /***
     * 说明：添加了文本输入框字符个数限制
     * 作者：闫争棵
     * 日期：2014-01-06
     * 参数：pid int textaId
     *      num int strNum
     * 返回：boll 
     */
    function verify_str_num(pid, num) {
        var desc = document.getElementById("desc_text_" + pid).value;
        var num = typeof num == 'undefined' ? 20 : parseInt(num);
        if (desc.length > num) {
            var newstr = desc.substr(0, 20);
            document.getElementById("desc_text_" + pid).value = newstr;
            alert("照片描述最多只能输入" + num + "个字符！");
            document.getElementById("desc_text_" + pid).focus();
            return false;
        }
        return true;
    }
    </script>

     <!--</div>-->
    <script type="text/javascript">
    var DKL = my_getbyid;
    var nowPrivacy = 1;
    var nowPassWord = '';
    var start = false;
    var nowFxkjSet = 'off';

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

    function save_photo_privacy_set(value, pass, fxkj) {
        if (value < 1 || value > 5) {
            alert('设置参数错误！');
            return;
        }
        var param = '?value=' + value;
        if (value == 4) {
            var have_pw = 0,
                have_fxkj = 0;
            if (pass.length > 0 && pass != nowPassWord) {
                if (/=|&|#|\s/.test(pass)) {
                    alert('爱情密码中含有非法字符！');
                    return;
                }
                if (/[\u4E00-\u9FA5]/g.test(pass)) {
                    alert('爱情密码中请不要包含中文汉字');
                    return;
                }
                param += '&pass=' + pass;
                have_pw = 1;
            }
            if (fxkj.length > 1 && fxkj != nowFxkjSet) {
                if (fxkj != 'on' && fxkj != 'off') {
                    alert('主动发信对方可见选项设置错误！');
                    return;
                }
                param += '&fxkj_set=' + fxkj;
                have_fxkj = 1;
            }
            if (have_pw == 0 && have_fxkj == 0 && nowPrivacy == 4) {
                alert('需要爱情密码选项设置未改变！');
                return;
            }
        }
        if (start == true) { return; }
        var xmlHttp = initAjax();
        if (typeof(xmlHttp) != "object") {
            alert("Your browser does not support ajax");
            return;
        }
        start = true;
        var url = "save_privacy.php" + param;
        if (start) {
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                    var Result = xmlHttp.responseText;
                    Result = Math.ceil(Result);
                    if (Result == -1) {
                        alert('登录已超时，请登录后再修改！');
                    }
                    if (Result == -2) {
                        alert('照片隐私设置参数无效！');
                    }
                    if (Result == -4) {
                        alert('爱情密码中请不要包含中文汉字! ');
                    }
                    if (Result == 0) {
                        nowPrivacy = value;
                        if (value == 4) {
                            if (have_pw == 1) {
                                nowPassWord = pass;
                            }
                            if (have_fxkj == 1) {
                                nowFxkjSet = fxkj;
                                if (fxkj == 'on') {
                                    DKL('fxkj_set_1').checked = true;
                                    DKL('fxkj_set_2').checked = true;
                                } else {
                                    DKL('fxkj_set_1').checked = false;
                                    DKL('fxkj_set_2').checked = false;
                                }
                            }
                        }
                        if (value == 4) {
                            closeDiv('photo_set_mask_4_1');
                            closeDiv('photo_set_mask_4_2');
                        } else {
                            var id = get_photo_privacy_set_div_id(value, nowPrivacy);
                            closeDiv(id);
                        }
                        openDiv('photo_set_mask_close');
                        changePasswordShow();
                    }
                    start = false;
                }
            }
            xmlHttp.open("GET", url, true);
            xmlHttp.send(null);
        }
    }

    function save_photo_privacy_password(which) {
        if (which != 1 && which != 2) {
            return -1;
        }
        var sendmailsetId = 'fxkj_set_' + which;
        var fxkj_set;
        if (DKL(sendmailsetId).checked) {
            fxkj_set = 'on';
        } else {
            fxkj_set = 'off';
        }
        if (fxkj_set == nowFxkjSet) {
            fxkj_set = '';
        }
        if (which == 1) {
            var passwordId = 'password_' + which;
            var password = DKL(passwordId).value;
        } else {
            var password = '';
        }

        save_photo_privacy_set(4, password, fxkj_set);
    }

    function changePasswordShow() {
        DKL('showPass').innerHTML = nowPassWord;
        DKL('showPass2').innerHTML = nowPassWord;
        DKL('password_1').value = nowPassWord;
        if (nowPrivacy == 4) {
            DKL('show_password').style.display = '';
        } else {
            DKL('show_password').style.display = 'none';
        }
    }
    </script>
</head>

<body>
    <div class="my_infomation">
        <div class="navigation"><a href="{{ route('home') }}" onmousedown="send_jy_pv2('editprofile|my_home|m|168103003');">个人中心</a>&nbsp;&gt;&nbsp;我的照片</div>
        <div class="borderbg"><img src="http://images1.jyimg.com/w4/usercp/i/i520/border_top.jpg" /></div>
        <div class="info_content">
            <!-- 左侧开始 -->
            <div class="info_left">
                <ul>
                    <li class="mark" onmousedown="send_jy_pv2('editprofile|category_base|m|168103003');"><a href="http://www.jiayuan.com/usercp/profile.php?action=base">基本资料</a></li>
                    <li class="ok" onmousedown="send_jy_pv2('editprofile|category_note|m|168103003');"><a href="http://www.jiayuan.com/usercp/note.php">内心独白</a></li>
                    <li class="on"><a href="javascript:;">我的照片</a></li>
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
                    <a class="return_jy" href="http://www.jiayuan.com/usercp/index.php" onmousedown="send_jy_pv2('editprofile|return_home|m|168103003');">返回我的佳缘</a>
                </div>
            </div>
            <!-- 左侧结束 -->
            <!-- 中间开始 -->
            <!--mtxx fix-->
            <style>
            .mtxxTip {
                position: absolute;
                left: 117px;
                top: 75px;
                width: 228px;
                height: 95px;
                background: url(http://images2.jyimg.com/w4/usercp/i/mtkk/mtxxtc_03.png) no-repeat;
                opacity: 0.95;
                z-index: 9999;
            }

            .mtxxTip .closemtxxTip {
                width: 20px;
                height: 20px;
                position: absolute;
                top: 7px;
                right: 1px;
            }

            .mt2 {
                margin-top: 2px;
            }

            .mtxxEnt {
                margin-top: -3px;
                margin-right: 2px;
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
                            <div class="image"> <img id="img_mt" src="http://images1.jyimg.com/w4/global/i/zwzp_m_bp.jpg" width=120 height=120 />
                            </div>
                            <!--mtxx fix-->
                            <p class="mt2">
                                <a onClick="openDiv('upload_photo', 560, 400);" onmousedown="send_jy_pv2('editprofile|uploadphoto|m|168103003');" href="javascript:;">上传照片</a>
                            </p>
                            <!--mtxx fix-->
                        </div>
                        <!-- new pic_notice begin-->
                        <div class="new_pic_notice" style="float:right;">
                            <p class="WLclearfix">
                                <a href="http://upload.jiayuan.com/register/step_new_2.php?to_url=http://www.jiayuan.com/usercp/photo.php" target="_blank" onclick="clickCount(1);" onmousedown="send_jy_pv2('editprofile|change_avatar|m|168103003');" class="new_add_photo"></a><a onClick="openDiv('monolog_div', 709, 490);" class="wl-ml13" href="javascript:;">如何上传好照片</a>
                            </p>
                            <ul class="notice-refers">
                                <li>有照片会员，收到的<span>信件</span>比没照片的会员多<span>11倍</span></li>
                            </ul>
                        </div>
                        <!-- new pic_notice end-->
                        <!-- old pic_notice begin-->
                        <div class="pic_notice" style="display:none;">
                            <a onClick="openDiv('upload_photo', 560, 400);" onmousedown="send_jy_pv2('editprofile|uploadphoto|m|168103003');" href="javascript:;" class="add_photo">上传照片</a><a onClick="openDiv('monolog_div', 709, 490);" href="javascript:;" style="color:#0066CD; text-decoration:underline;" class="up_photo ">如何上传好照片</a>
                        </div>
                        <!-- 我的生活照 -->
                        <!-- old pic_notice end-->
                        <div class="life_pic">
                            <h2>我的生活照</h2>
                            <ul id="life_pic">
                                <li class="nopic">
                                    <div class="showpic">
                                        <p><img src="http://images1.jyimg.com/w4/global/i/mryz_m_b.jpg" onClick="openDiv('upload_photo', 560, 400);" /></p>
                                    </div>
                                    <div class="pic_control_2" style="padding:8px 0;">
                                        <input type="button" class="upload_pic" value="" onClick="openDiv('upload_photo', 560, 400);" />
                                    </div>
                                </li>
                                <li class="nopic">
                                    <div class="showpic">
                                        <p><img src="http://images1.jyimg.com/w4/global/i/mryz_m_b.jpg" onClick="openDiv('upload_photo', 560, 400);" /></p>
                                    </div>
                                    <div class="pic_control_2" style="padding:8px 0;">
                                        <input type="button" class="upload_pic" value="" onClick="openDiv('upload_photo', 560, 400);" />
                                    </div>
                                </li>
                                <li class="nopic">
                                    <div class="showpic">
                                        <p><img src="http://images1.jyimg.com/w4/global/i/mryz_m_b.jpg" onClick="openDiv('upload_photo', 560, 400);" /></p>
                                    </div>
                                    <div class="pic_control_2" style="padding:8px 0;">
                                        <input type="button" class="upload_pic" value="" onClick="openDiv('upload_photo', 560, 400);" />
                                    </div>
                                </li>
                                <li class="nopic">
                                    <div class="showpic">
                                        <p><img src="http://images1.jyimg.com/w4/global/i/mryz_m_b.jpg" onClick="openDiv('upload_photo', 560, 400);" /></p>
                                    </div>
                                    <div class="pic_control_2" style="padding:8px 0;">
                                        <input type="button" class="upload_pic" value="" onClick="openDiv('upload_photo', 560, 400);" />
                                    </div>
                                </li>
                                <li class="nopic">
                                    <div class="showpic">
                                        <p><img src="http://images1.jyimg.com/w4/global/i/mryz_m_b.jpg" onClick="openDiv('upload_photo', 560, 400);" /></p>
                                    </div>
                                    <div class="pic_control_2" style="padding:8px 0;">
                                        <input type="button" class="upload_pic" value="" onClick="openDiv('upload_photo', 560, 400);" />
                                    </div>
                                </li>
                                <li class="nopic">
                                    <div class="showpic">
                                        <p><img src="http://images1.jyimg.com/w4/global/i/mryz_m_b.jpg" onClick="openDiv('upload_photo', 560, 400);" /></p>
                                    </div>
                                    <div class="pic_control_2" style="padding:8px 0;">
                                        <input type="button" class="upload_pic" value="" onClick="openDiv('upload_photo', 560, 400);" />
                                    </div>
                                </li>
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
            <!--弹出层 end-->
            <!-- 中间结束 -->
            <!-- 右边开始 -->
            <!--[if lte IE 6]>
        <script type="text/javascript" src="http://images1.jyimg.com/w4/msg/js/dd_belatedpng.js?09153"></script>
        <script>
            DD_belatedPNG.fix('.ie6png');
        </script>
        <![endif]-->
            <div class="info_right">
                <h2>资料完整度：<span class="span101203_1">45.5分</span></h2>
                <div class="integrality">
                    <div class="plan" style="width:45.5%;">
                        <div class="progress_jindu">45.5</div>
                        &nbsp;
                    </div>
                    <div style="left:85%;" class="progress_modelMain">
                        <div class="progress_model ie6png">
                        </div>
                        <div class="progress_modelNum ie6png">
                            85
                        </div>
                    </div>
                </div>
                <div class="pre_fen">
                    达到85分可得到优先推荐的资格哦~
                </div>
                <div class="preview">
                    <a href="http://www.jiayuan.com/usercp/profile.php?action=base" onmousedown="send_jy_pv2('editprofile|220059_14|m|168103003');">去补充基本资料</a>
                </div>
                <div class="why">
                    <h3>为什么要上传照片？</h3>
                    <p>世纪佳缘统计，有照片的会员征友成功率是无照片会员的<strong style="color:red;">6倍</strong>！清晰生动的照片能为您吸引更多的目光，让更多的异性关注您。</p>
                    <p>头像照是您在世纪佳缘上最常被其他异性看到的头像照片，90%的会员在搜索时会选择有头像照的会员进行联系。您的头像照会出现在：搜索结果里、信件正文里、异性的佳缘首页里、在线聊天频道里、礼物附言里等等，是异性了解您、进而联系您最为关键的第一印象。</p>
                </div>
                <div class="whybg"></div>
                &nbsp;&nbsp;
                <div id="ad_pos_14"></div>
                <script type='text/javascript' src='http://ads.jiayuan.com/ad.php?pd_id=7'></script>
            </div>
            <!-- 右边结束 -->
        </div>
        <div class="borderbg"><img src="http://images1.jyimg.com/w4/usercp/i/border_bottom.jpg" /></div>
    </div>
    <!-- 照片描述 -->
    <div id="photo_describe" class="photo_describe" style="display:none;">
        <div class="float_content">
            <div class="div_title"><strong>照片描述</strong><img src="http://images1.jyimg.com/w4/usercp/i/new_uploadPic/close.png" alt="关闭" onClick="closeDiv('photo_describe');" /></div>
            <div class="describe_content">
                <p>照片描述功能仅对星级会员开放，您还不是星级会员，马上升级，获得更多特权！</p>
                <p><a href="http://www.jiayuan.com/usercp/validateemail/certificate.php" target="_blank" onmousedown="send_jy_pv2('editprofile|goto_validate|m|168103003');"><img src="http://images1.jyimg.com/w4/usercp/i/update.jpg" alt="现在去升级" /></a>&nbsp;&nbsp;<a href="javascript:closeDiv('photo_describe');"><img src="http://images1.jyimg.com/w4/usercp/i/cancel.jpg" alt="取消" /></a></p>
            </div>
        </div>
    </div>
    <!-- 上传照片 -->
    <iframe id="upload_photo_iframe" name="upload_photo_iframe" style="width:0px;height:0px;display:none;"></iframe>
    <div class="upload_photo" style="display:none;" id="upload_photo">
        <div class="float_content">
            <div class="div_title"><strong>上传照片</strong><img src="http://images1.jyimg.com/w4/usercp/i/new_uploadPic/close.gif" alt="关闭" onClick="closeDiv('upload_photo')" /></div>
            <!--照片导航 B-->
            <div class="clear"></div>
            <div class="uploadNav">
                <ul class="clearfix">
                    <li class="upSelected"><a href="javascript:;">本地照片</a></li>
                    <li><a href="javascript:;">网络照片</a></li>
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
                                        <dd>照片1：
                                            <input type="file" class="file uploadFile" name="upload_file[]" onchange="check_fileszie(this,0);" size="40" style="width:300px;" />
                                        </dd>
                                    </dl>
                                </td>
                                <td width="180" valign="bottom" style="padding-bottom:7px; padding-bottom:5px\9;_padding-bottom:10px;"><span onClick="add_file_item(0)">更多</span></td>
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
                <!--网络照片 B-->
                <div class="networkPic" id="networkPic">
                    <form name="wl_frm_upload" id="wl_frm_upload" method="post" action="http://upload.jiayuan.com/usercp/photoupload_byurl.php?type=js" target="upload_photo_iframe">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <input type="hidden" name="MAX_FILE_SIZE" id="wl_max_file_size" value="5242880" />
                            <input type="hidden" name="upload_quick" id="wl_upload_quick" value="0" />
                            <img id="wl_oFileChecker" style="width:0px;height:0px" onload="check_photo_size(1)" />
                            <tr>
                                <td width="350">
                                    <dl id="wl_upfile_containter" class="upfile_containter">
                                        <dt></dt>
                                        <dd>照片地址：
                                            <input type="text" class="file uploadFile inputBg" name="wl_upload_file[]" onchange="check_fileszie(this,1);" size="40" style="width:275px;" />
                                        </dd>
                                    </dl>
                                </td>
                                <td width="180" valign="bottom" style="padding-bottom:7px; padding-bottom:5px\9;_padding-bottom:10px;"><span onClick="add_file_item(1)">更多</span></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <input type="button" class="shangchuan" value="上传照片" onmousedown="send_jy_pv2('|zpscyd_photo_wlzptc_sczp_rc|');send_jy_pv2('|zpscyd_photo_wlzptc_sczp_rs|168103003');" onClick="document.getElementById('wl_upload_quick').value='0';upload_photo(1);" />
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div>
                        <strong>温馨提示：</strong>
                        <p>1、您可以将网络相册、空间或博客相册中的照片上传至佳缘相册，照片格式应为：jpg、jpeg、gif、png；大小不超过5MB。</p>
                        <p>2、照片粘贴方法：在照片上点击右键，选择“复制图片地址”或“属性-地址”，将图片地址粘贴至输入框中，以http://开始，以.jpg/.jpeg/.gif/.png结束。</p>
                        <p>3、部分网络照片可能由于禁止外链、有其他水印等原因无法上传，敬请谅解。</p>
                        <p>4、请勿上传：非本人、背影、与现年龄不符、裸露、军装照和带有政治色彩的照片，否则将予以删除，并将取消赠送看信宝。</p>
                    </div>
                </div>
                <!--网络照片 E-->
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
            <div class="div_title"><strong>如何上传好照片</strong><img src="http://images1.jyimg.com/w4/usercp/i/new_uploadPic/close.png" alt="关闭" onClick="closeDiv('monolog_div')" /></div>
            <div class="monolog_content" style="text-align:center">
                <img src="http://images1.jyimg.com/w4/usercp/i/goodphoto_m.jpg" width="689" />
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
        <div class="div_title"><strong>正在上传</strong><img src="http://images1.jyimg.com/w4/usercp/i/new_uploadPic/close.png" alt="关闭" onClick="closeDiv('uploading')" /></div>
        <div class="loading"><img src="http://images1.jyimg.com/w4/usercp/i/schedule.gif" alt="" />
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
                    您现在的爱情密码：<strong id='showPass'><script>document.write(nowPassWord)</script></strong>，如需修改请<a href="javascript:closeDiv('photo_set_mask_4_2');openDiv('photo_set_mask_4_1')">点这里</a></p>
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
        <!--圆角矩形背景层 结束-->
        <div class="float_content">
            <div class="div_title"><strong>照片显示权限设置</strong><img src="http://images1.jyimg.com/w4/usercp/i/close.jpg" alt="关闭" onClick="close_photo_privacy_set_div('photo_set_mask_close')" /></div>
            <div class="div091014inbox">
                <p class="t t14">照片显示模式保存成功</p>
                <p class="btn"><a href="javascript:close_photo_privacy_set_div('photo_set_mask_close')" class="lan lan102103">关 闭</a></p>
            </div>
        </div>
    </div>
    <iframe style="display:none;" name="mobile_pay_ifr" id="mobile_pay_ifr" scrolling="no" width="654" height="600" allowTransparency="true" frameborder="0"></iframe>

</body>

</html>