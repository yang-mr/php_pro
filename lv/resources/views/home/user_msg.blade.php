@extends('layouts.auto_app')

<link href="{{ asset('css/home/stype.css') }}" rel="stylesheet">
<style>
.mdy_layer{width:360px;padding:30px 0 10px 0;font-size:14px;line-height:150%}
.mdy_layer ul{margin:0;padding:0 0 0 60px;text-align:left;list-style:none}
.mdy_layer ul li{margin:0;padding:2px;list-style:none}
.mdy_layer ul li label{font-size:14px}
.mdy_layer ul li input{height:22px}
.mdy_layer ul li a.code{background:#fff8f9;border:1px solid #ffb5bf;color:#ff546a;display:inline-block;height:22px;text-align:center;text-decoration:none;vertical-align:1px;width:88px}
.mdy_layer div{height:30px;text-align:center}
.mdy_layer .tips{text-align:center;color:#F00;font-size:12px}
#mdy_mobile_tips{text-align:left;color:#F00;font-size:12px}
#mdy_location_tips{text-align:left;color:#F00;font-size:12px;width:250px;}
#mdy_idcard_tips{text-align:left;padding:0 30px 0 64px;color:#F00;font-size:12px}
.tips_link,a.tips_link{font-size:12px;color:#00F;text-decoration:none;padding-left:20px;}
.tips_link:hover,a.tips_link:hover{text-decoration:underline}
.tips_link:visited{color:#00F;text-decoration:none}
.tips_pay{font-size:12px;color:#999;text-decoration:underline}
.tips_pay:hover{text-decoration:underline}
.tips_pay:visited{color:#999;text-decoration:none}
#mdy_tips_infos{text-align:center;color:#F00;font-size:14px;padding:5px}
.button{vertical-align:middle;margin-top:-3px}
.base_infomation td.item {color: black;}
</style>
<script type="text/javascript" src="{{ asset('js/home/ad.js') }}">
</script>
<script>
//判断字符串长度
function strlen(str){  
    var len = 0;  
    for (var i=0; i<str.length; i++) {   
     var c = str.charCodeAt(i);   
    //单字节加1   
     if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {   
       len++;   
     }   
     else {   
      len+=2;   
     }   
    }   
    return len;  
}  
function DBC2SBC(str) {
        var i;
        var result='';
        for(i=0; i < str.length; i++)
        {
                code = str.charCodeAt(i);

                if (code == 12290)
                {
                        result += String.fromCharCode(46);
                }
                else if (code == 183)
                {
                        result += String.fromCharCode(64);
                }
                else if(code >= 65281 && code<65373)
                {
                        result += String.fromCharCode(str.charCodeAt(i)-65248);
                }
                else
                {
                        result += str.charAt(i);
                }
        }
        return result;
}
function save_profile(str)
{
    $('#mdy_nickname_tips').hide();
    var get_str = '';
    //如果修改昵称    
    if (str == 'nickname')
    {
        var nickname = DBC2SBC($('#new_nickname').val());
        var nickname_len = strlen(nickname);
        if ('杨测试' == nickname)
        {
            $('#mdy_nickname_tips').show();
            $('#mdy_nickname_tips').html('温馨提示：您还没有修改昵称~');
            return false;
        }
        if (nickname_len < 2 || nickname_len > 20) {
            $('#mdy_nickname_tips').show();
            $('#mdy_nickname_tips').html('温馨提示：昵称最少2个字母或1个汉字，最多10个汉字或20个字母~');
            return false;
        }
        get_str = 'type='+str+'&new_nickname='+encodeURI(nickname);
    }
    else if (str == 'height')//如果修改身高
    {
        var height = $('#new_height').val();
        if (height > 226 || height < 130) {
            $('#mdy_height_tips').show();
            $('#mdy_height_tips').html('温馨提示：您输入的身高不正确~');
            return false;
        }
        if ('170' == height)
        {
            $('#mdy_height_tips').show();
            $('#mdy_height_tips').html('温馨提示：您还没有修改身高~');
            return false;
        }
        get_str = 'type='+str+'&new_height='+height;
    }
    else if (str == 'age')//如果修改年龄
    {
        var new_age = $('#modify_age_want').html();
        if (new_age == '')
        {
            return false;
        }
        get_str = 'type='+str;
    }
    else if (str == 'location') { //修改地区
        var profile_location = $('#new_profile_location').val();
        var profile_sublocation = $('#new_profile_sublocation').val();
        var change_area_reason = $('#change_area_reason').val();
        if (profile_location == '' || profile_location == '0' || profile_sublocation == '' || profile_sublocation == '0' || profile_sublocation.substr(2,2) == '00')
        {
            $('#mdy_location_tips').show();
            $('#mdy_location_tips').html('温馨提示：您还没有修改地区~');
            return false;
        }
        if (change_area_reason == '' || change_area_reason == undefined) {
            $('#mdy_location_tips').show();
            $('#mdy_location_tips').html('温馨提示：您还没有选择修改原因~');
            return false;
        }
        get_str = 'type='+str+'&work_location='+profile_location+'&work_sublocation='+profile_sublocation+'&change_area_reason='+change_area_reason;
    }
    else if(str == 'income')//修改收入
    {
        var new_income = $('#new_income').val();
        if (new_income == '' || '60' == new_income)
        {
            $('#mdy_income_tips').show();
            $('#mdy_income_tips').html('温馨提示：您还没有修改收入~');
            return false;
        }
        if ((new_income - '60') > 10) {
            $('#mdy_income_tips').show();
            $('#mdy_income_tips').html('温馨提示：每次只能向上调一个档次~');
            return false;
        }
        get_str = 'type='+str+'&new_income='+new_income;
    }
    $.ajax({
    type:"get",
    url:"/usercp/modify_profile.php?"+get_str,
    dataType:"json",
    data:{},
    success:function(data)
    {
        if (data.status == 1)
        {
            if (str == 'nickname')
            {
                //$('#show_nickname').html(nickname);
                $('#modify_nickname_tag').hide();
                $('#show_nickname').css('color','');
                send_jy_pv2('|1027534_10|');
            }
            else if (str == 'height')
            {
                $('#height option').attr("selected", false);
                $('#height option[value='+height+']').attr("selected", true);
                $('#modify_height_tag').hide();
                $('#height').attr('disabled','disabled');
                $('#height').css('color','');
                send_jy_pv2('|1027534_12|');
            }
            else if (str == 'age')
            {
                send_jy_pv2('|1027534_11|');
                $('#modify_age_tag').html(new_age);
                $('#modify_age_tag').css('color','');
                setTimeout("jy_head_function.lbg_hide()", 3000);
            }
            else if (str == 'location')
            {
                $('#profile_location option').attr("selected", false);
                $('#profile_sublocation option').attr("selected", false);
                $('#profile_location option[value='+profile_location+']').attr("selected", true);
                $('#profile_sublocation option[value='+profile_sublocation+']').attr("selected", true);
                $('#modify_location_tag').hide();
                $('#profile_location').attr('disabled','disabled');
                $('#profile_sublocation').attr('disabled','disabled');
                $('#profile_location').css('color','');
                $('#profile_sublocation').css('color','');
                send_jy_pv2('|1027534_13|');
            }
            else if (str == 'income')
            {
                $('#income option').attr("selected", false);
                $('#income option[value='+new_income+']').attr("selected", true);
                $('#modify_income_tag').hide();
                $('#income').attr('disabled','disabled');
                $('#income').css('color','');
                send_jy_pv2('|1027534_14|');
            }
            setTimeout("jy_head_function.lbg_hide()", 3000);
        }
        $('#mdy_'+str+'_tips').html(data.msg);
        $('#mdy_'+str+'_tips').show();  
    }
        
    });
}
</script>
@section('left_content')
    <div class="home_left_content">
        kj
    </div>
@endsection

@section('content')
    <div class="my_infomation">
    <div class="navigation"><a href="{{ route('home') }}" onmousedown="send_jy_pv2('editprofile|my_home|m|168103003');">个人中心</a>&nbsp;&gt;&nbsp;基本资料</div>
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
                    <a  class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=economy" onmousedown="send_jy_pv2('editprofile|category_economy|m|168103003');">经济实力</a>
                    <a  class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=life" onmousedown="send_jy_pv2('editprofile|category_life|m|168103003');">生活方式</a>
                    <a  class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=work" onmousedown="send_jy_pv2('editprofile|category_work|m|168103003');">工作学习</a>
                    <a  class="mark2"  href="http://www.jiayuan.com/usercp/profile.php?action=body" onmousedown="send_jy_pv2('editprofile|category_body|m|168103003');">外貌体型</a>    
                    <a  class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=marriage" onmousedown="send_jy_pv2('editprofile|category_marriage|m|168103003');">婚姻观念</a>         
                    <a  class="mark2" href="http://www.jiayuan.com/usercp/profile.php?action=interest" onmousedown="send_jy_pv2('editprofile|category_interest|m|168103003');">兴趣爱好</a>
                </li>
            </ul>
            <div class="return_index">
                <a class="return_jy" href="http://www.jiayuan.com/usercp/index.php" onmousedown="send_jy_pv2('editprofile|return_home|m|168103003');">返回我的佳缘</a>
            </div>
        </div>
        <!-- 左侧结束 -->
        <!-- 中间开始 -->
                <!-- 中间开始 -->
        <div class="info_center">
    <div class="title">
        <strong>基本资料</strong>
    </div>
    <div class="mid_border">
        <div class="base_infomation" id="w_base_infomation">
        <p class="info_note">资料越完善，同等条件我们将优先推荐您哦~</p>
        <form id="form_base" name="form_base" action="profile_postdo_new.php?action=base" method="post" onsubmit="return check_post();">
            <!-- 基本资料 -->
            <div class="base_info">
                <h2>为保证资料真实有效，灰色字体信息不得随意修改，<a href="http://www.jiayuan.com/helpcenter/list.php?type1=1&type2=1&type3=17#art413 " target="_blank">查看修改技巧</a>。<!--，如有需要，请<a href="http://www.jiayuan.com/helpcenter/postmail.php" target="_blank" onmousedown="send_jy_pv2('editprofile|contract_service|f|113987332');">联系客服</a>。--></h2>
                <table colspan = "3" width="450" cellpadding="0" cellspacing="0" class="f-table">
                    <tr>
                        <td class="item"><span style="color:#666;">昵称：</span></td>
                        <!--如果手机没有验证-->
                                                                                <td id="show_nickname" style="color:#666;">杨测试
                            <a href="javascript:;" class="tips_link" id="modify_nickname_tag" onmousedown="send_jy_pv2('|1027534_0|');" onclick="show_modity_profile('nickname');">修改</a>
                                                                            </td>
                        
                    </tr>
                    <tr>
                        <td class="item"><span style="color:#666;">性别：</span></td>
                        <td>男</td>
                    </tr>
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">出生日期：</span></td>
                        
                        
                                                            <td id="modify_age_tag" style="color:#666;">1980-08-11
                                                                                    </td>
                    </tr>
                    <tr>
                        <td class="item"> <span style="color:#666;">生肖：</span></td>
                        <td>猴</td>
                    </tr>
                    <tr>
                        <td class="item"> <span style="color:#666;">星座：</span></td>
                        <td>狮子座</td>
                    </tr>
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">身高：</span></td>
                        <td>
                            
                            <!--如果手机没有验证-->
                                                                                                <select name="height" id="height"  style="color:#666;" class="select1" onChange="select_changed()"  ><option label="130" value="130">130</option>
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
<option label="170" value="170" selected="selected">170</option>
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
</select>&nbsp;厘米
                                    <a href="javascript:;" class="tips_link" id="modify_height_tag" onmousedown="send_jy_pv2('|1027534_4|');" onclick="show_modity_profile('height');">修改</a>
                                                                        
                        </td>
                    </tr>
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">学历：</span></td>
                        <td>本科</td>
                    </tr>
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">婚姻状况：</span></td>
                        <td>
                            未婚
                        </td>
                    </tr>
                    <tr>
                        <td class="item"><a id="l_pos" name="l_pos"></a><span class="ico_stars">*</span><span >有无子女：</span></td>
                        <td>
                            <select name="children" id="children" onChange="select_changed();"  >
                                <option value="0">--请选择--</option>
                                <option label="无小孩" value="1">无小孩</option>
<option label="有小孩归自己" value="2">有小孩归自己</option>
<option label="有小孩归对方" value="3">有小孩归对方</option>

                            </select>
                        </td>
                    </tr>
                    <tr >
                        <td class="item"><span class="ico_stars">*</span><span style="color:#666;">
                        所在地区：</span></td>
                        <td>
                        <!--如果手机没有验证-->
            <select name="work_location"  style="color:#666;"  id="profile_location" 
            class="select1" onchange="build_second(this.value,'profile_sublocation',LOK);
            select_changed();" ></select>&nbsp;&nbsp;
            <select  style="color:#666;"  
            name="work_sublocation" id="profile_sublocation" class="select2" 
            onChange="document.getElementById('change_area_div').style.display='';
            select_changed()" >
            </select>           
            <script type="text/javascript">init_location(11, 1108, 'profile');</script>
          <a href="javascript:;" class="tips_link" id="modify_location_tag" 
          onmousedown="send_jy_pv2('|1027534_6|');" 
          onclick="show_modity_profile('location');">修改</a>
                     <a onmouseover="show_info_div(1, 'info_div')" class="ico0" style="display: none;"></a>
                                                </td>
                    </tr>
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span >户口：</span></td>
                        <td><select onchange="build_second(this.value,'home_sublocation',LOK);select_changed();"  
                            class="select1" id="home_location" name="home_location"></select>&nbsp;&nbsp;
                            <select onchange="select_changed()"  class="select2" id="home_sublocation" 
                            name="home_sublocation"></select></td>
                        <!--<script type="text/javascript">build_select("home_location","home_sublocation",LSK,LOK,"");</script>-->
                        <script type="text/javascript">init_location(0, 0, 'home');</script>
                    </tr>
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span  >血型：</span></td>
                        <td>
                            <select name="bloodtype" id="bloodtype" onChange="select_changed();"  >
                                <option value="0">--请选择--</option>
                                <option label="A型" value="1">A型</option>
<option label="B型" value="2">B型</option>
<option label="O型" value="3">O型</option>
<option label="AB型" value="4">AB型</option>
<option label="其它" value="5">其它</option>
<option label="保密" value="6">保密</option>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span>民族：</span></td>
                        <td>
                            <select name="nation" id="nation"  onChange="select_changed();"><option value="0">--请选择--</option><option label="汉族" value="1">汉族</option>
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
                        </td>
                    </tr>                       
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span  style="color:#666;">月薪：</span></td>
                        <td>
                            
                            <!--如果手机没有验证-->
                                                                                                <select id="income" name="income"  style="color:#666;" onChange="validate('income', this.value)">
                                    <option label="2000元以下" value="10">2000元以下</option>
<option label="2000～5000元" value="20">2000～5000元</option>
<option label="5000～10000元" value="30">5000～10000元</option>
<option label="10000～20000元" value="40">10000～20000元</option>
<option label="20000～50000元" value="50">20000～50000元</option>
<option label="50000元以上" value="60" selected="selected">50000元以上</option>
                                    </select>
                                    <a href="javascript:;" class="tips_link" id="modify_income_tag" onmousedown="send_jy_pv2('|1027534_8|');" onclick="show_modity_profile('income');">修改</a>
                                                                                    </td>
                    </tr>
                                        <tr>
                        <td class="item"><span class="ico_stars">*</span><span >居住情况：</span></td>
                        <td>
                            <select id="house" name="house" onChange="select_changed();" >
                                <option value="0">--请选择--</option>
                                                                    <option label="暂未购房" value="1">暂未购房</option>
<option label="需要时购置" value="8">需要时购置</option>
<option label="已购房（有贷款）" value="9">已购房（有贷款）</option>
<option label="已购房（无贷款）" value="10">已购房（无贷款）</option>
<option label="与人合租" value="3">与人合租</option>
<option label="独自租房" value="4">独自租房</option>
<option label="与父母同住" value="5">与父母同住</option>
<option label="住亲朋家" value="6">住亲朋家</option>
<option label="住单位房" value="7">住单位房</option>

                                                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="item"><span class="ico_stars">*</span><span >购车情况：</span></td>
                        <td>
                            <select id="auto" name="auto" onChange="select_changed();">
                                <option value="0">--请选择--</option>
                                                                    <option label="暂未购车" value="1">暂未购车</option>
<option label="已购车（经济型）" value="3">已购车（经济型）</option>
<option label="已购车（中档型）" value="4">已购车（中档型）</option>
<option label="已购车（豪华型）" value="5">已购车（豪华型）</option>
<option label="单位用车" value="6">单位用车</option>
<option label="需要时购置" value="7">需要时购置</option>

                                                            </select>
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
                        <td class="item" width="70"><span >真实姓名：</span></td>
                        <td width="360">
                                                            <input type="text" name="true_name" class="text" onChange="select_changed()"/>
                                                    </td>
                    </tr>
                    <tr>
                        <td class="item"><span >身份证号：</span></td>
                        <td>
                                                            <input type="text" class="text" id="id_card_id"  name="id_card" value="" onChange="select_changed()" onblur="check_validate('idcard', this.value)" />
                                                    </td>
                    </tr>
                    <tr>
                        <td class="item" style="color:#666;">邮箱：</td>
                        <td><a href="validateemail/certificate.php?menu=5">填写邮箱地址并验证>></a></td>
                    </tr>
                    <tr>
                        <td class="item"><span >QQ：</span></td>
                        <td><input type="text" class="text" id="qq" name="qq"  value="" onChange="select_changed()" onblur="check_validate('qq', this.value)" /></td>
                    </tr>
                    <tr>
                        <td class="item"><span   >MSN：</span></td>
                        <td><input type="text" class="text" id="msn" name="msn"  value="" onChange="select_changed()" onblur="check_validate('msn', this.value)" /></td>
                    </tr>
                    <tr>
                        <td class="item"><span  >通讯地址：</span></td>
                        <td><input type="text" class="text" name="address" value="" onChange="select_changed()"/></td>
                    </tr>
                    <tr>
                        <td class="item"><span  >邮政编码：</span></td>
                        <td><input type="text"  class="text" name="postcode" value="" onChange="select_changed()" /></td>
                    </tr>
                    
                    <tr style='display:none;'>
                      <td colspan="2"><div style="border-top:1px #DDE0E5 solid; margin:10px 0 0 0; padding:10px 0 0 0;"><b style="color:#000; font-size：14px;">分享资料</b><img src="http://images1.jyimg.com/w4/usercp/i/icon_rec.gif" align="absmiddle" /></div></td>
                    </tr>
                    <tr style='display:none;'>
                      <td colspan="2" class="item" style="color:#999898; line-height:1.6; width:439px; ">分享内容包括：昵称、所在城市、年龄、职业、内心独白。<br>佳缘，爱建议：想爱，大声说出来！
                      </td>
                   </tr>
                    <tr style='display:none;'>
                      <td class="item"><span style="color:#333;">您的选择：</span></td>
                      <input type="hidden" name="share" value="1" >
                     <!-- <td class="item" style="width:200px; "><input type="checkbox" name="share" value="1"  style="vertical-align:middle"/> 同意分享</td>-->
                   </tr>
                   
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit" class="save" value="保存并继续" onmousedown="send_jy_pv2('editprofile|save_base|m|168103003');" /><input type="button" value="跳过此页" class="skip" onClick="skip()" onmousedown="send_jy_pv2('editprofile|skip_base|m|168103003');" /></td>
                    </tr>
                </table>
            </div>
            <!-- 联系信息结束 -->
            <!-- 弹层开始 -->
            <div id="info_div" class="info_div" style="display:none;"><!-- 此处火狐的初始top是242像素，每个加28像素，IE的初始TOP是250像素，每个加30像素 -->
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
                    <strong>请选择修改原因：</strong><br/>
                    <label for="change_area_reason1"><input type="radio" name="change_area_reason" id="change_area_reason1" value="1" />工作地区变更</label><br/>
                    <label for="change_area_reason2"><input type="radio" name="change_area_reason" id="change_area_reason2" value="2" />定居地区变更</label><br/>
                    <label for="change_area_reason3"><input type="radio" name="change_area_reason" id="change_area_reason3" value="3" />求学地区变更</label><br/>
                    <label for="change_area_reason4"><input type="radio" name="change_area_reason" id="change_area_reason4" value="4" />误操作导致地区填写错误</label><br/>
                    <p style="color:#333;margin-top:3px;">温馨提示：为保证您的征友严肃性，所在地区仅支持每30天修改一次。</p>
                </dl>
            </div>
            <!-- 弹层结束 -->
        </form>
        </div>
    </div>
</div>
<div class = "popup" id="to_change_match" style="display:none">
    <h3><a href = "javascript:;" onclick="form_submit();" class = "closed">关闭</a>提示</h3>
    <p>您修改了所在地区，是否需要修改择友要求中的所在地区？</p>
    <a href = "http://www.jiayuan.com/usercp/condition.php" target="_blank" onclick="form_submit();" class = "modify"></a>
    <a href = "javascript:;" onclick="form_submit();" class = "later"></a>
    <span class = "baseline1"></span>
    <div class = "baseline2"><span class = "baseline2_inner1"><span class = "baseline2_inner2"></span></span></div>
</div>

<div id="mdy_mobile" style="display:none;">
    <div class="mdy_layer" style="padding-top:10px">
        <ul>
            <li><label>手机号：</label>
            <input type="text" name="" id="mobile-num" style="width:150px" /></li>
            <li><label>验证码：</label>
            <input type="text" name="" id="mobile-code" style="width:51px" />&nbsp;
            <a onclick="send_msg();" href="javascript:;" class="send-code-btn code">获取验证码</a></li>
         <li id="mdy_mobile_tips">温馨提示：完成手机验证，可马上修改一次哦~</li>
            <li style="margin:5px auto;padding:2px 93px"><input type="image" onclick="verify_phone();" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" /></li>
        </ul>
    </div>
</div>


<div id="mdy_idcard" style="display:none;">
    <div class="mdy_layer" style="padding-top:10px">
        <ul style="margin:0;padding:0;text-align:center;list-style:none">
            <li><label>真实姓名：</label>
            <input type="text" name="real_name" id="real_name" style="width:150px" /></li>
            <li><label>身份证号：</label>
            <input type="text" name="real_identity" id="real_identity" style="width:150px" /></li>
            <li>
                <label>验证码：</label>
                <input type="text" name="antispam" id="antispam" style="width: 137px;"><br/>
                <span class="yzm" style="padding-right: 5px;display: inline-block;margin: 5px 0px 0 35px;"><img src="/antispam_v2.php?hash=gd_gen" style="width:75px;height:18px;vertical-align: middle;" alt="" id="antispam_v2"/><script type="text/javascript">function con_code(){var ran= Math.round((Math.random()) * 100000000);document.getElementById("antispam_v2").src = "/antispam_v2.php?r=" + ran;}</script></span><a href="javascript:con_code();">换一张</a> 
            </li>
        <li id="mdy_idcard_tips">温馨提示：身份认证需要花费2佳缘宝，手机验证完成可以获得一次免费验证机会~</li>
            <li style="margin:5px auto" id="mdy_idcard_click"><input type="image" onclick="verify_identity('m',1,'1');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" /></li>
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
<option label="170" value="170" selected="selected">170</option>
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
    <div id="mdy_height_tips" class="tips" style="display:none">
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
            <li style="margin:5px auto;padding:2px 93px"><input type="image" onclick="save_profile('location');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" /></li>
        </ul>
    </div>
</div>

<div id="mdy_income" style="display:none;">
    <div class="mdy_layer">
        <div>
            <label>月薪：</label>
            <select style="width:135px" id="new_income">
            <option label="2000元以下" value="10">2000元以下</option>
<option label="2000～5000元" value="20">2000～5000元</option>
<option label="5000～10000元" value="30">5000～10000元</option>
<option label="10000～20000元" value="40">10000～20000元</option>
<option label="20000～50000元" value="50">20000～50000元</option>
<option label="50000元以上" value="60" selected="selected">50000元以上</option>
            </select>
            <input type="image" onclick="save_profile('income');" class="button" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
        </div>
        <div id="mdy_income_tips" class="tips">
        温馨提示：您的月薪本月只能上调一档哦~
        </div>
    </div>
</div>
<div id="mdy_nickname" style="display:none;">
    <div class="mdy_layer">
        <div>
            <label>昵称：</label>
            <input type="text" name="" id="new_nickname" style="width:135px" />&nbsp;
            <input type="image" class="button" onclick="save_profile('nickname');" src="http://images1.jyimg.com/w4/popup/JY_alert/i/alert_btn.png" />
        </div>
    <div id="mdy_nickname_tips" class="tips">
        温馨提示：昵称修改成功之后，请重新登录~
        </div>
    </div>
</div>

        <!-- 右边结束 -->
</div>
@endsection

@section('right_content')
    <div class="home_right_content">
    </div>
@endsection