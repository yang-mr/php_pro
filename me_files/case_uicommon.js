/**
 * 公共模块 之 发信模块
 *
 * @date	2013-02-17
 *
 **/

//命名空间
$.uicommon	= {};

//控制点击频率
var send_succ_flag 		= 0;					//0:不可发信 1：可发信
var click_first_time 	= true;
var date1 				= new Date();
var prev_time 			= date1.getTime();
var ui_domain			= window.location.host;	//获取当前域名
var global_param		= {is_first:0, is_change:0, change_event:0, current_id:"", clone_html:""};	//全局变量
var timmer   			= '';
var alert_content       = '今日发信已满，明天再玩吧！';
//重新加载loading条
var loading_html = '<div style="width:0; height:0; overflow:hidden; background:transparent;" id="loading_show"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="55" height="55" id="FLashLoading" align="middle"><param name="allowScriptAccess" value="always" /><param name="movie" value="http://images1.jiayuan.com/w4/parties/2012/zt2012/f/as_load.swf" /><param name="flashvars" value="" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><embed src="http://images1.jiayuan.com/w4/parties/2012/zt2012/f/as_load.swf" quality="high" wmode="transparent" bgcolor="#ffffff" width="55" height="55" name="FLashLoading" align="middle" allowScriptAccess="always" FlashVars="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object></div>';

$(function(){
	//$('body').append(loading_html);
});


$.uicommon.showActivity = function(){
	$.uicommon.show_loading('show');
}

$.uicommon.hideActivity = function(){
	$.uicommon.show_loading('hide');
}

$.uicommon.thisMovie = function(mn){
    if($.browser.msie && $.browser.version == '6.0'){
        return window[mn];
    }else{
        return document[mn];
    }
}

$.uicommon.show_loading = function(tp){
    var href    = '#loading_show';
    if(tp == 'show'){
        if($.browser.msie && $.browser.version == '6.0'){
        	$.fancybox.showActivity();
        	return;
        }
        $(href).css({'width':'55px','height':'55px'});
        var _pop_height = document.documentElement.clientHeight;
        var _pop_width  = document.documentElement.clientWidth;
        if($.browser.safari){
            var _scroll_height = parseInt(document.body.scrollTop,10);
        }else{
            var _scroll_height = parseInt(document.documentElement.scrollTop,10);
        }
        var c_height    = (parseInt(_pop_height,10) - parseInt($(href).height(),10))/2;
        c_height = (c_height <=1)?0:c_height;
        c_height += _scroll_height;
        var c_width = (parseInt(_pop_width) - parseInt($(href).width()))/2;
        c_width = (c_width <=1)?0:c_width;
        $(href).css({'position':'absolute','left':c_width,'top':c_height,'z-index':9000});

        $.uicommon.thisMovie('FLashLoading').init_run();

        var in_num  = 30;
        timmer = setInterval(function(){
            $.uicommon.thisMovie('FLashLoading').insert_num(in_num);
            in_num += 40;
            in_num = in_num>=100?100:in_num;
        },800);
    }else if(tp == 'hide'){
    	if($.browser.msie && $.browser.version == '6.0'){
        	$.fancybox.hideActivity();
        	return;
        }
        clearInterval(timmer);
    	$.uicommon.thisMovie('FLashLoading').insert_num(0);
        $(href).css({'width':'0','height':'0'});
    }
}

/**
 * 发信频率控制
 *
 **/
$.uicommon._sleep = function(){
	var date2 		= new Date();
	var curr_time 	= date2.getTime();
	if(click_first_time){
		click_first_time 	= false;
		prev_time 			= curr_time;
		return true;
	}
	if(curr_time - prev_time < 2000 || send_succ_flag == 0){
		return false;
	}
	if(curr_time - prev_time >= 2000 && send_succ_flag == 1){
		send_succ_flag 	= 0;
		prev_time 		= curr_time;
		return true;
	}
}

/**
 * 封装fancybox插件的弹层 - 关闭弹层 - 扩展ie6兼容性问题
 *
 **/
