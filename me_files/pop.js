//TODO 本文件中不可再用document.write的方式写入一个script标签了，不然IE下面会出现滚动条可以无限下拖的现象
if(typeof window.jQuery == 'undefined'){
    var fileref = document.createElement('script');//创建标签
    fileref.setAttribute("type", "text/javascript");//定义属性type的值为text/javascript
    fileref.setAttribute("src", "http://images1.jyimg.com/w4/common/j/jquery-1.7.2.min.js"); //文件的地址
    if(typeof fileref != "undefined"){
        document.getElementsByTagName("head")[0].appendChild(fileref);
    }
}
//取PV连接
(function() {
var V_LINK = "";
window.getPvlink = function(){
	if(V_LINK==""){
		var myloc=readCookie('myloc');
		var mysex=readCookie('mysex');
		var myage=readCookie('myage');
		var myuid=readCookie('myuid');
		V_LINK="http://pv.jiayuan.com/call/v.gif?w=1&location="+ myloc+"&sex="+mysex+"&age="+myage+"&uid="+myuid;
	}
	return V_LINK+"&rd="+Math.random();
}
//TODO 这sl函数考虑使用Image()实现，不用run_fra这个iframe了, 但是run_fra在后台发布的弹出样式中还有用到
window.sl_pv = function (obj){
	var elements = obj.getElementsByTagName('a');
	for (var i = 0, len = elements.length; i < len; ++i) {
		elements[i].onmousedown = function(){
			var pvurl = getPvlink();
			document.getElementById('run_fra').src=pvurl+'&sl=zd&click='+this.href+'&';
		};
	}
}
})();
//弹窗类
(function() {
	var pop_height = 203; // 最大化时的高度
	var pop_mini_h = 35; // mini状态时的高度
	var min_class = "sp1";
	var max_class = "sp3";
	var on_class = "on";
	var over_class = "over";
	var move_int_time = 50; // 移动间隔时间
	var move_int_height = 10; // 移动间隔高度
	var pop = {};
	pop.extend = function(des, src) { //{{{
		if(src instanceof Array) {
			for(var i = 0, len = src.length; i < len; i++)
				pop.extend(des, src[i]);
		}
		for( var i in src) {
			des[i] = src[i];
		}
		return des;
	};
	//最大化，最小化，关闭, 浮动
	pop.extend(pop, {//{{{
		time_hdl_minmax: 0,
		// status: 0 无变化, 1, 变大 2, 变小 3, 关闭
		status: 0,
		minmax: function() {
			pop.status = pop.status == 1 ? 2 : 1;
			if(pop.status == 1) {
				pop.sBtn.title = "最小化";
				pop.sBtn.className = min_class;
			} else {
				pop.sBtn.title = "最大化";
				pop.sBtn.className = max_class;
			}
			pop.doChange();
		},
		max: function() {
			pop.status = 1;
			pop.sBtn.title = "最小化";
			pop.sBtn.className = min_class;
			pop.doChange();
		},
		close: function() {
			pop.status = 3;
			pop.doChange();
		},
		doChange: function() {
			clearInterval(pop.time_hdl_minmax);
			pop.time_hdl_minmax = setInterval(function(){pop.changeH(pop.status)}, move_int_time);
		},
		changeH: function(status) {
			var popH = parseInt(pop.bulletin_div.style.height);
			switch(status) {
				case 1:
					if (popH<pop_height){
						pop.bulletin_div.style.height=(Math.min(pop_height,popH+move_int_height)).toString()+"px";
					}
					else{  
						clearInterval(pop.time_hdl_minmax);
					}
					pop.scrollTip();
					break;
				case 2:
				case 3:
					if (popH>pop_mini_h) {
						pop.bulletin_div.style.height=(Math.max(pop_mini_h,popH-move_int_height)).toString()+"px";
					} else { 
						clearInterval(pop.time_hdl_minmax);
						if(status == 3)
							pop.bulletin_div.style.height="0px";  //不设置隐藏,只改变高度
					}
					pop.scrollTip();
					break;
				default:
					clearInterval(pop.time_hdl_minmax);
					break;
			}
		},
		//自适应位置
		scrollTip: function() { 
			var w=0,h=0,x=0,y=0;
			if(document.documentElement && document.documentElement.clientWidth){ 
				w=document.documentElement.clientWidth;
				h=document.documentElement.clientHeight;
			} else if(document.body && document.body.clientWidth){ 
				w=document.body.clientWidth;
				h=document.body.clientHeight;
			} else if(window.innerWidth){ 
				w=window.innerWidth-18;
				h=window.innerHeight-18; 
			} 
			if(document.documentElement&&document.documentElement.scrollTop){ 
				y=document.documentElement.scrollTop;
			} else if(document.body&&document.body.scrollTop){ 
				y=document.body.scrollTop;
			} else if(window.pageYOffset){ 
				y=window.pageYOffset;
			} else if(window.scrollY){ 
				y=window.scrollY;
			}
			if(document.documentElement&&document.documentElement.scrollLeft){ 
				x=document.documentElement.scrollLeft;
			} else if(document.body&&document.body.scrollLeft){ 
				x=document.body.scrollLeft;
			} else if(window.pageXOffset){ 
				x=window.pageXOffset;
			} else if(window.scrollX){ 
				x=window.scrollX;
			}
			pop.bulletin_div.style.top = h + y - pop.bulletin_div.offsetHeight + "px"; 
			pop.bulletin_div.style.left = w + x - pop.bulletin_div.offsetWidth + "px"; 
		}
	});
	//切换与设置标签
	pop.extend(pop, {
		showtab: function(show_id, max_it) {
			for(var i=1; i<=3; i++) {
				var div_obj = document.getElementById("div_"+i);
				var con_obj = document.getElementById("con_"+i);
				if(!div_obj || !con_obj) continue;
				if(i ==	show_id) {
					div_obj.className = on_class;
					con_obj.style.display = '';
				} else {
					div_obj.className = over_class;
					con_obj.style.display = 'none';
				}
			}
			if(max_it) {
				pop.max();
			}
			pop.stop_flash(show_id);
		},
		settab: function(set_id, content) {
			var con_obj =   document.getElementById("con_" + set_id);
			if(con_obj) {
				con_obj.innerHTML = content;
				sl_pv(con_obj);
			}
		}
	});
	//初始化
	pop.extend(pop, {
		bulletin_div: null,
		sBtn: null,
		init: function() {
			pop.bulletin_div = document.createElement('DIV');
			pop.bulletin_div.style.display = '';
			pop.bulletin_div.style.height = "0px";
			pop.bulletin_div.style.right = "0px";
			pop.bulletin_div.style.bottom = "0px";
			var loc_res = location.href.substr(Math.max(0, location.href.length - 7), 7);//取URL后7位
			if(loc_res == 'usercp/' || loc_res == '/usercp'){
				//这时候就算不弹也显示个mini窗口
				pop.bulletin_div.style.height = pop_mini_h + "px";
				pop.scrollTip();
			}
			pop.sBtn = document.getElementById('switchButton');
			if(pop.sBtn){
				pop.sBtn.onclick = function() {
					pop.minmax();
				}
			}
			var btn	= document.getElementById('closeButton');
			if(btn){
				btn.onclick = function() {
					pop.close();
				}
			}
			pop.bulletin_div.onclick = function() {
				pop.stop_flash();
			}
			for(var i = 1; i <= 3; i++){
				(function(i) {
					var div_obj	= document.getElementById("div_"+i);
					if(!div_obj) return;
					div_obj.onclick = function() {pop.showtab(i, 1);}
				})(i);
			}
			addEvent("onscroll", function(){pop.scrollTip();});
			addEvent("onresize", function(){pop.scrollTip();});
		}
	});
	function addEvent(event, func) {
		var oldevent = window[event];
		if(typeof window[event] != 'function') {
			window[event] = func;
		} else {
			window[event] = function() {
				oldevent();
				func();
			}
		}
	}
	pop.init();
	window.pop = pop;
	timepop(true);//调用弹出
	//setTimeout('timepop(true)',3000);
})();
//弹出活动等公告信息
function insert_con()
{
	return;//在jyim2.js中重写
}
// 弹出一段消息内容
function pop_content(content)
{	
	setTimeout(function(){ pop_content(content) },2000);
	return false;//在jyim2.js中重写
}
//声明xmlhttp
function getXMLHttpRequest()
{
	var xmlhttp;
	try{//for ie
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP.4.0");
	}catch(E){
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(E){
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(E){
				xmlhttp = false;
			}
		}
	}
	if(!xmlhttp && typeof XMLHttpRequest != 'undefined'){
		xmlhttp = new XMLHttpRequest();//for firefox
	}
	return xmlhttp;
}
//定时及首次弹出内容
function timepop(first_time)
{
	if(typeof(HEAD_USER) != 'undefined' && HEAD_USER.uid > 0){
		var now = new Date().getTime();//当前时间
		var pop_time = readCookie('pop_time');//最近一次弹出时间
		if(first_time || (now - pop_time) > (1000 * 60 * 6)){
			if(!first_time){
				writeCookie('pop_time', now, 10);
			}
			var myxml = getXMLHttpRequest();
			var url = "/pop/pop.php?COMMON_HASH=" + readCookie('COMMON_HASH') + "&RAW_HASH="+readCookie('RAW_HASH') + "&a=" + Math.random();
			var is_self_kpd = 0;
			if(document.location.href.indexOf("#cp_kpd") > 0){
				is_self_kpd = 1;
			}
			if(first_time && !is_self_kpd){
				url += "&ft=1";
			}
			url += "&v=3";
			myxml.open("GET", url, true);
			myxml.send(null);
			myxml.onreadystatechange = function(){
				if(myxml.readyState == 4){
					var myxml_text = myxml.responseText;
					if(myxml_text == ''){
						jQuery.getScript('http://ads.jiayuan.com/ad.php?pd_id=11', function(data){
							if(data){
								pop.bulletin_div.style.height = "0px";
								pop_content(data);
							}
						});	
					}
					if(myxml_text != 'none' && myxml_text != ''){
						pop.bulletin_div.style.height = "0px";
						pop_content(myxml_text);
						//个人资料页添加右下弹出统计
						if(typeof(profile_pop_tj_xj) != "undefined"){
							if(profile_pop_tj_xj == 1){
								send_jy_pv2('|profile_new_pop_pv|');
							}else if(profile_pop_tj_xj == 2){
								send_jy_pv2('|profile_old_pop_pv|');
							}
						}
					}
				}
			}
		}
	}
	setTimeout('timepop(false)', 30000);
}