$.uicommon.myself_fancybox_close = function (){
	$.uicommon.syndone();

	if($.browser.msie && $.browser.version == '6.0'){
		$('.my_hidder_layer').parent().hide();
	}else{
		$.fancybox.close();
	}
}

/**
 * 封装fancybox插件的弹层 - 打开弹层 - 扩展ie6兼容性问题
 *
 * @param href:弹层id 	例如：#myid
 **/
$.uicommon.myself_fancybox = function (href){
	if($.browser.msie && $.browser.version == '6.0'){
		//兼容ie6的弹层设置
		$(href).parent().show();
		var _pop_height = document.documentElement.clientHeight;
		var _pop_width  = document.documentElement.clientWidth;
		var _scroll_height = parseInt(document.documentElement.scrollTop,10);
		
		var c_height	= (parseInt(_pop_height,10) - parseInt($(href).height(),10))/2;
		c_height = (c_height <=1)?0:c_height;
		c_height += _scroll_height;
		
		var c_width	= (parseInt(_pop_width) - parseInt($(href).width()))/2;
		c_width = (c_width <=1)?0:c_width;
		
		$(href).addClass('my_hidder_layer').parent().css({'position':'absolute','left':c_width,'top':c_height});
	}else{
		//调用fancybox弹层插件，需要事先引入jquery.fancybox.js和jquery.fancybox.css
		$.fancybox({
				'margin'			: 0,
				'padding'			: 0,
				'overlayColor'		: '#000',
				'showCloseButton'	: false,
				'href'				: href,
				'scrolling'			:'no',
				'centerOnScroll'	: true,
				'hideOnOverlayClick' : false
			});
	}
}

/**
 * 关闭弹层时的其他处理
 *
 **/
$.uicommon.syndone	= function(){
	if(global_param.is_change == 1){
		global_param.is_change	= 0;
	}
}

/*
 * 获取用户发信弹层
 *
 * @param param:传递参数
 * param:{
 			gtype 		    : 1,								//标记是否发送完信件后，需要再重新获取一批新用户
 			fxly		    : 'zt2012_dafuweng',				//发信来源标记
			man_pvid	    : '|42013154|',						//男用户发信数量统计标记
			woman_pvid	    : '|106164618|',					//女用户发信数量统计标记
			subject		    : '爱情连连看',						//信件标题
			callback	    : 'funcname' 或 function(){...},	//回调函数
            getuser_callback:'funcname' 或 function(){...},	    //获取用户回调函数，暂时用来更改展示曾比较特殊的
			showlayerid	    : 'layerid',						//弹层的模板id
			content 	    : 'xxxx',							//自定义发信内容(如果内容为空，则调用个人发信模板)
			getnum		    : 10 								//获取用户数量
			atype		    : 'b' 			//获取头像的图片大小'b'-110*135  'n'-74*90  's'-65*80 默认为：'b'-110*135
			li_restyle      :'class_name' ,                      //控制每个li的样式
			send_uid        :79648447                           //发信用户uid
			change_pvid     :'|106164618|',                     //换一组按钮点击
            send_pvid       :'|106164618|',                     //发信按钮点击
            party_type      : 1,                                //标识是否专题游戏
 		}
 **/
$.uicommon.get_user = function(param){
    if(!$.uicommon._sleep() && send_succ_flag == 0){
        return false;
    }else if(param.length == 0){
    	alert("缺少必要参数!");
    	return false;
    }
	send_succ_flag	= 0;	//锁定
	var _init_html	= $('#'+param.showlayerid).html();//放置弹层内容

	var is_getnewlayout = false;	//是否获取新弹层 - 避免一个页面中有多个弹层之间交叉显示的时候出现问题
	if(global_param.current_id != ''){
		if(global_param.current_id == param.showlayerid){
			is_getnewlayout = false;
		}else{
			is_getnewlayout = true;
			global_param.current_id = param.showlayerid;
		}
	}else{
		global_param.current_id = param.showlayerid;
	}
	if(_init_html == ''){
		send_succ_flag = 1;	//开启
		alert('弹层参数内容错误！');
		return;
	}else{
	    if($.browser.msie && $.browser.version == '6.0')
		{
			var _init_html2 = '<div style="display:none;"><span class="my_hidder_layer" id="hidder_layer_1">' + _init_html + '</span></div>';
			if(!$('#hidder_layer_1').is('span')){
				$('body').append(_init_html2);
				DD_belatedPNG.fix('.pngfix');
			}else{
				//已经存在该弹层
				if(is_getnewlayout){
					$('#hidder_layer_1').html(_init_html);
					$('#hidder_layer_1').contents().find('li').css('display','none');
				}
			}
		}
		else
		{
			var _init_html2 = '<div style="display:none;"><div class="my_hidder_layer" id="hidder_layer_1">' + _init_html + '</div></div>';
			if(!$('#hidder_layer_1').is('div')){
				$('body').append(_init_html2);
			}else{
				//已经存在该弹层
				if(is_getnewlayout){
					$('#hidder_layer_1').html(_init_html);
					$('#hidder_layer_1').contents().find('li').css('display','none');
				}
			}
		}
	}
	if(param.gtype == 0 || (param.gtype == 1 && global_param.change_event == 1) || global_param.is_first == 0){
		$.uicommon.showActivity();
	}
	var req_url		= '/common/ajax_get_recommend_user.php';	
	if(ui_domain == 'jiayuan.msn.com.cn'){
		req_url		= '/case/web/common/ajax_get_recommend_user.php';
	}
	var getnum = param.getnum?param.getnum:10;
	var atype = param.atype?param.atype:'n';
    $.getJSON(req_url, {'num':getnum,'atype':atype,'fxly':param.fxly}, function(r){
		if(r){
			if(param.gtype == 0 || (param.gtype == 1 && global_param.change_event == 1) || global_param.is_first == 0){
				global_param.is_first = 1;
				$.uicommon.hideActivity();
			}
			global_param.change_event = 0;
			send_succ_flag	= 1;	//开启
			var all_uids	= '';
			var all_indexs	= '';
			var _html 		= $('#hidder_layer_1').contents().find('ul').html();
			if(global_param.clone_html == '' || is_getnewlayout){
				global_param.clone_html	= _html;
			}else{
				_html	= global_param.clone_html;
			}
			$('#hidder_layer_1').contents().find('ul').empty();
            var li_index_i = 0;
			for(var a in r){
                li_index_i++;
				var temp_html	= _html;
                temp_html = decodeURI(temp_html);
				temp_html = temp_html.replace(/\|\|uid\|\|/g, r[a].uid);
                temp_html = temp_html.replace(/\|\|fxly\|\|/g, (r[a].fxly==""||r[a].fxly==null) ? param.fxly : r[a].fxly);
				temp_html = temp_html.replace(/http:\/\/images\.jiayuan\.com\/w4\/case\/common\/i\/a\.gif/g, r[a].src);
				temp_html = temp_html.replace(/\|\|name\|\|/g, r[a].name);
				temp_html = temp_html.replace(/\|\|age\|\|/g, r[a].age);
				temp_html = temp_html.replace(/\|\|info\|\|/g, r[a].info);
				temp_html = temp_html.replace(/\|\|zw\|\|/g, r[a].zw);
                if(param.li_restyle){
                    if(li_index_i<10){
                        temp_html = temp_html.replace(/\|\|li_restyle\|\|/g,param.li_restyle+'0'+li_index_i);
                    }else{
                        temp_html = temp_html.replace(/\|\|li_restyle\|\|/g,param.li_restyle+li_index_i);
                    }
                }
				$('#hidder_layer_1').contents().find('ul').append(temp_html);
				all_uids += r[a].uid + '|';
				all_indexs += r[a].zw + '|';
			}
			$('#hidder_layer_1').contents().find('li').css('display','');
			//传递展位Index
			param.msg_indexs	= all_indexs;
			//发信用户uid
			param.msg_uids	= all_uids;
			//如果是带有复选框的弹层形式，需要重新获取可发信用户的uid
			if($('.input_ckbox_user').is('input')){
				$('.input_ckbox_user').click(function(n){
					var ck_uids    = '';
					var ck_indexs = '';
				    $('input[name="msguid"]:checked').each(function(i,n){
				    	if($(n).val() != '||uid||'){
							ck_uids    += $(n).val() + '|';
							ck_indexs  += $("[name = 'index_"+$(n).val()+"']").val() + '|';
				    	}
				    });
				    param.msg_uids	= ck_uids;
					param.msg_indexs	= ck_indexs;
				});
			}
			//换一组按钮点击
			$('.ui_change').die('click').live('click', function(){
				global_param.is_change = 1;	//标记是新获取一组发信用户
				global_param.change_event = 1;
				$.uicommon.get_user(param);
                if(param.change_pvid != undefined && param.change_pvid != ''){
                    send_jy_pv2(param.change_pvid);
                }
                return false;
			});
			//发信按钮点击
			$('.ui_hello').die('click').live('click', function(){
				global_param.is_change = 1;//点击发信后，如果是循环获取，需要设置is_change参数
				$.uicommon.send_all_msg(param);
                if(param.send_pvid != undefined && param.send_pvid != ''){
                    send_jy_pv2(param.send_pvid);
                }
                return false;
			});

            //获取用户回调
            if(typeof param.getuser_callback == 'function'){
                param.getuser_callback();
            }else if(typeof param.getuser_callback == 'string'){
                eval(param.getuser_callback+'()');
            }
			//显示弹层
			if(global_param.is_change == 0){
				$.uicommon.myself_fancybox('#hidder_layer_1');
			}
		}else{
			// send_succ_flag = 1;
			// $.fancybox.hideActivity();
		}
	});
}


/**
 * 多用户发信
 * 
 * @params {
		gtype		: 1,								//标记是否获取新用户 1:获取 0 不获取
		fxly		: 'zt2013_ppl',						//发信来源
		man_pvid	: '|123456|',						//男用户发信数量统计标识
		woman_pvid	: '|3459348|',						//女用户发信数量统计标识
		subject		: '泡泡龙',							//信件标题
		callback	: 'funcname' 或 function(){...},	//回调函数
		msg_uids	: '1231231|43534534|34534534|',		//收信人用户uid
		content 	: 'xxxxx'							//自定义发信内容(如果为空，调用个人发信模板)
		send_uid	: 1231231 							//发信人uid
		alert_content   : '今日发信已满，明天再玩吧！'
 	}
 **/
$.uicommon.send_all_msg = function(params){
    if(!$.uicommon._sleep() && send_succ_flag == 0){
        return false;
    }
    send_succ_flag = 0;	//标记为不可点击
    $.uicommon.showActivity();//显示加载中的提示
    var post_uid    = params.msg_uids;
	var post_indexs = params.msg_indexs;
    if(post_uid != ''){
        //全部打招呼点击
        var req_url		= '/common/ajax_send_msg.php';	
		if(ui_domain == 'jiayuan.msn.com.cn'){
			req_url		= '/case/web/common/ajax_send_msg.php';
		}
		var send_content = params.content?params.content:'';
        $.getJSON(req_url, {'post_uid':post_uid, 'post_index':post_indexs, 'fxly':params.fxly,'subj':params.subject, 'content':send_content}, function(res){
            if(res){
				send_succ_flag = 1;	//只要请求返回结果，就可以放开按钮了
				$.uicommon.hideActivity();
				if(res['retcode'] <= 0){
                    //交友状态有误
                    alert(res['content']);
                    return false;
                }else{
                	var succ_num	= 0;
					//统计男女发信数
					for(var k in res){
						if(res[k]['retcode'] == 1){
							if(res[0]['sex'] == 'm' && params.man_pvid != undefined && params.man_pvid != ''){
							   send_jy_pv2(params.man_pvid+params.send_uid+'|');
						    }else if(res[0]['sex'] == 'f' && params.woman_pvid != undefined && params.woman_pvid != ''){
							   send_jy_pv2(params.woman_pvid+params.send_uid+'|');
						    }
							succ_num += 1;
						}
						if(res[k]['retcode'] == '-127'){
							if(params.party_type != undefined && params.party_type != '')
							{
								alert(alert_content);
							}
							else
							{
								alert("今日发信人数已经超过限额，不能进行该操作！");
							}
							return false;
						}
					}
					params.msg_uids	= '';//清空用户id列表
            		//回调
            		if($.browser.msie && $.browser.version == '6.0')
            		{
            			if(params.gtype == 1){
	            			//循环获取
	            			$.uicommon.get_user(params);	//重新获取一批新发信用户
	            		}else{
	            			//不再循环获取，关闭弹层
	            			$.uicommon.myself_fancybox_close();
	            		}
            		}
            		if(typeof params.callback == 'function'){
            			params.callback(succ_num);
            		}else if(typeof params.callback == 'string'){
            			eval(params.callback+'('+succ_num+')');
            		}
            		if(!($.browser.msie && $.browser.version == '6.0'))
            		{
	                    if(params.gtype == 1){
	            			//循环获取
	            			$.uicommon.get_user(params);	//重新获取一批新发信用户
	            		}else{
	            			//不再循环获取，关闭弹层
	            			$.uicommon.myself_fancybox_close();
	            		}
            		}
				}
            }
        });
    }else{
    	send_succ_flag	= 1;
    	$.uicommon.hideActivity();
    	if($('.input_ckbox_user').is('input')){
    		alert('您还没有选择要打招呼的异性哦！');
    		return false;
    	}
    }
}


/**
 * 单人发信
 * @param {
		uid 		: 123123,							//收信人uid
		fxly		: 'zt2012_zmc',						//发信来源
		man_pvid	: '|12312312|',						//男用户发信数量统计标记
		woman_pvid	: '|34534534|',						//女用户发信数量统计标记
		subject 	: '最美春',							//信件标题
		callback	: 'funcname' 或 function(){...},	//回调函数
		content 	: 'xxxxx'							//发信内容
		send_uid	: 123456							//发信人uid
 	}
 * @return 
 **/
$.uicommon.send_msg 	= function(params){
	if(params.uid == '' || params.uid == 0 || params.uid == undefined || params.length == 0){
		alert("缺少必要参数！");
		return false;
	}
	if(!$.uicommon._sleep() && send_succ_flag == 0){
		return false;
	}
	$.uicommon.showActivity();
	send_succ_flag	= 0;
    var req_url		= '/common/ajax_send_msg.php';	
	if(ui_domain == 'jiayuan.msn.com.cn'){
		req_url		= '/case/web/common/ajax_send_msg.php';
	}
	var send_content = params.content?params.content:'';
	$.post(req_url, {'post_uid':params.uid,'fxly':params.fxly,'subj':params.subject,'content':send_content}, function(r){
		if(r){
			r = eval('('+r+')');
			var is_succ		= 0;	//是否发送成功
			send_succ_flag	= 1;
			$.uicommon.hideActivity();
			if(r[0]['retcode'] == 1){
				//发送成功
				is_succ = 1;
				if(r[0]['sex'] == 'm' && params.man_pvid != undefined){
					send_jy_pv2(params.man_pvid+params.send_uid+'|');
				}else if(r[0]['sex'] == 'f' && params.man_pvid != undefined){
					send_jy_pv2(params.woman_pvid+params.send_uid+'|');
				}
			}else{
				if(r[0]['content'] != null && r[0]['content'] != '' && r[0]['content'] != undefined){
					alert(r[0]['content']);
				}
				return false;
			}
			//回调
    		if(typeof params.callback == 'function'){
    			params.callback(is_succ);
    		}else if(typeof params.callback == 'string'){
    			eval(params.callback+'('+is_succ+')');
    		}
			return true;
		}
	});
}
