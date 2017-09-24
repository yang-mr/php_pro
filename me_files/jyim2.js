var escape_cookie = (ua=navigator.userAgent.toUpperCase()) && (ua.indexOf('FIREFOX') != -1 || ua.indexOf('OPERA') != -1 || ua.indexOf('SAFARI') != -1);
var json_util =
{
	escapable: /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
	meta: {
		'\b': '\\b',
		'\t': '\\t',
		'\n': '\\n',
		'\f': '\\f',
		'\r': '\\r',
		'"' : '\\"',
		'\\': '\\\\'
	},
	
	quote: function(string) 
	{
		this.escapable.lastIndex = 0;
		var me = this;
		return this.escapable.test(string) ?
			'"' + string.replace(this.escapable, function (a) {
				var c = me.meta[a];
				return typeof c === 'string' ? c : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
			}) + '"' :
			'"' + string + '"';
	},

	encode: function(arr) {
		if(arr == null) return 'null';
		var parts = [];
		var is_list = (Object.prototype.toString.apply(arr) === '[object Array]');

		for(var key in arr) {
			if(!arr.hasOwnProperty(key)) continue;
			var v = arr[key];
			
			var str = "";
			if(!is_list) str = '"' + key + '":';
			if(typeof v == "number") str += v;
			else if(typeof v == "object") str += this.encode(v); 
			else if(typeof v == "string") str += this.quote(v);
			else if(v === false) str += 'false';
			else if(v === true) str += 'true';
			else continue;
			
			parts.push(str);
		}
		var json = parts.join(",");

		if(is_list) return '[' + json + ']';
		return '{' + json + '}';
	},

	decode: function(str)
	{
		if(/^[\],:{}\s]*$/.test(str.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, '')))
		{
			return eval('('+str+')');
		}
		return null;
	}
}

function CometClient(shost, nport, sdata, cb, bridge_dir)
{
	if(typeof(sdata) == 'object' && sdata.constructor == Object)
	{
		var s = [];
		for(var n in sdata)
		{
			s.push(encodeURIComponent(n));
			s.push('=');
			s.push(encodeURIComponent(sdata[n]));
			s.push('&');
		}
		sdata = s.join('');
	}
	
	var script = null;
	var check_live = function(cb)
		{
			var called = false;
			CometClient.live = function(is_live)
				{
					if(!called)
					{
						if(script)
						{
							header.removeChild(script);
							script = null;
						}
						if(loaded)
						{
							header.removeChild(loaded);
							loaded = null;
						}
						
						called = true;
						
						cb(is_live);
					}
				};
			
			script = document.createElement("script");
			script.src = "http://"+shost+(nport==80?'':':'+nport)+"/check-live?"+Math.floor(Math.random()*1000000);
			script.type = "text/javascript";
			script.onload = script.onreadystatechange = function()
				{
					if(!this.readyState || this.readyState === "loaded" || this.readyState === "complete")
					{
						CometClient.live(false);
					}
				}
			
			script.onerror = function()
				{
					CometClient.live(false);
				}
			
			var header = document.getElementsByTagName('HEAD')[0];
			
			script = header.insertBefore(script, header.firstChild);
			
			if(window.opera)
			{
				var loaded = document.createElement("script");
				loaded.type = "text/javascript";
				loaded.text = "CometClient.live(false);";
				loaded = header.insertBefore(loaded, script.nextSibling);
			}
		};
	
	var error_count = 0;
	var tmr = 0;
	var clear_timeout = function()
	{
		if(tmr)
		{
			window.clearTimeout(tmr);
			tmr = 0;
		}
	};

	var check_server = function()
		{
			clear_timeout();
			
			tmr = window.setTimeout(function()
			{
				tmr = 0;
				check_live(function(is_live)
				{
					tmr = 0;
					if(!is_live)
					{
						if(cb.onerror)cb.onerror();
					}
					else
					{
						++error_count;
						if(error_count < 3 && ifr)
						{
							tmr = window.setTimeout(function()
								{
									tmr = 0;
									if(ifr)
									{
										ifr.src = ifr.src+Math.floor(Math.random()*1000);
										check_server();
									}
								}, 5000);
						}
						else
						{
							if(cb.onerror)cb.onerror();
						}
					}
				});
			}, 10000);
		};
	
	var ua = navigator.userAgent.toUpperCase();
	try
	{
		if('WebSocket' in window || 'MozWebSocket' in window)
		{
			var opened = false;
			var ws_url = 'ws://'+shost+(nport==80?'':':'+nport)+'/ws'+(sdata?'?'+sdata:'');
			var ws = ('WebSocket' in window ? new WebSocket(ws_url) : new MozWebSocket(ws_url));
			ws.onmessage = function (msg)
			{
				var o = eval('('+msg.data+')');
				if(cb.onmessage)
					cb.onmessage(o.data, o.cmd, msg.data);
			};

			ws.onopen=function() { opened = true; if(cb.onconnect)cb.onconnect(); }
			ws.onclose=function()
				{
					if(ws && cb.onerror)
					{
						cb.onerror();
					}
				}
			
			return function()
				{
					if(ws)
					{
						clear_timeout();
						
						ws.close();
						ws = null;
					}
				};
			
		}
		else if(ua.indexOf('FIREFOX') != -1 && window.postMessage)
		{
			var org = 'http://' + shost + (nport==80?'':':'+nport);
			window.addEventListener("message",
				function(evt)
				{
					if(evt.origin !== org)
						return;
					
					if(evt.data == '')
					{
						++error_count;
						if(error_count < 3 && ifr)
						{
							clear_timeout();
							tmr = window.setTimeout(function()
								{
									tmr = 0;
									if(ifr)
									{
										ifr.src = ifr.src+Math.floor(Math.random()*1000);
										check_server();
									}
								}, 5000);
						}
						return;
					}
					
					var dat = evt.data.replace(/==/g, '=');
					var o = eval('('+dat+')');
					if(o.cmd == 1)
					{
						if(o.data==2)
						{
							if(cb.onconnect)cb.onconnect();
						}
						else if(ifr && o.data==3)
						{
							if(cb.onerror)cb.onerror();
						}
					}
					else if(cb.onmessage)
					{
						cb.onmessage(o.data, o.cmd, dat);
					}
				},
				false);
			
			var ifr = document.createElement('iframe');
			ifr.src = org+'/ff-bridge'+(sdata?'?'+sdata:'')+'&r='+Math.floor(Math.random()*1000000);;
			ifr.style.display = 'none';
			ifr.onload=function()
			{
				clear_timeout();
			}
			
			ifr = document.documentElement.appendChild(ifr);
			check_server();
			var close_ptr = function()
				{
					clear_timeout();
					if(ifr)
					{
						document.documentElement.removeChild(ifr);
						ifr = null;
					}
				};
			window.addEventListener("unload", close_ptr, false);
			return close_ptr;
		}		
	} catch(e) {}

	var connTm = Number.MAX_VALUE;
	if(window.opera && ('postMessage' in window))
	{
		var f = function(evt)
		{
			if(!evt) evt = window.event;
			
			var dat = evt.data;
			var ms= json_util.decode(dat);
			if(!('cmd' in ms)) return;
			clear_timeout();
			
			if(ms.cmd == 1)
			{
				if(ms.data == 2)
				{
					connTm = (new Date()).getTime();
					if(cb.onconnect) cb.onconnect();
				}
				else if(ms.data == 3)
				{
					var now = (new Date()).getTime();
					if(now - connTm > 60000)
					{
						error_count = 0;
						connTm = now;
					}
					
					if(error_count < 3 && ifr)
					{
						error_count++;
						tmr = window.setTimeout(function(){
							tmr = 0;
							if(ifr)
							{
								ifr.src = ifr.src + Math.floor(Math.random()*1000);
								check_server();
							}
						}, 3000);
					}
					else if(ifr && cb.onerror)
					{
						cb.onerror();
					}
				}
				else if(ms.data == 4 && ifr)
				{
					tmr = window.setTimeout(function()
						{
							tmr = 0;
							if(error_count < 3 && ifr)
							{
								error_count++;
								ifr.src = ifr.src+Math.floor(Math.random()*1000);
								check_server();
							}
							else if(ifr && cb.onerror)
							{
								cb.onerror();
							}
						}, 6000);
				}
			}
			else if(cb.onmessage)
			{
				cb.onmessage(ms.data, ms.cmd, dat);
			}
		};

		var doc = document;
		var win = window;
		
		win.addEventListener("message", f, false);
		
		var ifr = doc.createElement('iframe');
		ifr.style.display = 'none';
		ifr.src = 'http://'+shost+(nport==80?'':':'+nport)+'/ifr?'+(sdata?sdata:'')+'&r='+Math.floor(Math.random()*1000000);
		
		ifr = doc.documentElement.appendChild(ifr);
		check_server();
	}
	else
	{
		if(bridge_dir == undefined)
		{
			var path = window.location.pathname;
			var i = path.lastIndexOf('/');
			path = path.substr(0, i+1);
			
			bridge_dir = 'http://'+window.location.host + path + 'helper/';
		}
		bridge_dir += 'bridge.php';
		
		var doc;
		var win;
		var is_ie = (ua.indexOf('MSIE') != -1);
		if(is_ie)
		{
			doc = new ActiveXObject("htmlfile");
			doc.open();
			doc.write("<html>");
			doc.write("<script>document.domain = '"+document.domain+"'");
			doc.write("</html>");
			doc.close();
			win = doc.parentWindow;
		}
		else
		{
			doc = document;
			win = window;
		}
		
		var $ = function(dat)
			{
				window.setTimeout(function(){
					clear_timeout();
					var ms= json_util.decode(dat);
					for(var i=0; i<ms.length; i++)
					{
						if(ms[i].cmd == 1)
						{
							if(ms[i].data == 2)
							{
								connTm = (new Date()).getTime();
								if(cb.onconnect) cb.onconnect();
							}
							else if(ms[i].data == 3)
							{
								var now = (new Date()).getTime();
								if(now - connTm > 60000)
								{
									error_count = 0;
									connTm = now;
								}
								
								if(error_count < 3 && ifr)
								{
									error_count++;
									tmr = window.setTimeout(function(){
										tmr = 0;
										if(ifr)
										{
											ifr.src = ifr.src+Math.floor(Math.random()*1000);
											check_server();
										}
									}, 3000);
								}
								else if(ifr && cb.onerror)
								{
									cb.onerror();
								}
							}
							else if(ms[i].data == 4 && ifr)
							{
								tmr = window.setTimeout(function()
									{
										tmr = 0;
										if(error_count < 3 && ifr)
										{
											error_count++;
											ifr.src = ifr.src+Math.floor(Math.random()*1000);
											check_server();
										}
										else if(ifr && cb.onerror)
										{
											cb.onerror();
										}
									}, 6000);
							}
						}
						else if(cb.onmessage)
						{
							cb.onmessage(ms[i].data, ms[i].cmd, json_util.encode(ms[i]));
						}
					}
				}, 1);
			};
		
		if(is_ie) win.$ = $;
		else CometClient.$ = $;
		
		var ifr = doc.createElement('iframe');
		ifr.style.display = 'none';
		ifr.src = 'http://'+shost+(nport==80?'':':'+nport)+'/ifr?bridge='+encodeURIComponent(bridge_dir)+(sdata?'&'+sdata:'')+'&r='+Math.floor(Math.random()*1000000);
		
		ifr = doc.documentElement.appendChild(ifr);
		check_server();
	}
	
	var close_fun = function()
		{
			clear_timeout();
			if(ifr)
			{
				doc.documentElement.removeChild(ifr);
				ifr = doc = win = null;
				delete CometClient.$;
				try{CollectGarbage()}catch(e){};
			}
		};

	if(window.addEventListener) window.addEventListener('unload', close_fun, false);
	else if(window.attachEvent) window.attachEvent('onunload', close_fun);
	
	return close_fun;
}

////////////////////////////////
//utf8 string length
function getStringBytes(s) {
	var totalLength = 0;
	var i;
	var charCode;
	for (i = 0; i < s.length; i++) {
		charCode = s.charCodeAt(i);
		if (charCode < 0x007f) {
			totalLength = totalLength + 1;
		} else if ((0x0080 <= charCode) && (charCode <= 0x07ff)) {
			totalLength += 2;
		} else if ((0x0800 <= charCode) && (charCode <= 0xffff)) {
			totalLength += 3;
		}
	}
	return totalLength;
}

//count uhash for write letters
var hex_md5 = (function(){
	var hexcase=0;
	function hex_hmac_md5(a,b){return rstr2hex(rstr_hmac_md5(str2rstr_utf8(a),str2rstr_utf8(b)))}
	function rstr_md5(a){return binl2rstr(binl_md5(rstr2binl(a),a.length*8))}
	function rstr_hmac_md5(c,f){var e=rstr2binl(c);if(e.length>16){e=binl_md5(e,c.length*8)}var a=Array(16),d=Array(16);for(var b=0;b<16;b++){a[b]=e[b]^909522486;d[b]=e[b]^1549556828}var g=binl_md5(a.concat(rstr2binl(f)),512+f.length*8);return binl2rstr(binl_md5(d.concat(g),512+128))}
	function rstr2hex(c){try{hexcase}catch(g){hexcase=0}var f=hexcase?"0123456789ABCDEF":"0123456789abcdef";var b="";var a;for(var d=0;d<c.length;d++){a=c.charCodeAt(d);b+=f.charAt((a>>>4)&15)+f.charAt(a&15)}return b}
	function str2rstr_utf8(c){var b="";var d=-1;var a,e;while(++d<c.length){a=c.charCodeAt(d);e=d+1<c.length?c.charCodeAt(d+1):0;if(55296<=a&&a<=56319&&56320<=e&&e<=57343){a=65536+((a&1023)<<10)+(e&1023);d++}if(a<=127){b+=String.fromCharCode(a)}else{if(a<=2047){b+=String.fromCharCode(192|((a>>>6)&31),128|(a&63))}else{if(a<=65535){b+=String.fromCharCode(224|((a>>>12)&15),128|((a>>>6)&63),128|(a&63))}else{if(a<=2097151){b+=String.fromCharCode(240|((a>>>18)&7),128|((a>>>12)&63),128|((a>>>6)&63),128|(a&63))}}}}}return b}
	function rstr2binl(b){var a=Array(b.length>>2);for(var c=0;c<a.length;c++){a[c]=0}for(var c=0;c<b.length*8;c+=8){a[c>>5]|=(b.charCodeAt(c/8)&255)<<(c%32)}return a}
	function binl2rstr(b){var a="";for(var c=0;c<b.length*32;c+=8){a+=String.fromCharCode((b[c>>5]>>>(c%32))&255)}return a}
	function binl_md5(p,k){p[k>>5]|=128<<((k)%32);p[(((k+64)>>>9)<<4)+14]=k;var o=1732584193;var n=-271733879;var m=-1732584194;var l=271733878;for(var g=0;g<p.length;g+=16){var j=o;var h=n;var f=m;var e=l;o=md5_ff(o,n,m,l,p[g+0],7,-680876936);l=md5_ff(l,o,n,m,p[g+1],12,-389564586);m=md5_ff(m,l,o,n,p[g+2],17,606105819);n=md5_ff(n,m,l,o,p[g+3],22,-1044525330);o=md5_ff(o,n,m,l,p[g+4],7,-176418897);l=md5_ff(l,o,n,m,p[g+5],12,1200080426);m=md5_ff(m,l,o,n,p[g+6],17,-1473231341);n=md5_ff(n,m,l,o,p[g+7],22,-45705983);o=md5_ff(o,n,m,l,p[g+8],7,1770035416);l=md5_ff(l,o,n,m,p[g+9],12,-1958414417);m=md5_ff(m,l,o,n,p[g+10],17,-42063);n=md5_ff(n,m,l,o,p[g+11],22,-1990404162);o=md5_ff(o,n,m,l,p[g+12],7,1804603682);l=md5_ff(l,o,n,m,p[g+13],12,-40341101);m=md5_ff(m,l,o,n,p[g+14],17,-1502002290);n=md5_ff(n,m,l,o,p[g+15],22,1236535329);o=md5_gg(o,n,m,l,p[g+1],5,-165796510);l=md5_gg(l,o,n,m,p[g+6],9,-1069501632);m=md5_gg(m,l,o,n,p[g+11],14,643717713);n=md5_gg(n,m,l,o,p[g+0],20,-373897302);o=md5_gg(o,n,m,l,p[g+5],5,-701558691);l=md5_gg(l,o,n,m,p[g+10],9,38016083);m=md5_gg(m,l,o,n,p[g+15],14,-660478335);n=md5_gg(n,m,l,o,p[g+4],20,-405537848);o=md5_gg(o,n,m,l,p[g+9],5,568446438);l=md5_gg(l,o,n,m,p[g+14],9,-1019803690);m=md5_gg(m,l,o,n,p[g+3],14,-187363961);n=md5_gg(n,m,l,o,p[g+8],20,1163531501);o=md5_gg(o,n,m,l,p[g+13],5,-1444681467);l=md5_gg(l,o,n,m,p[g+2],9,-51403784);m=md5_gg(m,l,o,n,p[g+7],14,1735328473);n=md5_gg(n,m,l,o,p[g+12],20,-1926607734);o=md5_hh(o,n,m,l,p[g+5],4,-378558);l=md5_hh(l,o,n,m,p[g+8],11,-2022574463);m=md5_hh(m,l,o,n,p[g+11],16,1839030562);n=md5_hh(n,m,l,o,p[g+14],23,-35309556);o=md5_hh(o,n,m,l,p[g+1],4,-1530992060);l=md5_hh(l,o,n,m,p[g+4],11,1272893353);m=md5_hh(m,l,o,n,p[g+7],16,-155497632);n=md5_hh(n,m,l,o,p[g+10],23,-1094730640);o=md5_hh(o,n,m,l,p[g+13],4,681279174);l=md5_hh(l,o,n,m,p[g+0],11,-358537222);m=md5_hh(m,l,o,n,p[g+3],16,-722521979);n=md5_hh(n,m,l,o,p[g+6],23,76029189);o=md5_hh(o,n,m,l,p[g+9],4,-640364487);l=md5_hh(l,o,n,m,p[g+12],11,-421815835);m=md5_hh(m,l,o,n,p[g+15],16,530742520);n=md5_hh(n,m,l,o,p[g+2],23,-995338651);o=md5_ii(o,n,m,l,p[g+0],6,-198630844);l=md5_ii(l,o,n,m,p[g+7],10,1126891415);m=md5_ii(m,l,o,n,p[g+14],15,-1416354905);n=md5_ii(n,m,l,o,p[g+5],21,-57434055);o=md5_ii(o,n,m,l,p[g+12],6,1700485571);l=md5_ii(l,o,n,m,p[g+3],10,-1894986606);m=md5_ii(m,l,o,n,p[g+10],15,-1051523);n=md5_ii(n,m,l,o,p[g+1],21,-2054922799);o=md5_ii(o,n,m,l,p[g+8],6,1873313359);l=md5_ii(l,o,n,m,p[g+15],10,-30611744);m=md5_ii(m,l,o,n,p[g+6],15,-1560198380);n=md5_ii(n,m,l,o,p[g+13],21,1309151649);o=md5_ii(o,n,m,l,p[g+4],6,-145523070);l=md5_ii(l,o,n,m,p[g+11],10,-1120210379);m=md5_ii(m,l,o,n,p[g+2],15,718787259);n=md5_ii(n,m,l,o,p[g+9],21,-343485551);o=safe_add(o,j);n=safe_add(n,h);m=safe_add(m,f);l=safe_add(l,e)}return Array(o,n,m,l)}
	function md5_cmn(h,e,d,c,g,f){return safe_add(bit_rol(safe_add(safe_add(e,h),safe_add(c,f)),g),d)}
	function md5_ff(g,f,k,j,e,i,h){return md5_cmn((f&k)|((~f)&j),g,f,e,i,h)}
	function md5_gg(g,f,k,j,e,i,h){return md5_cmn((f&j)|(k&(~j)),g,f,e,i,h)}
	function md5_hh(g,f,k,j,e,i,h){return md5_cmn(f^k^j,g,f,e,i,h)}
	function md5_ii(g,f,k,j,e,i,h){return md5_cmn(k^(f|(~j)),g,f,e,i,h)}
	function safe_add(a,d){var c=(a&65535)+(d&65535);var b=(a>>16)+(d>>16)+(c>>16);return(b<<16)|(c&65535)}
	function bit_rol(a,b){return(a<<b)|(a>>>(32-b))};
	return function(a){return rstr2hex(rstr_md5(str2rstr_utf8(a)))}
})();

function fromhex(s)
{
	var i;
	var r = [];
	for(i=0; i<s.length; )
	{
		var c = s.substr(i, 2);
		r.push(String.fromCharCode(parseInt(c, 16)));
		i += 2;
	}
	return r.join('');
}

var im_top_domain = (function(){
		var topdomain = window.location.host.match(/[^.]*\.(com|cn|net|org)(\.[^.]*)?/ig);
		if(topdomain) topdomain = topdomain[0];
		else topdomain = document.domain;
		return topdomain;
	})();

var im_root_url = (function()
	{
		var domain = document.location.host;
		if(domain == 'msn.jiayuan.com')
			return 'http://msn.jiayuan.com/';
		if(domain == 'msn.miuu.cn')
			return 'http://msn.miuu.cn/';
		if(/(^|\.)jiayuan\.com$/.test(domain))
			return "http://www.jiayuan.com/";
		if(/(^|\.)miuu\.cn$/.test(domain))
			return "http://www.miuu.cn/";
		
		return "http://"+domain + "/";
	})();

var im_profile_url = (function()
	{
		var domain = document.location.host;
		if(domain == 'msn.jiayuan.com')
			return 'http://msn.jiayuan.com/';
		if(domain == 'msn.miuu.cn')
			return 'http://msn.miuu.cn/';
		if(/(^|\.)jiayuan\.com$/.test(domain))
			return "http://profile.jiayuan.com/";
		if(/(^|\.)miuu\.cn$/.test(domain))
			return "http://profile.miuu.cn/";
		
		return "http://"+domain + "/";
	})();
	
var im_image_base = (function()
	{
		var host = document.location.host;
		if(host.indexOf('msn.jiayuan.com') != -1) return 'http://images1.jyimg.com/m4/webim/';
		if(host.indexOf('jiayuan.com') != -1) return 'http://images1.jyimg.com/w4/webim/';
		if(host.indexOf('jiayuan.msn.com.cn') != -1) return 'http://images1.jyimg.com/m4/webim/';
		if(host.indexOf('msn.miuu.cn') != -1) return 'http://images.miuu.cn/m4/webim/';
		if(host.indexOf('miuu.cn') != -1) return 'http://images.miuu.cn/w4/webim/';
		return '';
	})();
	
var im_webim_base = (function(){
	var domain = document.location.host;
	if(domain == 'msn.jiayuan.com' || domain == 'jiayuan.msn.com.cn')
		return "http://jiayuan.msn.com.cn/webim/";
	if(domain == 'msn.miuu.cn')
		return 'http://msn.miuu.cn/webim/';
	if(/(^|\.)jiayuan\.com$/.test(domain))
		return "http://webim.jiayuan.com/";
	if(/(^|\.)miuu\.cn$/.test(domain))
		return "http://webim.miuu.cn/";
	return "http://"+domain + "/";
})();

var imnew_webim_base = im_webim_base + "imnew/";

var im_subject_base = (function(){
	var domain = document.location.host;
	if(domain == 'msn.jiayuan.com' || domain == 'jiayuan.msn.com.cn')
		return "http://jiayuan.msn.com.cn/subject/";
	if(domain == 'msn.miuu.cn')
		return 'http://msn.miuu.cn/subject/';
	if(/(^|\.)jiayuan\.com$/.test(domain))
		return "http://subject.jiayuan.com/";
	if(/(^|\.)miuu\.cn$/.test(domain))
		return "http://subject.miuu.cn/";
	return "http://"+domain + "/";
})();

function ImSetCookie(name, val)
{
	if(escape_cookie) val = escape(val);
	else val = (''+val).replace(/%/g, '%25').replace(/;/g, '%3B').replace(/\r/g,'%0D').replace(/\n/g,'%0A');
	
	if(getStringBytes(val) > 4000) return false;
	document.cookie = name + "=" + val + "; path=/; domain="+im_top_domain;
	return true;
}

function ImSetCookie_v2(name, val, expires)
{
	if(escape_cookie) val = escape(val);
	else val = (''+val).replace(/%/g, '%25').replace(/;/g, '%3B').replace(/\r/g,'%0D').replace(/\n/g,'%0A');
	
	if(getStringBytes(val) > 4000) return false;
	var exdate=new Date();
	exdate.setMinutes(exdate.getMinutes() + expires);
	document.cookie = name + "=" + val + "; expires="+ exdate.toUTCString() +"; path=/; domain="+im_top_domain;
	return true;
}

function ImGetCookie(name)
{
	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
	if (arr != null) return unescape(arr[2]);
	return '';
}

function ImGetIntCookie(name)
{
	return parseInt('0'+ImGetCookie(name), 10);
}

function toInt(str)
{
	return parseInt('0'+str, 10);
}

function ImDelCookie(name)
{
	document.cookie = name + "=; path=/; domain="+im_top_domain+"; expires=Thu, 01-Jan-1970 00:00:01 GMT";
}

function ImGetMergedCookie(cn)
{
	var s = ImGetCookie(cn);
	if(!s)
		s = {};
	else
	{
		try{
			s = json_util.decode(s);
			if (!s) s= {};
		}catch(e){
			s = {};
			ImSetCookie(cn, json_util.encode(s));
		}
	}
	
	return s;
}

function ImSetMultiCookie(cn, val)
{
	var s = ImGetMergedCookie(cn);
	for (var k in val)
		s[k] = val[k];
	
	return ImSetCookie(cn, json_util.encode(s));
}

function ImSetSingleCookie(cn, name, val)
{
	var s = ImGetMergedCookie(cn);
	s[name] = val;
	return ImSetCookie(cn, json_util.encode(s));
}

function ImGetSCookieRaw()
{
	return ImGetMergedCookie('IM_S');
}

function ImGetSCookie(name)
{
	var s = ImGetMergedCookie('IM_S');
	return s[name];
}

function ImSetSCookie(name, val)
{
	return ImSetSingleCookie('IM_S', name, val);
}

function ImSetMultiSCookie(val)
{
	return ImSetMultiCookie('IM_S', val)
}

function ImGetCCookieRaw()
{
	return ImGetMergedCookie('IM_CON');
}

function ImGetCCookie(name)
{
	var s = ImGetMergedCookie('IM_CON');
	return s[name];
}

function ImSetCCookie(name, val)
{
	return ImSetSingleCookie('IM_CON', name, val);
}

function ImSetMultiCCookie(val)
{
	return ImSetMultiCookie('IM_CON', val)
}

function JymsgClient()
{
	this.started = false;
	this.bridge = false;
	
	this.conn1 = ImGetIntCookie('IM_C1');
	this.conn2 = ImGetIntCookie('IM_C2');
	
	this.conn = 0;
	this.id = 0;
	this.sn = toInt(ImGetCCookie('IM_SN'));
	this.queue = [];
	this.cid = toInt(ImGetSCookie('IM_CID'));
	this.restart_num = 0;
	
	if(this.cid == 0)
	{
		this.cid = 1 + Math.floor(Math.random()*10000000);
		ImSetSCookie('IM_CID', this.cid);
		ImSetCookie('IM_CS', 0);
	}
	this.uhash = ImGetCookie('COMMON_HASH');
	
	var me = this;
	var quit_fun=function(){
		if(me.bridge) {
			me.notifyOthers(me.isMaster(), true);
			var i = ImGetIntCookie('IM_CS');
			if(i > 0) ImSetCookie('IM_CS', i-1);
		}
	};
	if(window.addEventListener) window.addEventListener('unload', quit_fun, false);
	else if(window.attachEvent) window.attachEvent('onunload', quit_fun);
}

JymsgClient.prototype.isMaster = function()
{
	if(window.opera)
	{
		var conn1 = ImGetIntCookie('IM_C1');
		if(conn1 == this.conn1) return true; //master die
		this.conn1 = conn1;
		return !!this.master;
	}
	
	if(this.id == 0) return false;
	var c = ImGetIntCookie('IM_CS');
	if(c < 2) return true;
	var i = ImGetIntCookie('IM_ID');

	return (this.id < i);
}

JymsgClient.prototype.isConnected = function()
{
	var cs = ImGetIntCookie('IM_CS');
	if(cs == 2) return true;
	if(cs == 0) return false;
	
	var now = (new Date()).getTime();
	var tk = ImGetIntCookie('IM_TK');
	return (now - tk <= 3000);
}

JymsgClient.prototype.needConnect = function()
{
	var me = this;
	if(window.opera)
	{
		var conn1 = ImGetIntCookie('IM_C1');
		var conn2 = ImGetIntCookie('IM_C2');
		if(conn1 == this.conn1)
		{
			ImSetCookie('IM_C1', ++me.conn);
			this.master = true;
			this.tmr1 = window.setInterval(function(){ImSetCookie('IM_C1', ++me.conn);}, 500);
			return true;
		}
		if(conn2 == this.conn2)
		{
			this.conn1 = conn1;
			ImSetCookie('IM_C2', ++this.conn);
			this.tmr1 = window.setInterval(function(){ImSetCookie('IM_C2', ++me.conn);}, 500);
			return true;
		}
		
		this.conn1 = conn1;
		this.conn2 = conn2;
		
		return false;
	}
	
	var conns = ImGetIntCookie('IM_CS');
	if(conns < 2)
	{
		ImSetCookie('IM_CS', conns+1);
		return true;
	}
	return false;
}

JymsgClient.prototype.notifyOthers=function(master, notcheck)
{
	if(!master) return false;
	
	if(this.uhash != ImGetCookie('COMMON_HASH'))
	{
		this.stop();
		if(this.OnError) this.OnError(true);
		return false;
	}
	
	var tm = toInt(ImGetCCookie('IM_TM'));
	var now = (new Date()).getTime();
	ImSetCookie('IM_TK', now);
	if(this.queue.length == 0)
	{
		var esc = now - tm;
		if(esc > 4000 && esc < 12000)
		{
			ImSetCookie('IM_M', '[]');
		}
		return true;
	}

	var sn = toInt(ImGetCCookie('IM_SN'));
	if(sn > this.sn)
	{
		this.queue.splice(0, sn - this.sn);
		this.sn = sn;
	}
	
	if(this.queue.length)
	{
		if((now - tm > 2000 || now < tm) || notcheck)
		{
			var len = this.queue.length;
			var del = 0;
			while(true)
			{
				if(len == 0)
				{
					this.queue.shift(); ++del;
					len = this.queue.length;
				}
				var msg = '['+this.queue.slice(0, len).join(',')+']';
				if(ImSetCookie('IM_M', msg)) break;
				len--;
			}
			
			ImSetCCookie('IM_TM', now);
			this.sn += len + del;
			ImSetCCookie('IM_SN', this.sn);
			this.queue.splice(0, len);
			return true;
		}
		return false;
	}
	
	return true;
}

JymsgClient.prototype.notify = function(msg, type)
{
	if(!this.isMaster()) return;
	if(this.OnMessage) this.OnMessage(msg, type, true);
	this.queue.push(json_util.encode({'data':msg, 'cmd':type}));
}

JymsgClient.prototype.pullMsgs = function()
{
	if(this.uhash != ImGetCookie('COMMON_HASH'))
	{
		this.stop();
		if(this.OnError) this.OnError(true);
		return false;
	}
	
	var sn = toInt(ImGetCCookie('IM_SN'));
	if(sn != this.sn)
	{
		var json = ImGetCookie('IM_M');
		if (json == '') return false;
		var msgs = json_util.decode(json);
		for(var i=0; i<msgs.length; ++i)
		{
			if(this.OnMessage) this.OnMessage(msgs[i].data, msgs[i].cmd, false);
		}
		this.sn = sn;
	}
	else
	{
		var now = (new Date()).getTime();
		var id = ImGetIntCookie('IM_ID');
		var cs = ImGetIntCookie('IM_CS');
		var tk = ImGetIntCookie('IM_TK');
		if(now - tk > 3000 && id > 1 && cs == 2)
		{
			ImSetCookie('IM_TK', now+3000);
			this.id = id - 1;
			
			if(this.tmr3)
			{
				window.clearInterval(this.tmr3);
				delete this.tmr3;
			}
			
			if(!this.bridge)
				this.connect(1);
		}
	}
	
	return true;
}

JymsgClient.prototype.connect=function(relay)
{
	var me = this;
	this.bridge = true;
	
	me.host = GetCometHost(ImGetCookie('COMMON_HASH'));
	var hs = me.host.split(':');
	var p = 80;
	if(hs.length == 2) p = parseInt(hs[1]);
	
	this.close_fun = CometClient(hs[0], p,
		{
			ver: '3.0',
			ua:'web',
			cid: this.cid,
			relay:relay,
			common_hash: ImGetCookie('COMMON_HASH'),
			raw_hash: ImGetCookie('RAW_HASH'),
			myloc: ImGetCookie('myloc'),
			gender: ImGetCookie('PROFILE').split(':')[2],
			age: ImGetCookie('myage'),
			income: ImGetCookie('myincome'),
			flag: 0
		},
		{
		onmessage: function(msg, type, raw)
			{
				if(me.uhash != ImGetCookie('COMMON_HASH'))
				{
					me.stop();
					if(me.OnError) me.OnError(true);
					return;
				}
				
				var master = me.isMaster();
				if(master)
				{
					if(me.tmr3)
					{
						window.clearInterval(me.tmr3);
						delete me.tmr3;
						me.pullMsgs();
					}
					
					me.queue.push(raw);
					me.notifyOthers(master);
					
					if(me.OnMessage) me.OnMessage(msg, type, master);
				}
			},
		onconnect: function()
			{	
				if(me.OnConnect) me.OnConnect();
			},
		onerror: function()
			{
				if(me.OnError) {me.stop(true); me.OnError(false);}
				else me.stop();
			}
		},
		'http://'+ document.location.host + '/webim/helper/'
	);
	
	me.tmr2 = window.setInterval(function(){me.notifyOthers(me.isMaster());}, 1000);
}

JymsgClient.prototype.start = function()
{
	this.stop();
	this.started = true;
	var me = this;
	var master = false;
	
	if(me.needConnect())
	{
		this.id = ImGetIntCookie('IM_ID') + 1;
		ImSetCookie('IM_ID', this.id);
		master = me.isMaster();
		me.connect(master?0:1);
	}
	
	if(!master)
	{
		me.tmr3 = window.setInterval(function(){
			me.pullMsgs();
			if(me.started && !me.bridge && me.needConnect())
			{
				me.id = ImGetIntCookie('IM_ID') + 1;
				ImSetCookie('IM_ID', me.id);
				me.connect(1);
			}
		}, 1000);
	}
}

JymsgClient.prototype.restart = function()
{
	if(!this.bridge) return false;
	this.stop(true);
	if (this.restart_num > 2) return false;
	this.restart_num++;
	this.started = true;
	var me = this;
	
	var master = me.isMaster();
	me.connect(master?0:1);
	
	if(!master)
	{
		me.tmr3 = window.setInterval(function(){
			me.pullMsgs();
			if(me.started && !me.bridge && me.needConnect())
			{
				me.id = ImGetIntCookie('IM_ID') + 1;
				ImSetCookie('IM_ID', me.id);
				me.connect(1);
			}
		}, 1000);
	}
}

JymsgClient.prototype.stop = function(keep_conn)
{
	if(this.tmr1){window.clearInterval(this.tmr1);delete this.tmr1;}
	if(this.tmr2){window.clearInterval(this.tmr2);delete this.tmr2;}
	if(this.tmr3){window.clearInterval(this.tmr3);delete this.tmr3;}
	if(this.close_fun)
	{
		this.close_fun();
		delete this.close_fun;
	}
	if(this.bridge && !keep_conn)
	{
		var i = ImGetIntCookie('IM_CS');
		if(i > 0) ImSetCookie('IM_CS', i-1);
		this.bridge = false;
	}
	if(!keep_conn) this.id = 0;
	this.queue = [];
	this.started = false;
};

// new pv 
function reportPV(log)
{
	var f_url = 'http://pv2.jyimg.com/any/';
	var Arr = ["a","b","c","d","e","f","g","h","i","g","k","l","m","n","o","p","q","r","s","t","u","v","x","y","z"];
		var n = Math.floor(Math.random() * Arr.length + 1)-1;   
		var url = f_url +Arr[n]+".gif?|"+log+"|"+new Date().getTime()+"|";
		var sender = new Image();
		sender.onload = function(){clear(this);};
		sender.onerror = function(){clear(this);};
		sender.onabort = function(){clear(this);};
		sender.src = url;
		function clear(obj){
			obj.onerror = null;
			obj.onload = null;
			obj.onabort = null;
			obj = null;
		}
}

(function($, doc){
	if(!ImGetCookie('COMMON_HASH') ||
		window.top != window.self ||
		typeof(pop_template)=='undefined' ||
		typeof(GetCometHost)=='undefined' ||
		typeof(jQuery) == 'undefined')
	{
		return;
	}
	
	var ie6 = false;
	var ua = navigator.userAgent.toUpperCase();
	if(ua.indexOf("OPERA") == -1 &&
		ua.indexOf("FIREFOX") == -1 &&
		ua.indexOf("SAFARI") == -1 &&
		ua.indexOf("CHROME") == -1)
	{
		if(ua.indexOf("MSIE") != -1){
			var reIE = new RegExp("MSIE (\\d+\\.\\d+);");  
			if(!reIE.test(ua)) return;
			var ieVer = parseFloat(RegExp["$1"]); 
			if(ieVer < 6) return;
			if(ieVer == 6)
			{
				ie6 = true;
				doc.write('<link href=\"'+im_image_base+'jyim3-ie6.css?v=22\" type=\"text/css\" rel=\"stylesheet\" />');
			}
			else
			{
				doc.write('<link href=\"'+im_image_base+'jyim3.css?v=22\" type=\"text/css\" rel=\"stylesheet\" />');
			}
		}
		else if (ua.indexOf("LIKE GECKO") != -1){ // IE11
			doc.write('<link href=\"'+im_image_base+'jyim3.css?v=22\" type=\"text/css\" rel=\"stylesheet\" />');
			$.browser.msie = true;
		}
		else{
			return;
		}
	}
	else
	{
		doc.write('<link href=\"'+im_image_base+'jyim3.css?v=22\" type=\"text/css\" rel=\"stylesheet\" />');
	}
	
	$.fn.grayscale=function(s)
	{
		if($.browser.msie)
		{
			if(typeof(s)!='undefined' && !s) this.css('filter', '');
			else this.css('filter', 'gray(true)');
		}
		else
		{
			if(typeof(s)!='undefined' && !s) this.css('opacity', '');
			else this.css('opacity', '0.3');
		}
	};
	
	$.fn.sort = function()
	{
		return this.pushStack( [].sort.apply(this, arguments), []);
	};
	
	$.fn.hoverSwitch = function(cls, e)
	{
		if(e.type == 'mouseenter') this.addClass(cls);
		else this.removeClass(cls);
	};
	
	var $ = nojQuery ? $.noConflict(true) : $;
	
	function JyFootbar()
	{
		this._container = $('#im_bar');
		this._container.css("display", "none");
		this._container.addClass("im_bar_min");
	}

	JyFootbar.prototype = {
		getDom: function()
		{
			return this._container;
		},
		addBtnItem: function(fbitem)
		{
			this._container.append(fbitem.getDom());
		},
		addListItem: function(fbitem)
		{
			this._container.append(fbitem.getDom());
		},
		addDetachedPopup: function(html)
		{
			var popup = $(html);
			this._container.append(popup);
			popup.hide();
		},
		removeItem: function(className)
		{
			$("." + className, this._container).remove();
		}
	};

	function JyFootbarBtnItem(itemName, id, align, click)
	{
		this._id = id;
		this._name = itemName;
		this._align = align ? align : "right";
		this._dom = $('<div class="im_btn"><a class="im_inner_btn">' + this._name + '</a></div>');
		this._btn = $('.im_inner_btn', this._dom);
		if (this._id)
			this._dom.attr("id", this._id);
		if (click)
			this._btn.click(click);
		this._align == 'left' ?  this.addItemClass("fl") : this.addItemClass("fr");
	}

	JyFootbarBtnItem.prototype = {
		getDom: function()
		{
			return this._dom;
		},
		setClick: function(click)
		{
			this._dom.live("click", click);
		},
		addItemClass: function(className)
		{
			this._dom.addClass(className);
		},
		getBtnClass: function(){
			return this._btn.attr("class");
		},
		addBtnClass: function(className)
		{
			this._btn.addClass(className);
		},
		removeBtnClass: function(className)
		{
			this._dom.removeClass(className);
		},
		removeItemClass: function(className)
		{
			this._dom.removeClass(className);
		},
		addNumber: function(num)
		{
			var html = '<span class="amount fl"><span class="amount_leftBg fl"></span><span class="amount_centerBg fl"></span><span class="amount_rightBg fl"></span></span>';
			var numDom = $(html);
			$('.amount_centerBg', numDom).text(num);
			this._btn.after(numDom);
			this._numDom = numDom;
			this.hideNumber();
		},
		changeNumber: function(num)
		{
			$('.amount_centerBg', this._numDom).text(num);
		},
		getNumber: function()
		{
			return parseInt($('.amount_centerBg', this._numDom).text());
		},
		hideNumber: function()
		{
			this._numDom.hide();
		},
		attr: function(pro,val){
			this._btn.attr(pro,val);
		},
		showNumber: function()
		{
			this._numDom.css("display", "inline");
		}
	}

	function JyFootbarListItem(id, align)
	{
		this._id = id;
		this._align = align ? align : "right";
		this._dom = $('<ul></ul>');
		if (this._id)
			this._dom.attr("id", this._id);
		this._align == 'left' ?  this.addClass("fl") : this.addClass("fr");
	}

	JyFootbarListItem.prototype = {
		addItem: function(name, className, title, url)
		{
			var li = $("<li><a></a></li>");
			$("a", li).html(name);
			if (title)
				li.attr("title", title);
			if (className)
				li.addClass(className);
			if (url)
				$("a", li).attr("href", url).attr("target", "_blank");
			this._dom.append(li);
		},
		addClass: function(className)
		{
			this._dom.addClass(className);
		},
		getDom: function()
		{
			return this._dom;
		}
	}

	function JyFootbarPopup(id, title, className, titleClass, isClose)
	{
		this._id = id;
		this._dom = $('<div id="' + id + '" class="im_win" style="display:none;"><div class="pr im_win_title"><div class="title_leftBg fl"></div><h1 class="title_centerBg"></h1><div class="title_rightBg fl"></div></div><div class="im_list"></div></div>');
		this._title = $('.im_win_title', this._dom);
		this._body = $('.im_list', this._dom);
		$('h1', this._title).html(title);
		if (className)
			this._dom.addClass(className);
		if (titleClass)
			this._title.addClass(titleClass);
		if (isClose)
			$(".title_rightBg", this._title).append($('<a class="pr im_t_close"></a>'));
	}

	JyFootbarPopup.prototype = {
		getTitle: function()
		{
			return this._title;
		},
		getBody: function()
		{
			return this._body;
		},
		getDom: function()
		{
			return this._dom;
		},
		customTitle: function(title_html)
		{
			if (typeof title_html == "string")
				$(".title_rightBg", this._title).append($(title_html));
			else
				$(".title_rightBg", this._title).append(title_html);
		},
		customBody: function(body_html)
		{
			if (typeof body_html == "string")
				this._body.append($(body_html));
			else
				this._body.append(body_html);
		},
		appendToBtn: function(btn)
		{
			btn.getDom().append(this._dom);
			btn.getDom().attr("t", "#" + this._id).addClass("im_attached_pop");
		}
	};

	function JyFootbarPopupList()
	{
		JyFootbarPopup.apply(this, arguments);
	}

	JyFootbarPopupList.prototype = new JyFootbarPopup();
	JyFootbarPopupList.prototype.constructor = JyFootbarPopupList;
	JyFootbarPopupList.prototype.addItem = function(name, url, id, tj, add_strong, add_span_class)
	{
		var item = $('<div class="im_item"><a target="_blank"></a></div>');
		var btn = $("a", item);
		btn.attr("href", url).html(name);
		if (id)
			item.attr("id", id);
		if (add_strong)
			btn.append($('<strong></strong>'));
		if (add_span_class)
			btn.append($('<span class="' + add_span_class + '"></span>'));
		if (tj)
			btn.attr("tj", tj);
		this.getBody().append(item);
	}
	
	var last_pop_time = 0;
	var pop_visible = false;
	var list_loaded = false;		//friends list loaded
	var chat_loaded = false;
	var old2new_map = {'999':1999, '4':106, '15':106, '16':117, '17':116, '50':120, '88':121, '89':121};
	var new2old_map = {'119':21,'118':20,/*'116':17,'117':16,'106':15,*/
		'115':13,'114':12,'113':11,'112':10,'111':9,'110':8,'109':7,
		'108':6,'107':5,'104':3,'101':2,'103':1};
	var new2old_phone_map = {'103':28};
	
	var myuid = parseInt(ImGetCookie('PROFILE').split(':')[0]);
	var mysex = ImGetCookie('PROFILE').split(':')[2];
	var location_url = document.location.href;
	var is_profile = location_url.indexOf('/profile') || /^(http\:\/\/www\.jiayuan\.com\/\d+\/?)([\?\#])?.*$/.test(location_url) || /^(http\:\/\/www\.miuu\.cn\/\d+\/?)([\?\#])?.*$/.test(location_url);
	
	var pvLink = '';
	(function(){
		var myage = ImGetCookie("myage");
		var myloc = ImGetCookie("myloc");
		pvLink = "http://pv.jyimg.com/call/v.gif?w=1&location=" + myloc + "&sex=" + mysex + "&age=" + myage + "&uid=" + myuid;
	})();
	
	function getPvLink()
	{
		return pvLink + "&rd=" + Math.random()+"&";
	}
	
	function CreateAudio(file)
	{
		var id = 'audio_'+Math.floor(Math.random()*1000000);
		if($.browser.msie)
		{
			doc.write('<bgsound id="'+id+'" loop="false"/></bgsound>');
			var e = $('#'+id);
			
			return {
				play: function() { e.attr('src', file+'.mp3'); },
				stop: function() { e.attr('src', ''); },
				destroy: function(){e.remove()}
			};
		}
		
		doc.write('<audio preload="auto" id="'+id+'"><source src="'+file+'.mp3" type="audio/mpeg" /><source src="'+file+'.ogg" type="audio/ogg" /></audio>');
		var e = $('#'+id);
		
		return {
			play: function(){this.stop();try{e[0].play();}catch(e){}},
			stop: function(){try{e[0].pause(); e[0].currentTime = 0;}catch(e){}},
			destroy: function(){e.remove();}
		};
	}

	var jyappClient = undefined;
	function GetAppClient()
	{
		if (jyappClient!==undefined) return jyappClient;		
		if($.browser.msie)
		{
			try
			{
				jyappClient = new ActiveXObject('Jyassist.msgr');
				return jyappClient;
			}
			catch(e)
			{
				jyappClient = null;
				return jyappClient;
			}
		}
		
		if(!navigator.mimeTypes) return false;
		var i;
		for(i=0; i<navigator.mimeTypes.length; i++)
		{
			if(navigator.mimeTypes[i].type == 'application/x-jyassist')
			{
				try
				{
					var ctl_id = 'jyappclient_'+Math.floor(Math.random()*1000000);
					var jyapp = $('<embed id="'+ctl_id+'" type="application/x-jyassist" rot="false" width="1" height="1"></embed>');
					$(doc.body).append(jyapp);
					jyappClient = jyapp[0];
					return jyappClient;
				}
				catch(e)
				{
					jyappClient = null;
					return jyappClient;
				}
			}
		}
		
		jyappClient = null;
		return jyappClient;
	}
	
	var pcClient = undefined;
	var pcVersion = '';
	function GetPcClient(createNew)
	{

		pcClient = null;
		return pcClient;

		if (pcClient!==undefined) return pcClient;
		if(typeof(createNew)=='undefined') createNew = true;
		
		if($.browser.msie)
		{
			try
			{
				if(createNew)
					pcClient = new ActiveXObject('Jyclient.msgr');
				else
					pcClient = GetObject('Jyclient.msgr');
				
				pcVersion = pcClient.version();
				return pcClient;
			}
			catch(e)
			{
				pcClient = null;
				return pcClient;
			}
		}
		
		if(!navigator.mimeTypes) return false;
		var i;
		for(i=0; i<navigator.mimeTypes.length; i++)
		{
			if(navigator.mimeTypes[i].type == 'application/x-jymsgr')
			{
				try
				{
					var ctl_id = 'jyclient_'+Math.floor(Math.random()*1000000);
					var jymsgr = $('<embed id="'+ctl_id+'" type="application/x-jymsgr" rot="'+(createNew?'false':'true')+'" width="1" height="1"></embed>');
					$(doc.body).append(jymsgr);
					pcClient = jymsgr[0];
					pcVersion = pcClient.version();
					return pcClient;
				}
				catch(e)
				{
					pcClient = null;
					return pcClient;
				}
			}
		}
		
		pcClient = null;
		return pcClient;
	}
	
	try
	{
		var ajax_login = false;
		var pclog_obj = ImGetMergedCookie('pclog');
		if (!pclog_obj) pclog_obj = {};
		if (pclog_obj && pclog_obj[myuid])
		{
			var cur_time = new Date();
			var pclog_arr = pclog_obj[myuid].split('|');
			if (!pclog_arr) pclog_arr = [0,0];
			if (pclog_arr[2] === undefined)
			{
				pclog_arr[2] = GetPcClient() ? 1 : 0;
				pclog_obj[myuid] = pclog_arr.join('|');
				ImSetCookie_v2('pclog', json_util.encode(pclog_obj), today_remain_time(cur_time.getTime()));
			}
			var login_time = new Date(parseInt(pclog_arr[0]));
			var login_day = login_time.getFullYear()+'-'+login_time.getMonth()+'-'+login_time.getDate();
			var today = cur_time.getFullYear()+'-'+cur_time.getMonth()+'-'+cur_time.getDate();
			if (login_day != today)
			{
				pclog_obj[myuid] = cur_time.getTime() + '|1|' + pclog_arr[2];
				ImSetCookie_v2('pclog', json_util.encode(pclog_obj), today_remain_time(cur_time.getTime()));
				doLoginPcclient();
			}
			else
			{
				window.setTimeout(show_jiaxin_pop, 5000);
			}
		}
		else
		{
			var haspc = GetPcClient() ? 1 : 0;
			var cur_time = new Date();
			pclog_obj[myuid] = cur_time.getTime() + '|1|' + haspc;
			ImSetCookie_v2('pclog', json_util.encode(pclog_obj), today_remain_time(cur_time.getTime()));
			doLoginPcclient();
			window.setTimeout(show_jiaxin_pop, 5000);
		}
	}
	catch(e){}
	//switchjx();

	function today_remain_time(tm)
	{
		var d = new Date(tm);
		d.setDate(d.getDate()+1);
		d.setHours(0);
		d.setMinutes(0);
		d.setSeconds(0);
		return parseInt((d.getTime() - tm)/1000/60);
	}
	
	function doLoginPcclient()
	{
		var client = GetPcClient();
		if (!client) return;
		reportPV('pc_auto_login|' + myuid);
		reportPV('pc_auto_login_' + pcVersion + '|' + myuid);
		$.ajax({
			type: "GET",
			url: getAjaxUrl('ajax.php?svc=get_hash'),
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(ret){
				if (ret.code != 0) return;
				var suc = false;
				var err = "normal";
				try{
					suc = client.login(myuid - 1000000, ret.hash, false);
				}catch(e){
					err = e.message;
					try{
						suc = client.login(myuid - 1000000, ret.hash);
					}catch(e){
						err = e.message;
					}
				}
				var bt = 'unknown';
				if ($.browser.safari)
					bt = 'safari';
				else if ($.browser.opera)
					bt = 'opera';
				else if ($.browser.msie)
					bt = 'msie';
				else if ($.browser.mozilla)
					bt = 'mozilla';
				var bv = encodeURIComponent($.browser.version);
				suc = suc ? 'suc' : 'fail';
				err = encodeURIComponent(err);
				reportPV('pc_auto_login_' + bt + '_' + bv + '_' + suc + '_' + err + '|' + myuid);
			}
		});
	}

	function switchjx(){
		var jyapp = GetAppClient();
		if (!jyapp) return;
		try{
			var jystart = jyapp.IsJyMsgrRun();
			if (!jystart) return;
			var client = GetPcClient();
			if (!client) return;
			client.IsSwitchToWebUser(myuid-1000000);
		}
		catch(e){}
		return;
	}
	
	//var pcClient = undefined;
	function IsPcClientOnline()
	{
		try
		{
			var client = GetPcClient();
			if(client && client.isLogined2(myuid - 1000000))
			{
				return true;
			}
		}catch(e){}
		return false;
	}
	
	var g_openchat_tmr = 0;
	function OpenWebChat(uid, sex)
	{
		if(g_openchat_tmr) return -1;
		g_openchat_tmr = window.setTimeout(function(){g_openchat_tmr = 0;}, 800);
		
		if(uid == myuid - 1000000)
		{
			alert('您不能和自己聊天！');
			return -1;
		}
		if(sex == mysex)
		{
			alert("对不起，您不能和同性聊天，更多异性正在等您!");
			return  -1;
		}
		
		try
		{
			if(IsPcClientOnline())
			{
				pcClient.chatWith(uid);
				$.ajax({
					type: "GET",
					url: getAjaxUrl("ajax.php?svc=getChatUser&uid="+uid),
					dataType: "jsonp",
					jsonp: 'jsoncallback',
					success: function(ret){
					}
				});
				return;
			}
		}catch(e){}
		
		if (!pcClient)
		{
			open_webim_chat(uid);
		}
		else
		{
			// 如果已经启动客户端，则呼出客户端窗口
			try{
				pcClient.version();
			}catch(e){
				open_webim_chat(uid);
				return 0;
			}
			$.ajax({
				type: "GET",
				url: getAjaxUrl('ajax.php?svc=get_hash'),
				dataType: "jsonp",
				jsonp: 'jsoncallback',
				success: function(ret){
					if (!ret || ret.code != 0)
					{
						open_webim_chat(uid);
						return;
					}
					try{
						pcClient.login(myuid - 1000000, ret.hash, false);
						window.setTimeout(function(){
							try{
								if (IsPcClientOnline())
								{
									pcClient.chatWith(uid);
									$.ajax({
										type: "GET",
										url: getAjaxUrl("ajax.php?svc=getChatUser&uid="+uid),
										dataType: "jsonp",
										jsonp: 'jsoncallback',
										success: function(ret){
										}
									});
								}
								else
									open_webim_chat(uid);
							}
							catch(e){
								open_webim_chat(uid);
							}
						}, 2000);
					}catch(e){
						try{
							pcClient.login(myuid - 1000000, ret.hash);
							window.setTimeout(function(){
								try{
									if (IsPcClientOnline())
									{
										pcClient.chatWith(uid);
										$.ajax({
											type: "GET",
											url: getAjaxUrl("ajax.php?svc=getChatUser&uid="+uid),
											dataType: "jsonp",
											jsonp: 'jsoncallback',
											success: function(ret){
											}
										});
									}
									else
										open_webim_chat(uid);
								}
								catch(e){
									open_webim_chat(uid);
								}
							}, 2000);
						}catch(e){
							open_webim_chat(uid);
						}
					}
				},
				error: function(){
					open_webim_chat(uid);
				}
			});
		}
		
		return 0;
	}

	function open_webim_chat(uid){
		var win_name = ImGetCookie('IM_CT');
		if(!win_name) win_name = 'chat_0';
		uid = parseInt(uid) + 1000000;
		(window.open(im_webim_base + 'chat2.php#'+uid, win_name, 'height=650,width=880,resizable=yes')).focus();
	}
	
	if(typeof(im_switch) == 'undefined') im_switch = false;
	
	if(im_switch)
	{
		try{bulletin_div.style.display = 'none';}catch(e){}
		try{pop.bulletin_div.style.display = 'none';}catch(e){}
		
		pop_content = function(msg)
		{
			var rex = /<img src=[\'\"]http:\/\/pv\.[^>]+>/mi;
			var pv = msg.match(rex);
			pv = (pv ? pv[0] : '');
			
			var r = $.trim(msg.replace(rex, '')).match(/^<div.*? type=(\d+)[^>]*>([\s\S]*?)<\/div>$/mi);
			if(r)
			{
				var type = old2new_map[r[1]];
				if(type) 
				{
					if(type == 120) return; //old_pop_content(msg);
				}
				else
				{
					//if(ImGetCookie('IM_S') && ImGetIntCookie('IM_CS')) return;
					type = parseInt(r[1]);
					if(type == 2) hideList();
					//150为bm弹层已经去掉
					if(type == 150 || type==151){
						var h_xiuq = 504,w_xiuq = 672;
						if(nojQuery && !window.jQuery){
							$.getScript(im_image_base+'../usercp/j/jquery-1.4.2.min.js',function(){
								$.getScript(im_image_base+'pop_full.js', function(){
									var is_msg = (/^(http\:\/\/www\.miuu\.cn\/msg\/)([\?\#])?.*$/.test(document.location.href) || /^(http\:\/\/www\.jiayuan\.com\/msg\/)([\?\#])?.*$/.test(document.location.href) || /^(http\:\/\/msg\.jiayuan\.com\/)([\?\#])?.*$/.test(document.location.href));
									if(!is_msg && !jQuery('#bgdiv').is(":visible")) showPopFull(r[2],w_xiuq,h_xiuq);
								});
							});
						}else{
							$.getScript(im_image_base+'pop_full.js', function(){
								var is_msg = (/^(http\:\/\/www\.miuu\.cn\/msg\/)([\?\#])?.*$/.test(document.location.href) || /^(http\:\/\/www\.jiayuan\.com\/msg\/)([\?\#])?.*$/.test(document.location.href) || /^(http\:\/\/msg\.jiayuan\.com\/)([\?\#])?.*$/.test(document.location.href));
								if(!is_msg && !jQuery('#bgdiv').is(":visible")) showPopFull(r[2],w_xiuq,h_xiuq); 
							});
						}
						return ;
					}
				}
				var res = showPop(r[2]+pv, 1, type);
				insertMsg(r[2]+(res?'':pv), type, res);
			}
		};
		
		insert_con = function()
		{
			var i;
			var pop_a_i = [];
			var succ_user = false;
			if('1' != readCookie('PROFILE').split(':')[8])
			{
				for(i=(pop_arr.length-1);i>=0;i--)
				{
					if(pop_arr[i][8] == '1')
					{
						pop_a_i.push(i);
						succ_user = true;
					}
				}
			}
			
			if(pop_a_i.length == 0)
			{
				var myloc	=	readCookie('myloc');
				var myloc_arr	=	false;
				if(myloc)
				{
					myloc_arr	=	myloc.split('|');
				}
				var mod;
				var _url = location.href;
				_url = _url.replace('http://','');
				var _url_arr = _url.split('/');
				mod	= _url_arr[1];
				var _dom_arr	=	_url_arr[0].split('.');
				if(_dom_arr[0]	!=	'www')
				{
					mod	= _dom_arr[0];
				}
				
				switch (mod)
				{
					case 'usercp':
						mod_code	=	2;
						break;
					case 'ques':
						mod_code	=	2;
						break;
					case 'msg':
						mod_code	=	2;
						break;
					case 'charge':
						mod_code	=	2;
						break;
					case 'search':
						mod_code	=	3;
						break;
					case 'party':
						mod_code	=	10;
						break;
					case 'parties':
						mod_code	=	10;
						break;
					case 'article':
						mod_code	=	4;
						break;
					case 'diary':
						mod_code	=	4;
						break;
					case 'online':
						mod_code	=	5;
						break;
					case 'story':
						mod_code	=	7;
						break;
					case 'love':
						mod_code	=	7;
						break;
					case 'my':
						mod_code	=	6;
						break;
					case 'profile':
						mod_code	=	6;
						break;
					case 'student':
						mod_code	=	8;
						break;
					case 'master':
						mod_code	=	9;
						break;
					default:
						mod_code	=	1;
						break;
				}
				
				for(i=(pop_arr.length-1);i>=0;i--)
				{
					var page_arr = pop_arr[i][3].split(',');
					for(k=0;k<page_arr.length;k++)
					{
						if(page_arr[k] > 0)
						{
							if(mod_code	==	page_arr[k])
							{
								if(pop_arr[i][4] > 0)
								{
									if(typeof(HEAD_USER) != 'undefined' && HEAD_USER.uid > 0 && myloc_arr)
									{
										if((pop_arr[i][4] + '00')	==	pop_arr[i][5])
										{
											if(myloc_arr[0]	== pop_arr[i][4])
											{
												pop_a_i.push(i);
											}
										}
										else
										{
											if(myloc_arr[1]	==	pop_arr[i][5])
											{
												pop_a_i.push(i);
											}
										}
									}
								}
								else
								{
									pop_a_i.push(i);
									break;
								}
							}
						}
					}
				}
			}
			
			if(pop_a_i.length)
			{
				var j = Math.floor(Math.random() * pop_a_i.length);
				i = pop_a_i[j];
				var msg = pop_arr[i][6];
				var cookieName = pop_arr[i][7];
				var minu = parseInt(pop_arr[i][1]);
				var displayMode = pop_arr[i][0];
				
				if(cookieName != '')
				{
					var tm = new Date().getTime();
					var nextTime = parseInt(tm)+minu*60*1000;
					var n = parseInt(readCookie(cookieName));
					if(!n) n = 0;
					if(displayMode == "once"){
						if(tm < n){
							showPop(msg, 2, 0);
							return;
						}
					}
					writeCookie(cookieName, nextTime, 24*30);
				}
				////////////////统计
				if(succ_user)
				{
					showPop(msg, 2, 1);
					report_old_PV('http://pv2.jyimg.com/any/v.gif?|xique_ad||'+Math.floor(Math.random()*100000));
				}
				else
				{
					var huodong_len_l = msg.indexOf('pid=');
					var pid = msg.substr(huodong_len_l+4,4);
					var pv = "&pv=huodong&pid="+pid+"&";
					showPop(msg, 2, 1, pv);
				}
			}
		};
		
		_mini = function()
		{
			//$('#im_pop_win').animate({"marginBottom": "-=198px"}, 1000, "linear", 
			$('#im_pop_win').slideUp(1000,
			function(){
				$('#im_pop_win').hide();
				chgImBarMode(false);
				pop_visible = false;
			});
		}
	}
	
	
	function report_old_PV(url)
	{
		var img = new Image();
		img.onload = function(){clear(this);};
		img.onerror = function(){clear(this);};
		img.onabort = function(){clear(this);};
		img.src = url;
		function clear(obj){
			obj.onerror = null;
			obj.onload = null;
			obj.onabort = null;
			obj = null;
		}
	}
	
	function insertMsg(htm, type, shown)
	{
		var list = $('#im_msgx .im_list');
		$('div[type="'+type+'"]', list).slice(0).remove();
		
		var m = $('<div class="im_mi"><div class="im_msg_icon"></div><div class="im_msg_cont"></div></div>');
		var cont = $(".im_msg_cont", m);
		cont.html(htm);
		$(".im_msg_icon", m).addClass("im_msg_icon_" + type);
		m.attr('type', type);
		$('br', m).remove();
		
		if(type == 103)
		{
			$('span', m).remove();
			var url=im_root_url+'usercp/clicked.php?from=wholook';
			$('&nbsp;<a target="_blank" href="'+url+'">[最近看过我的人]</a>').appendTo(cont);
		}
		
		$('.im_jy_logo').remove();
		list.prepend(m);
		
		if(!shown)
		{
			msg_btn.changeNumber(msg_btn.getNumber() + 1);
			msg_btn.showNumber();
			/*
			var e = $('.im_msg_new');
			e.text(parseInt(e.text())+1);
			e.show();
			*/
		}
	}
	
	function chgImBarMode(full)
	{
		if(full) $('#im_bar').addClass('im_bar_full');
		else $('#im_bar').removeClass('im_bar_full');
	}
	
	function showTray(msg)
	{
		var msgs = msg.m ? msg.m : 0;
		var friends = msg.f ? msg.f : 0;
		var online_friends = msg.of ? msg.of : 0;
		var omc = msg.omc ? msg.omc: 0;
		
		$(doc).ready(function(){
			if(typeof(im_wdkUtil) != 'undefined')
			{
				var og = im_wdkUtil.viewRect.get;
				im_wdkUtil.viewRect.get = function(e, b)
				{
					var r = og.call(im_wdkUtil.viewRect, e, b);
					if(e == window && b) r.h -= (ie6?16:33);
					return r;
				};
			}
			var svc_info = ImGetSCookie("svc");
			if (svc_info && svc_info.ocu)
				$('#WebIM_xiaoneng').css('bottom', '30px');
		});
		
		//$('#im_bar .im_btn[t="#im_friend"] span:nth-child(1)').text(online_friends);
		//$('#im_bar .im_btn[t="#im_friend"] span:nth-child(2)').text(friends);
		
		//customTray(msg);
		
		if(im_switch) $('#im_bar').css('display', 'block');
		if(omc != undefined && omc != 0)
		{
			omc = omc > 99 ? '···' : omc;
			chat_btn.changeNumber(omc);
			chat_btn.showNumber();
		}	
		else
		{
			chat_btn.changeNumber(0);
			chat_btn.hideNumber();
		}
		if(first_pop_msg)
		{
			pop_content(first_pop_msg);
			first_pop_msg = null;
		}
	}
	
	var include_nps_pay = false;
	var service_flag_arr = ['A', 'B', 'C', 'D', 'E', 'F'];
	var service_name_arr = ['体验版', '标准版', '尊贵版', '尊贵版', '尊贵版', '尊贵版'];
	var service_realname_arr = ['体验版', '标准版', '尊贵版1个月', '尊贵版3个月', '尊贵版6个月', '尊贵版12个月'];
	var pay_arr = ['', '开通', '续费', '升级'];
	var old_service_url = {
		40: {url: im_root_url + 'usercp/service/bmsg_tg2.php', name: "diamond", src_key: "db-wdfw-zs"},
		2:  {url: im_root_url + 'usercp/service/upgrade.php', name: "vip", src_key: "db-wdfw-vip"},
		33: {url: im_root_url + 'usercp/service/im.php', name: "chat", src_key: "db-wdfw-lt"},
		38: {url: im_root_url + 'usercp/service/bmsg.php', name: "readmonth", src_key: "db-wdfw-kx"},
		4:  {url: im_root_url + 'usercp/brightlist/index.php', name: "brightlist", src_key: "db-wdfw-gmb"},
		5:  {url: im_root_url + 'usercp/service/priority.php', name: "priority", src_key: "db-wdfw-pmtq"},
		41: {url: im_root_url + 'usercp/service/bfmsg.php', name: "sendmonth", src_key: "db-wdfw-fx"},
		100:{url: im_root_url + 'usercp/fate_express.php', name: "express", src_key: "db-wdfw-jgd"}
	};
	
	function closeTray()
	{
		$('#im_bar').hide();
		$('#im_new_bar').hide();
	}
	
	var showServiceTimer = null;
	function afterCustomTray(fun)
	{
		var mod;
		var _url = document.location.host + document.location.pathname;
		/*
		_url = _url.replace('http://','');
		var _url_arr = _url.split('/');
		mod	= _url_arr[1];
		var _dom_arr	=	_url_arr[0].split('.');
		if(_dom_arr[0]	!=	'www')
		{
			mod	= _dom_arr[0];
		}
		*/
		var show_url_arr = [
			'www.jiayuan.com/usercp/',
			'www.jiayuan.com/usercp',
			'usercp.jiayuan.com',
			'usercp.jiayuan.com/',
			'jiayuan.msn.com.cn/usercp/',
			'jiayuan.msn.com.cn/usercp',
			'www.miuu.cn/usercp/',
			'usercp.miuu.cn/'
		];
		if ($.inArray(_url, show_url_arr) == -1)
			return;
		
		if (showServiceTimer)
		{
			window.clearTimeout(showServiceTimer);
			showServiceTimer = null;
		}
		showServiceTimer = window.setTimeout(function(){fun(true);}, 1000);
	}
	
	function hideList()
	{
		var o = $('.im_btn_cur');
		if(o.length > 0)
		{
			var t = $(o.attr('t'));
			t.hide();
			o.removeClass('im_btn_cur');
			$(".im_inner_btn", o).removeClass("active_btn");
			return true;
		}
		return false;
	}

	//闪动标题
	var startFlashTitle;
	var stopFlashTitle;
	(function()
	{
		var flash_step = 0;
		var raw_title = document.title;
		var tray_flash_timer = 0;
		startFlashTitle = function(tonick)
			{
				if(tray_flash_timer) window.clearInterval(tray_flash_timer);
				tray_flash_timer = window.setInterval(function()
					{
						document.title = (flash_step?'【　　　】 ':'【新消息】 ') + raw_title; 
						flash_step = 1 - flash_step;
					}, 500);
			};
		
		stopFlashTitle = function()
			{
				if(tray_flash_timer)
				{
					window.clearInterval(tray_flash_timer);
					tray_flash_timer = 0;
					document.title = raw_title;
					flash_step = 0;
				}
			};
	})();
	var onlineListUrl = (function()
	{
		var domain = document.location.host;
		if(domain == 'msn.jiayuan.com')
			return 'http://msn.jiayuan.com/webim/';
		if(domain == 'jiayuan.msn.com.cn')
			return 'http://jiayuan.msn.com.cn/webim/';
		if(domain == 'msn.miuu.cn')
			return 'http://msn.miuu.cn/webim/';
		if(/(^|\.)jiayuan\.com$/.test(domain))
			return "http://webim.jiayuan.com/";
		if(/(^|\.)miuu\.cn$/.test(domain))
			return "http://webim.miuu.cn/";
		return "http://"+domain + "/";
	})();

	function getAjaxUrl(url)
	{
		return im_webim_base + url + "&ver=3.0";
	}
	
	
	function loadBtnRecChat()
	{
		var sendUrl = getAjaxUrl("ajax.php?svc=getRecList&count=3&rand=" + Math.random());
		$.ajax({
			type: "GET",
			url: sendUrl,
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(ret){
				if (!$.isArray(ret))
					return;
				var len = ret.length > 3 ? 3 : ret.length;
				for(var i = 0; i < len; i++)
				{
					$('.im_rec_chat').eq(i).attr('recuid',  ret[i].uid);
					$('.im_rec_chat img').eq(i).attr('src', ret[i].avatar).attr('title', ret[i].nickname);
				}
			}
		});
		$('#im_rec_chat_con').css('display', 'block');
	}
	
	//插消息到聊天盒子
	function insertTOChatList(tonick, touid, num)
	{	
		var list = $("#im_chatx .im_list ");
		if(tonick == "") 
		{
			var div = $('div[touid]', list);
			if(div.length == 0) 
			{
				loadBtnRecChat();
			}
			
			return ;
		}
		
		var div = $('div[touid=' + touid + ']', list);
		if(typeof(num) == 'undefined')
			num = div.length ? (parseInt(div.attr('unseen_msg_num', num)) + 1) : 1;
		
		div.length ? div.remove() : $('div[touid]', list).eq(2).remove();
		touid = parseInt(touid);
		var prifileUrl = im_root_url+(1000000+touid)+"?fxly=im-bar-lt";
		var msgCenterUrl = im_webim_base + "messages.php";
		var html = '<a target="_blank" href="' + prifileUrl + '">' + tonick + '</a>给你发了' + num + '条聊天消息';
		html = html + "<span><a class=\"im_tosee_chatmsg\" href='javascript:void(0)'>查看消息</a>　<a target=\"_blank\" href=\"" + msgCenterUrl + "\">消息中心</a></span>";
		
		var m = $('<div class="im_mi">'+html+'</div>');
		m.attr('touid', touid);
		m.attr('unseen_msg_num', num);
		$('.im_tosee_chatmsg', m).attr('touid', touid);
	
		$('#im_rec_chat_con').css('display', 'none');
		list.prepend(m);
	}

	function setImSCookie(of, f, omc)
	{
		var json = ImGetCookie('IM_S');
		if(json)
		{
			var msg = json_util.decode(json);
		
			if(of != -1)
			{
				msg.of = of;
			}
			if(f == -2) 
			{
				if(msg.of > 20)
					msg.of = 20;
			}
			else if(f != -1)
			{
				msg.f = f;
			}
			if(omc != -1 && omc != undefined)
				msg.omc = omc;
			
			ImSetCookie('IM_S', json_util.encode(msg));
		}
	}

	function showUnseenChatMsgNum(num, master)
	{
		var raw_num;
		if(num == -1)
		{	
			var json = ImGetCookie('IM_S');
			if(json)
			{
				var msg = json_util.decode(json);
				raw_num = msg.omc;
				if(isNaN(raw_num)) raw_num = 0;
				if(master)
				{
					++raw_num;
					setImSCookie(-1, -1, raw_num);
				}
				chat_btn.changeNumber(raw_num <= 99 ? raw_num : '...');
				chat_btn.showNumber();
			}
		}
		else
		{
			raw_num = num;
			if(master) setImSCookie(-1, -1, raw_num);
			chat_btn.changeNumber(raw_num <= 99 ? raw_num : '...');
			raw_num > 0 ? chat_btn.showNumber() : chat_btn.hideNumber();
		}		
	}
	
	function removeFromChatBox(touid)
	{
		var list = $("#im_chatx .im_list");
		var old_item = $('div[touid="'+touid+'"]', list);
		old_item.slice(0).remove();
	}

	//播放声音
	var music = CreateAudio(im_image_base+"chat/msg1");
	function trayPlaySound(master)
	{
		if (!master) return;			
		music.stop();
		music.play();
	}
	
	//显示托盘
	function showChatPop(msg)
	{
		var touid = parseInt(msg.from);
		var new_msg = msg.chatmsg;
		var tonick = msg.fromnick;
		var msgtype = msg.msgtype ? parseInt(msg.msgtype) : 0;
		switch(msgtype)
		{
			case 0:
			case 1:
			case 10:
			case 14:
				break;
			case 2:
				new_msg = '[笑话]' + new_msg;
				break;
			case 4:
				new_msg = '[你猜我画发自佳缘佳信]';
				break;
			case 5:
				new_msg = '[问答]' + new_msg;
				break;
			case 6:
				new_msg = '[图片发自佳缘佳信]';
				break;
			case 7:
				new_msg = '[视频留言发自佳缘佳信]';
				break;
			default:
				return false;
		}
		function ishanzi(c)
		{
			return /^[\x00-\xff]/.test(c);
		}

		var i = 0;
		var len = 0;
		for(;i < new_msg.length; i++)
		{
			if(ishanzi(new_msg[i]))
				len  = len + 6;
			else
				len = len + 12;
			if(len >= 320)
				break;
		}
		
		new_msg = len > 320 ? new_msg.substr(0, i) + '······' :new_msg.substr(0, i);
		var item = $('.im_chat_win_content > div');
		var e = $('span', item);
		$('span', item).eq(0).text(new_msg);
		$('.im_chat_win_nick').text(tonick + '说:');
		$('.im_chat_win_nick').attr('tonick' , tonick);
		
		//查看详情
		$('.im_tosee_chatmsg',item).removeAttr('href').attr('touid', touid).text('开始聊天');
		
		closeAllPop();
		var chat_pop = $('#im_chat_pop_win');
		//chat_pop.animate({"marginBottom": "+=198px"}, 1000, "linear",function(){});
		chat_pop.slideDown(1000, function(){chat_pop.css("height", "126px");chat_pop.show();});
		chat_pop.show();
		return true;
	}
	
	function showOffmsgPop(offmsgCount)
	{
		var pop = $('#im_pop_win');
		if (pop.is(':visible')) return;
		
		var chat_pop = $('#im_chat_pop_win');
		$('.im_chat_win_nick', chat_pop).text('系统消息');
		var item = $('.im_chat_win_content > div');
		var msgCenterUrl = im_webim_base + "messages.php";
		$('a', item).attr('target', '_blank').attr('href', msgCenterUrl).css('margin-left', '120px').text('去消息中心查看').addClass('to_msg_center');
		$('span', item).eq(0).css('margin', '8px 10px 0px 12px').css('text-align', 'left');
		var msg = '您有' + offmsgCount + '条未读消息';
		$('span', item).eq(0).text(msg);

		if($('#im_friend').is(':visible')) return;
		closeAllPop();
		//chat_pop.animate({"marginBottom": "+=198px"}, 1000, "linear",
		chat_pop.slideDown(1000,
			function()
			{
				chat_pop.css("height", "126px");
				chat_pop.show();
			});
		$('.to_msg_center').click(function()
		{
			//$('#im_chat_pop_win').animate({"marginBottom": "-=198px"}, 1000, "linear", 
			$('#im_chat_pop_win').slideUp(1000,
				function(){
					$(this).hide();
					window.setTimeout(show_jiaxin_pop, 100)
				});
		});
	}

	
	function showPop(htm, i, type, pv)
	{
		if(type != 101 && $('#im_friend').is(':visible')) return false;
		var pop = $('#im_pop_win');
		var cont = $('.im_pop_content:nth-child('+(i+1)+')', pop);
		var now = (new Date()).getTime();
		if(now - last_pop_time < 60000 && type != 101 && pop.is(':visible')) //recv mail
		{
			var cur_type = parseInt('0'+cont.attr('type'), 10);
			if(cur_type == 101) return false;
		}
		last_pop_time = now;
		
		hideList();
		$('#im_chat_pop').hide();
		cont.attr('type', type);
		
		if(!pop_visible || i==1)
		{
			$('.im_t_tab_cur').removeClass('im_t_tab_cur');
			$('.im_pop_content_cur').removeClass('im_pop_content_cur');
			$('.im_t_tab:nth-child('+i+')', pop).addClass('im_t_tab_cur');
			cont.addClass('im_pop_content_cur');
		}
		cont.html(htm);
		
		if(i == 2 && type == 0)
			return false;
		
		if(typeof(pv) != 'undefined')
		{
			$('<img>',{'src': getPvLink() + pv, 'width':'0', 'height':'0'}).appendTo(cont);
		}

		closeAllPop();
		//pop.animate({"marginBottom": "+=198px"}, 1000, "linear",function(){});
		//pop.show();
		pop.slideDown(1000, function(){pop.css("height", "186px");pop.show();});
		pop_visible = true;
		return true;
	}
	
	function show_ms_pop(jsonobj, pv)
	{
		var siteUrl = im_root_url;
		var uid = parseInt(jsonobj["uid"]);
		var disp_uid = uid + 1000000;
		var profileNickname = jsonobj["nick"];
		profileNickname = convertSpecailChar(profileNickname);
		if (chn_strlen(profileNickname) > 12)
			profileNickname = chn_substr(profileNickname, 10) + '...';
		var tpl = jsonobj["tpl"];

		var profileUrl = im_profile_url + disp_uid + "?m_type=11&chat=1&ol=1&ddp=6&fxly=cp-yfms&flt=qlcylook";
		var chatUrl = siteUrl + "webchat/pay.php?uid=" + disp_uid + "&flt=qlcychat";
		
		var ms_div;
		if (false)
		{
			var summer_url = siteUrl + "webim/client/app/summerkill/?from_src=im_ms_pop";
			ms_div = $('<div><div><a href="#" target="_blank" class="im_yfms_nick"></a>使用<a target="_blank" href="'+summer_url+'" class="im_yfms_a1">佳信免费秒杀</a>对你说:</div><div class="im_yfms_text"><div></div></div><p><a href="#" target="_blank" class="im_yfms_chat">邀请TA聊天</a><a href="#" target="_blank" class="im_yfms_profile">查看TA的资料</a></p><p><a target="_blank" href="' + summer_url + '" class="im_yfms_a2">获得免费缘分秒杀</a></p><div id="im_yfms_pv" style="display:none"></div></div>');
		}
		else
			ms_div = $('<div><div><a href="#" target="_blank" class="im_yfms_nick"></a>使用<a target="_blank" href="'+broadcast_url+'" class="im_yfms_a1">缘分秒杀</a>对你说:</div><div class="im_yfms_text"><div></div></div><p><a href="#" target="_blank" class="im_yfms_chat">邀请TA聊天</a><a href="#" target="_blank" class="im_yfms_profile">查看TA的资料</a></p><p><a target="_blank" href="' + broadcast_url + '" class="im_yfms_a2">我也要玩缘分秒杀</a></p><div id="im_yfms_pv" style="display:none"></div></div>');
		$(".im_yfms_nick", ms_div).attr("href", profileUrl).html(profileNickname);
		$(".im_yfms_profile", ms_div).attr("href", profileUrl);
		$(".im_yfms_chat", ms_div).attr("href", chatUrl);
		$(".im_yfms_text div", ms_div).text(jsonobj["msg"]);
		$('.im_yfms_text div', ms_div).scrollTop(0);
		if (pv)
			$("#im_yfms_pv", ms_div).html(pv);
		
		closeAllPop();
		var yfms_tpls = ["im_yfms_tpl0", "im_yfms_tpl1", "im_yfms_tpl2"];
		var im_yfms_tpl;
		if ((tpl <= yfms_tpls.length) && (tpl > 0))
			im_yfms_tpl = yfms_tpls[tpl - 1];
		else
			im_yfms_tpl = yfms_tpls[0];
		
		var pop = $("#im_yfms");
		pop.empty();
		pop.append(ms_div);
		pop.attr("class", im_yfms_tpl);
		//pop.animate({"marginBottom": "+=198px"}, 1000, "linear",function(){});
		pop.slideDown(1000);
		pop.show();
		pop_visible = true;
		
		$('.im_yfms_text div', ms_div).hover(
			function(){$(this).animate({scrollTop : this.scrollHeight-$(this).height()}, $(this).text().length * 70);},
			function(){$(this).stop();});
		return true;
	}
	
	function chn_strlen(str)
	{
		var i = 0;
		var len = 0;
		for (i=0;i<str.length;i++)  
		{
			if (str.charCodeAt(i)>255) len+=2; else len++;
		}
		return len;
	}
	
	// 获取长度不大于len的子串，一个汉字算2个长度
	function chn_substr(str, len)
	{
		var ret = '';
		var i = 0;
		var k = 0;
		while(k < len)
		{
			if (str.charCodeAt(i) > 255)
				k = k + 2;
			else
				k++;

			if (k <= len)
				ret = ret + str.charAt(i);
			else
				break;
			
			i++;
		}
		return ret;
	}
	
	function convertSpecailChar(str)
	{
		str = str.replace(/&quot;/g, '"');
		str = str.replace(/&amp;/g, '&');
		str = str.replace(/&lt;/g, '<');
		str = str.replace(/&gt;/g, '>');
		str = str.replace(/&nbsp;/g, ' ');
		return str;
	}
	
	var zhuanti_pop_timer = null;
	function show_sub5to1_pop(type, pv)
	{
		var html = "";
		var zt_url = im_root_url + "parties/2012/msg5to1/?src_key=im_pop_" + type;
		if (type == 247)
			html += '<div class="v4LayerNew"><div class="v4LayerNewTop"><a class="v4LayerNewClose"></a></div><div class="v4LayerNewBody"><a href="' + zt_url + '" target="_blank" class="v4LayerNewMore"></a></div></div>';
		else if (type == 246)
			html += '<div class="v4LayerZhudong"><div class="v4LayerNewTop"><a class="v4LayerNewClose"></a></div><div class="v4LayerNewBody"><a href="' + zt_url + '" target="_blank" class="v4LayerNewMore"></a></div></div>';
		if (pv)
			html += pv;
		
		closeAllPop();
		var pop = $("#im_zhuanti");
		pop.empty();
		pop.html(html);
		//pop.animate({"marginBottom": "+=265px"}, 1000, "linear",
		pop.slideDown(1000,
			function(){
				if (zhuanti_pop_timer != null)
				{
					window.clearTimeout(zhuanti_pop_timer);
					zhuanti_pop_timer = null;
				}
				zhuanti_pop_timer = window.setTimeout(function(){$(".v4LayerNewClose").trigger("click");}, 30000);
				pop.css("height", "215px");
				pop.show();
			});
		pop.show();
		pop_visible = true;
		return true;
	}
	
	function show_profile_pop(is_diamond)
	{
		if (!is_profile) return;
		if ($('#im_pop_win').is(':visible') || $('#im_chat_pop_win').is(':visible') || $('#im_myjy').is(':visible')) return;
		
		if (typeof(is_link) == 'undefined' || typeof(is_online) == 'undefined') return;
		if (!is_link && !is_diamond) return;
		if (myuid == uid_disp) return;
		var svc_info = ImGetSCookie("svc");
		if (typeof(svc_info) == 'undefined' || typeof(svc_info.ppc) == 'undefined') return;

		var close_time = parseInt(svc_info.ppc);
		if (close_time != 0)
		{
			var t = new Date(close_time*1000);
			var tm1 = t.getMonth() + '-' + t.getDate();
			var now = new Date();
			var tm2 = now.getMonth() + '-' + now.getDate();
			if (tm1 == tm2)  return;
		}

		is_link = is_link ? 1 : 0;
		is_diamond = is_diamond ? 1 : 0;
		is_online = is_online ? 1 : 0;
		var online_tip = [['心动吗？打个招呼吧', '留言吧，给TA一个上线惊喜'],
						  ['对方在线，有什么想说的吗？', '对方在线，和TA聊聊吧']];
		closeAllPop();
		var profile_pop = $("#im_profile_pop_win");
		$('.dzhLayer_text p', profile_pop).text(online_tip[is_online][is_link]);
		if (is_link)
		{
			$('.tishilayer_hi', profile_pop).text('Hi，好久不见，还好吗？');
			$('.tishilayer_face', profile_pop).text('又见面了，有时间聊聊吗？');
			$('.tishilayer_flower', profile_pop).text('今天过的怎样？希望你每天都开心').addClass('tishilayer_flower2');
			$('.tishilayer_xin', profile_pop).text('每次看到你，心都砰砰跳');
		}
		else if (is_diamond)
		{
			$('.tishilayer_hi', profile_pop).text('Hi，我对你感觉很好，希望能认识…').addClass('tishilayer_hi2');
			$('.tishilayer_face', profile_pop).text('有时间聊聊吗？');
			$('.tishilayer_flower', profile_pop).text('想了解你更多，可以聊聊吗？');
			$('.tishilayer_xin', profile_pop).text('你的资料让我怦然心动，可以认识一下吗？').addClass('tishilayer_xin2');
		}
		
		reportPV('im_show_profile_pop|' + mysex + '|' + myuid);
		profile_pop.slideDown(1000,
			function(){
				profile_pop.css("height", "111px").css("overflow", "visible");
				profile_pop.show();
			});
		profile_pop.show();
		pop_visible = true;
		return true;
	}

	var has_show_jxpop = false;
	function show_jiaxin_pop()
	{
		if (has_show_jxpop || is_profile) return;
		if ($('#im_pop_win').is(':visible') || $('#im_chat_pop_win').is(':visible') || $('#im_myjy').is(':visible') || $('#im_jiaxin_pop_win').is(':visible')) return;
		var match_url = 
		[
			/^http\:\/\/usercp\.jiayuan\.com\/?([\?\#].*)?$/,
			/^http\:\/\/www\.jiayuan\.com\/usercp\/?([\?\#].*)?$/,
			/^http\:\/\/www\.jiayuan\.com\/msg([\/\?\#].*)?$/,
			/^http\:\/\/msg\.jiayuan\.com([\/\?\#].*)?$/,
			/^http\:\/\/search\.jiayuan\.com([\/\?\#].*)?$/,
			/^http\:\/\/photo\.jiayuan\.com([\/\?\#].*)?$/,
			/^http\:\/\/www\.jiayuan\.com\/usercp\/clicked_new\.php([\?\#].*)?$/,
			/^http\:\/\/www\.jiayuan\.com\/usercp\/photo\.php([\?\#].*)?$/,
			/^http\:\/\/www\.jiayuan\.com\/usercp\/friends\.php([\?\#].*)?$/,
			/^http\:\/\/www\.jiayuan\.com\/usercp\/maillink\.php([\?\#].*)?$/
		];
		var match = false;
		for(var i in match_url)
		{
			if (match_url[i].test(document.location.href))
			{
				match = true;
				break;
			}
		}
		if (!match) return;

		var svc_info = ImGetSCookie("svc");
		if (typeof(svc_info) == 'undefined' || typeof(svc_info.jpc) == 'undefined') return;

		// new user not pop?
		var regt = svc_info.regt;
		if (regt)
		{
			var now = new Date();
			var tc = now.getTime() - regt*1000;
			if (tc < 7*86400000) return;
		}

		var using = svc_info.using;
		var svc_arr = using.split(',');

		var close_time = parseInt(svc_info.jpc);
		if (close_time != 0)
		{
			var t = new Date(close_time*1000);
			var tm1 = t.getMonth() + '-' + t.getDate();
			var now = new Date();
			var tm2 = now.getMonth() + '-' + now.getDate();
			if (tm1 == tm2)  return;
		}

		var pclog_obj = ImGetMergedCookie('pclog');
		if (!pclog_obj) pclog_obj = {};
		if (!pcClient)
		{
			if (pclog_obj && pclog_obj[myuid])
			{
				var pclog_arr = pclog_obj[myuid].split('|');
				if (pclog_arr[2] == 0)
				{
					if (pclog_arr[3] == undefined) pclog_arr[3] = '0,0';
					var jx_pop = pclog_arr[3].split(',');
					jx_pop[0] = parseInt(jx_pop[0]) ? parseInt(jx_pop[0]) : 0;
					jx_pop[1] = parseInt(jx_pop[1]) ? parseInt(jx_pop[1]) : 0;
					var poped = false;
					var cur_time = new Date();
					if ($.inArray('40', svc_arr) != -1)
					{
						do_jiaxin_pop();
						poped = true;
					}
					else if (($.inArray('41', svc_arr) != -1) || ($.inArray('38', svc_arr) != -1) || ($.inArray('2', svc_arr) != -1))
					{
						var t1 = new Date(jx_pop[0]);
						var tm1 = t1.getMonth() + '-' + t1.getDate();
						var tm2 = cur_time.getMonth() + '-' + cur_time.getDate();
						if (tm1!=tm2) jx_pop[1] = 0;
						if (jx_pop[1] < 3 && cur_time.getTime() - jx_pop[0] >= 3600000)
						{
							do_jiaxin_pop();
							poped = true;
						}
					}
					else
					{
						do_jiaxin_pop();
						poped = true;
					}
					if (poped)
					{
						jx_pop[1] = jx_pop[1] + 1;
						pclog_arr[3] = cur_time.getTime()+','+jx_pop[1];
						pclog_obj[myuid] = pclog_arr.join('|');
						ImSetCookie_v2('pclog', json_util.encode(pclog_obj), today_remain_time(cur_time.getTime()));
					}
				}
			}
		}
	}

	function do_jiaxin_pop()
	{
		closeAllPop();
		var jiaxin_pop = $("#im_jiaxin_pop_win");

		var svc_info = ImGetSCookie("svc");
		var using = svc_info.using;
		var svc_arr = using.split(',');
		var is_read = $.inArray('38', svc_arr) != -1;
		var is_send = $.inArray('41', svc_arr) != -1;
		var is_vip = $.inArray('2', svc_arr) != -1;
		var is_diamond = $.inArray('40', svc_arr) != -1;
		var pop_cls = "";
		if (is_diamond){
			var cls_arr = ['jx3_2_1_adv1','jx3_2_1_adv2','jx3_2_1_adv3','jx3_2_1_adv4','jx3_2_1_adv5','jx3_2_1_adv6','jx3_2_1_adv7','jx3_2_1_adv8'];
			var idx = Math.floor(Math.random()*cls_arr.length + 1) - 1;
			pop_cls = cls_arr[idx];
		}
		else if (is_read){
			pop_cls = "jx3_2_1_adv4";
		}
		else if (is_send){
			pop_cls = "jx3_2_1_adv5";
		}
		else if (is_vip){
			pop_cls = "jx3_2_1_adv7";
		}
		else{
			var cls_arr = ['jx3_2_1_adv1','jx3_2_1_adv2','jx3_2_1_adv3','jx3_2_1_adv4','jx3_2_1_adv5','jx3_2_1_adv6','jx3_2_1_adv7'];
			var idx = Math.floor(Math.random()*cls_arr.length + 1) - 1;
			pop_cls = cls_arr[idx];
		}
		$('.jx3_0_1_adv', jiaxin_pop).attr('class', 'jx3_0_1_adv ' + pop_cls);
		reportPV('im_show_jiaxin_pop|' + mysex + '|' + myuid);
		jiaxin_pop.slideDown(1000,
			function(){
				jiaxin_pop.css("height", "91px");
				jiaxin_pop.show();
			});
		jiaxin_pop.show();
		has_show_jxpop = true;
		pop_visible = true;
		return true;
	}
	
	function closeAllPop()
	{
		hideList();
		var pop = $('#im_pop_win');
		pop.stop();
		pop.hide().css('margin-bottom', '0px');
		var chat_pop = $('#im_chat_pop_win');
		chat_pop.stop();
		chat_pop.hide().css('margin-bottom', '0px');
		var ms_pop = $('#im_yfms');
		ms_pop.stop();
		ms_pop.hide().css('margin-bottom', '0px');
		var zt_pop = $('#im_zhuanti');
		zt_pop.stop();
		zt_pop.hide().css('margin-bottom', '0px');
		var profile_pop = $('#im_profile_pop_win');
		profile_pop.stop();
		profile_pop.hide().css('margin-bottom', '0px');
		var jiaxin_pop = $('#im_jiaxin_pop_win');
		jiaxin_pop.stop();
		jiaxin_pop.hide().css('margin-bottom', '0px');
		chgImBarMode(true);
	}
	
	function addFriend(uid, nick, avatar, online, recommend)
	{
		var siteUrl = im_root_url;
		var disp_uid = uid + 1000000;
		var pofile_url = im_profile_url + disp_uid + '?fxly=cp-lxr-thy';

		var chatUrl = siteUrl + "webchat/pay.php?uid=" + disp_uid;
		var f = $('<div class=\"im_fi\"><a class=\"headLink fl\"><img /></a><div class="im_fi_n"><a class=\"nameLink fl\"/><div/></div><div class="im_fi_con"><a class="im_fi_m messageLink fr">写信</a><a target="_blank" href="' + chatUrl + '"  class="im_fi_c talkLink fr">聊天</a></div></div>');
		
		var img = $('img', f);
		img.attr('src', avatar);
		img.parent().attr('href', pofile_url);
		$('a', f).attr('hidefocus', 'true').attr('target', '_blank');
		
		f.attr('uid', uid);
		f.attr('online', online);
		// 是否是推荐的
		if (recommend)
			f.attr('rec', 1);
		
		$('.im_fi_n a', f).html(nick).attr('href', pofile_url);
		
		var uhash = hex_md5(''+uid);
		var url = im_root_url + "msg/send.php?uhash=" + uhash + "&fxly=cp-lxr-thy";
		$('.im_fi_m', f).attr('href', url);
		
		if(!online)
		{
			//img.grayscale();
			var e = $('#im_friend .im_fi[online="0"]');
			if(e.length) e.eq(0).before(f);
			else $('#im_friend .im_list').append(f);

			$('.im_fi_c', f).css('visibility', 'hidden');
		}
		else
		{
			f.addClass('im_f_on');
			$('.im_fi_c', f).css('visibility', 'visible');
			$('#im_friend .im_list').prepend(f);
		}
	}
	
	function refreshRecList()
	{
		var recUrl = getAjaxUrl("ajax.php?svc=get_one_city&rand=" + Math.random());
		$.ajax({
			type: "GET",
			url: recUrl,
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(ret){
				list_loading = false;
				list_loaded = true;
				$('#im_friend .im_loading').remove();
				$('#im_friend .im_list > *').remove();
				if (!$.isArray(ret))
					return;
			
				for(var j = 0; j < ret.length; j++)
					addFriend(ret[j].uid, ret[j].nickname, ret[j].avatar, 1, 1);
			},
			error: function(){
				list_loading = false;
			}
		});
	}
	
	$('.oneCity_content_title a.change').live('click', function(){
		refreshRecList();
	});
	
	//click pv
	$('#im_msgx a').live('click', function()
		{
			reportPV('pop_msg_list');
			report_old_PV(getPvLink() + 'sl=zd&click='+this.href+'&');
		});

	//点击聊天
	$('#im_pop_win a, #im_msgx a, #im_yfms a, #im_friend a, .im_fi > img').live('click', function(e)
	{
		var res = /\/webchat\/pay.php\?uid=(\d+)/.exec($(this).attr('href'));
		if(res)
		{
			e.preventDefault();
			var uid = parseInt(res[1], 10) - 1000000;
			OpenWebChat(uid);
		}
	});
	
	var pop_pv_key = ['zdchat', 'tjchat', 'newlook2', 'newlook', 'newchat2','qlcylook','qlcychat'];
	$('#im_pop_win a').live('click', function()
	{
		var url = this.href;
		var pvtag = 'pop_a';
		if (url.indexOf('zdlook10') != -1)
		{
			pvtag = 'pop_a_cstj';
		}
		else if (url.indexOf('zdlook') != -1)
		{
			if (url.indexOf('zdlook3') != -1)
				pvtag = 'pop_a_zdlook';
			else
				pvtag = 'pop_a_zdtj';
		}
		else if (url.indexOf('wap_clients') != -1)
		{
			pvtag = 'pop_a_download_client';
		}
		else
		{
			for(var i = 0; i < pop_pv_key.length; i++)
			{
				if (url.indexOf(pop_pv_key[i]) != -1)
				{
					pvtag = 'pop_a_' + pop_pv_key[i];
					break;
				}
			}
		}
		if (url.indexOf('fromphone') != -1)
			pvtag += '_phone';
		reportPV(pvtag);
		report_old_PV(getPvLink() + 'sl=zd&click='+this.href+'&newim');
		$('#im_pop_win .im_t_close').trigger('click');
	});
	
	$('#im_yfms a').live('click', function()
	{
		var url = this.href;
		var pvtag = '';
		if (url.indexOf('qlcylook') != -1)
			pvtag = 'pop_a_qlcylook';
		else if (url.indexOf('qlcychat') != -1)
			pvtag = 'pop_a_qlcychat';
		
		if (pvtag != '')
			reportPV(pvtag);
		report_old_PV(getPvLink() + 'sl=zd&click='+this.href+'&newim');
		closeAllPop();
	});

	window.startNewChat = OpenWebChat;
	
	$('#im_friend .im_fi_n a').live('click', function()
		{
			reportPV('pop_profile|' + mysex + '|' + myuid);
		});
	$('#im_friend .im_fi .headLink').live('click', function()
		{
			reportPV('onecity_avatar|' + mysex + '|' + myuid);
		});
	$('#im_bar .im_fi_m').live('click', function(e)
		{
			reportPV('pop_msg|' + mysex + '|' + myuid);
		});
	$('#im_bar .im_fi_c').live('click', function(e)
		{
			reportPV("onecity_chat|" + mysex + "|" + myuid);
		});
	
	//UI
	var msgCenterUrl = im_webim_base + "messages.php";
	var broadcast_url = im_root_url + "broadcast/";
	
	doc.write('<div id="im_bar"></div>');
	buildFootbar();
	//doc.write('<div id="im_bar" style="display:none" class="im_bar_min"><div class="im_btn im_btn_first" t="#im_msgx"><div class="im_btn_msgx"><span>消息</span><span class=\'im_msg_new\'>0</span></div></div><div class="im_btn_second im_btn" t="#im_chatx"><div class="im_btn_msgx"><span>聊天</span><span class=\'im_chat_msg_new\'>0</span></div></div><div class="im_btn_last im_btn" t="#im_friend"><div>好友(<span class="im_a_num">0</span>/<span>0</span>)</div></div><div id="im_msgx" class="im_win"><div class="im_title"><div class=\'im_t_n\'>最新消息</div><div class="im_t_close"></div></div><div class="im_list"><img class="im_jy_logo" src="http://images.jiayuan.com/w/global/i/logo_prompt.jpg" /></div></div><div id="im_chatx" class="im_win"><div class="im_title"><div class=\'im_t_n\'>聊天</div><a target="_blank" href="' + msgCenterUrl + '">查看全部聊天记录</a><div class="im_t_close"></div></div><div class="im_list"><div id="im_rec_chat_con"><span class=\'im_list_no_chat\'>暂无未读消息，与下列用户聊天或访问<a target="_blank" href="'+ onlineListUrl +'">在线列表</a>发起聊天</span><div class="im_rec_chat"><img width=80 height=100></img><span>和我聊天</span></div><div class="im_rec_chat"><img width=80 height=100></img><span>和我聊天</span></div><div class="im_rec_chat"><img width=80 height=100></img><span>和我聊天</span></div></div></div></div><div id="im_friend" class="im_win"><div class="im_title"><div class=\'im_t_n\'>好友</div><div class="im_t_close"></div></div><div class="im_list"><div class="im_loading"><img src="'+im_image_base+'loading.gif"></img><span>正在加载，请稍候</span></div></div><div class="im_bottom"></div></div><div id="im_pop_win" class="im_win"><div class="im_title"><div class=\'im_t_tab\'>互动消息</div><div class=\'im_t_tab\'>最新活动</div><div class="im_t_close"></div></div><div class="im_pop_content"><img src="http://images.jiayuan.com/w/global/i/logo_prompt.jpg" /></div><div class="im_pop_content"><img src="http://images.jiayuan.com/w/global/i/logo_prompt.jpg" /></div></div><div class="im_yfms_tpl0" id="im_yfms"><div><a href="#" target="_blank" class="im_yfms_nick"></a>使用<a target="_blank" href="'+broadcast_url+'" class="im_yfms_a1">缘分秒杀</a>对你说:</div><div class="im_yfms_text"><div></div></div><p><a href="#" target="_blank" class="im_yfms_chat">邀请TA聊天</a><a href="#" target="_blank" class="im_yfms_profile">查看TA的资料</a></p><p><a target="_blank" href="' + broadcast_url + '" class="im_yfms_a2">我也要玩缘分秒杀</a></p><div id="im_yfms_pv" style="display:none"></div></div><div id="im_chat_pop_win" class="im_win"><div class="im_chat_win_title"><div class="im_chat_win_nick"></div><div class="im_t_close"></div></div><div class="im_chat_win_content  im_pop_content"><div><span></span><span><a class="im_tosee_chatmsg">查看详情</a></span></div></div></div><div id="im_zhuanti"></div></div>');
	
	//start comet
	var jymsg = new JymsgClient();
	//var ring2 = new CreateAudio(im_image_base+'chat/msg2');
	
	//script
	$(doc).click(function(e)
	{
		if($(e.target).parents('#im_bar').size() == 0)
		{
			if(hideList()) chgImBarMode(false);
		}
	});
	//点击'查看消息'
	$('.im_tosee_chatmsg').live('click', function()
	{
		
		var touid = $(this).attr('touid');
		if(!touid)
			return;
		var ret = OpenWebChat(touid);
		//清楚闪动
		stopFlashTitle();
		$('#im_chat_pop_win').slideUp(1000, function(){ $(this).hide();window.setTimeout(show_jiaxin_pop, 100);});
	});

	$('.im_btn').hover(function(e){if (!$(this).hasClass("im_btn_cur") && !$(this).hasClass("not_hover")) $(this).hoverSwitch('im_btn_hover', e);});
	
	$('.im_inner_btn').live("click", function(evt)
	{
		if($('#im_bar :animated').length) return;
		$('#im_chat_pop_win').hide().css('margin-bottom', '0px');
		$('#im_pop_win').hide().css('margin-bottom', '0px');
		$('#im_yfms').hide().css('margin-bottom', '0px');
		$('#im_zhuanti').hide().css('margin-bottom', '0px');
		$('#im_profile_pop_win').hide().css('margin-bottom', '0px');
		$('#im_jiaxin_pop_win').hide().css('margin-bottom', '0px');
		pop_visible = false;
		
		var m = $(this).parent('.im_btn');
		var t = $(m.attr('t'));
		var o = $('.im_btn_cur');
		o.toggleClass('im_btn_cur');
		$(".im_inner_btn", o).toggleClass("active_btn");
		m.removeClass("im_btn_hover");
		
		if(o.get(0) != m[0])
		{
			$(o.attr('t')).toggle();
			m.toggleClass('im_btn_cur');
			$(".im_inner_btn", m).toggleClass("active_btn");
			chgImBarMode(true);
			t.slideDown(200);
			var p_id = $("li",m).attr('p_id');
			if($("li",m).attr('rec_t') == 'nopay'){
				view_rec(5,p_id,2);
			}else{
				view_rec(1,p_id,2);
			}
		}
		else
		{
			$(o.attr('t')).slideUp(200, function(){
				chgImBarMode(false);
				window.setTimeout(show_jiaxin_pop, 100);
			});
		}
	});
	
	$('.im_btn[t="#im_msgx"]').click(function()
		{
			//$('.im_msg_new').hide().text(0);
			msg_btn.changeNumber(0);
			msg_btn.hideNumber();
		});
	
	$('#im_msgx .im_t_close,#im_friend .im_t_close,#im_chatx .im_t_close, #im_chat_pop_win .im_t_close, #im_myjy .title_rightBg, #im_jyapp .title_rightBg, #im_mysvc .title_rightBg').live("click", function()
	{
		var o = $('.im_btn_cur');
		$(o.attr('t')).slideUp(200, function(){
			chgImBarMode(false);
			window.setTimeout(show_jiaxin_pop, 100);
		});
		o.toggleClass('im_btn_cur');
		$(".im_inner_btn", o).toggleClass("active_btn");
	});
	
	$('#im_pop_win .im_t_close').click(function()
	{
		//$('#im_pop_win').animate({"marginBottom": "-=198px"}, 1000, "linear", 
		$('#im_pop_win').slideUp(1000,
			function(){
				$(this).hide();
				chgImBarMode(false);
				pop_visible = false;
				window.setTimeout(show_jiaxin_pop, 100);
			});
	});
	$('#im_chat_pop_win .im_t_close').click(function(e)
		{
			//消息插入聊天盒子
			var body = $("#im_chat_pop_win");
			var nick_item = $('.im_chat_win_nick',body);
			var tonick = nick_item.attr('tonick');
			var touid = $('.im_tosee_chatmsg', body).attr('touid');
			
			//chat_loaded ? insertTOChatList(tonick, touid) : 1;
			
			//stop flashTitle
			stopFlashTitle();
			
			//$('#im_chat_pop_win').animate({"marginBottom": "-=198px"}, 1000, "linear", 
			$('#im_chat_pop_win').slideUp(1000,
				function(){
					$(this).hide();
					chgImBarMode(false);
					pop_visible = false;
					window.setTimeout(show_jiaxin_pop, 100);
				});
			$.ajax({
				type: "GET",
				url: getAjaxUrl('ajax.php?svc=closeChatPop'),
				dataType: "jsonp",
				jsonp: 'jsoncallback'
			});
		});
	$('#im_profile_pop_win .im_t_close').click(function()
	{
		$('#im_profile_pop_win').slideUp(1000,
			function(){
				$(this).hide();
				chgImBarMode(false);
				pop_visible = false;
			});
	});
	$('#im_jiaxin_pop_win .im_t_close, #im_jiaxin_pop_win .jx3_0_1_advButton').click(function()
	{
		$('#im_jiaxin_pop_win').slideUp(1000,
			function(){
				$(this).hide();
				chgImBarMode(false);
				pop_visible = false;
			});
		$.ajax({
			type: "GET",
			url: getAjaxUrl('ajax.php?svc=set_jpc'),
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(ret){
				if (ret.code == 0)
				{
					var svc_info = ImGetSCookie('svc');
					svc_info.jpc = ret.t;
					ImSetSCookie("svc", svc_info);
				}
			}
		});
	});

	$('.im_t_tab').click(function()
		{
			var m = $(this);
			var i = m.index();
			
			$('.im_t_tab_cur').removeClass('im_t_tab_cur');
			$('.im_pop_content_cur').removeClass('im_pop_content_cur');
			m.addClass('im_t_tab_cur');
			
			$('.im_pop_content:nth-child('+(i+2)+')').addClass('im_pop_content_cur');
		});
	var chat_loading = false;
	function onlineTalkClick()
	{
		if(chat_loading || chat_loaded) return;
		chat_loading = true;
		var total_num = 0;
		$.ajax({
			type: "GET",
			url: getAjaxUrl('ajax.php?svc=ureadRelations'),
			dataType: "jsonp",
			jsonp:'jsoncallback',
			success: function(cache){
				chat_loading = false;
				chat_loaded = true;
				
				var len = cache.length;
				if(len == 0)
				{
					loadBtnRecChat();
					showUnseenChatMsgNum(0, true);
					return;
				}
				
				for(var i = len -1 ; i >=0; i--)
				{
					var tonick = cache[i].nickname;
					var touid = cache[i].uid;
					var num = cache[i].unread;
					total_num = total_num + parseInt(num);
					insertTOChatList(tonick, touid, num);
				}
				
				if(len < 3)
				{
					showUnseenChatMsgNum(total_num, true);
				}
			}
		});
	}

	$('#im_rec_chat_con .im_rec_chat img, #im_rec_chat_con .im_rec_chat span').click(function(e)
	{
		var item = $(e.target).parents('.im_rec_chat');
		var touid = item.attr('recuid');
		if(OpenWebChat(touid) == -1) return ;

		var sendUrl = getAjaxUrl("ajax.php?svc=getRecList&count=1&rand=" + Math.random());
		$.ajax({
			type: "GET",
			url: sendUrl,
			dataType: "jsonp",
			jsonp:'jsoncallback',
			success: function(ret){
				if (!$.isArray(ret))
					return;
				if(ret.length>0)
				{
					item.attr('recuid',  ret[0].uid);
					item.find('img').attr('src', ret[0].avatar).attr('title', ret[0].nickname);
				}
			}
		});
	});
	var list_loading = false;
	function oneCityClick()
	{
		if(list_loaded || list_loading) //get friends list
			return;
		
		list_loading = true;
		refreshRecList();
	}
	
	var jyapp_loaded = false;
	var jyapp_loading = false;
	function jyappClick()
	{
		reportPV("im_jyapp_click|" + mysex + "|" + myuid);
		if (jyapp_loaded || jyapp_loading || $("#im_jyapp .im_list").children().length > 0) return;
		jyapp_loading = true;
		var sendUrl = getAjaxUrl("ajax.php?svc=get_app_num");
		$.ajax({
			type: "GET",
			url: sendUrl,
			dataType: "jsonp",
			jsonp:'jsoncallback',
			success: function(ret){
				jyapp_loading = false;
				jyapp_loaded = true;
				if (ret.code != 0)
					return;
				
				var items = ret.data.item;
				var nums = ret.data.num;
				for(var idx in items)
				{
					var item = items[idx];
					var has_num = false;
					var num_val = "";
					if (nums && $.isArray(nums) && nums.length > 0)
					{
						for(var j = 0; j < nums.length; j++)
						{
							if (nums[j].id == item.menu)
							{
								has_num = true;
								num_val = nums[j].val;
								break;
							}
						}
					}
					jyapp_popup.addItem(item.name, item.url, 'im_jyapp_' + item.menu, item.menu, has_num, item['class']);
					if (has_num)
						$('#im_jyapp_' + item.menu + ' strong').text("（新" + num_val + "）");
				}
			}
		});
	}
	
	function view_rec(type,pid,position){
		//1:显示 2:点击 
		var loc = ImGetCookie('myloc');
		if(loc.substr(0,2) == "44"){
			var sendUrl = getAjaxUrl('ajax.php?svc=get_rec_log&type='+type+'&pid='+pid + '&position='+position);
			$.ajax({
				type: "GET",
				url: sendUrl,
				dataType: "jsonp",
				jsonp:'jsoncallback',
				success: function(info){
					//console.log(info.msg);
				}
			});
		}
	}

	var myjy_loaded = false;
	var myjy_loading = false;
	function myjyClick()
	{
		reportPV("im_myjy_click|" + mysex + "|" + myuid);
		showMyjyInfo(false);
	}
	function showMyjyInfo(auto)
	{
		if(myjy_loaded || myjy_loading || $("#im_myjy .im_list").children().length > 0) return;
		myjy_loading = true;
		var sendUrl = getAjaxUrl('ajax.php?svc=get_service_info');
		$.ajax({
			type: "GET",
			url: sendUrl,
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(info){
				myjy_loaded = true;
				myjy_loading = false;
				//if (info.code != 1) return;
				
				var using = info.using;
				var rec = info.recomend;
				var html = $("<div></div>");
				var checkbox_arr = new Array();
				if (using && $.isArray(using) && using.length > 0)
				{
					var using_html = $('<h2>正在使用</h2><ul class="usingService"></ul>');
					var svc_list = $(using_html[1]);
					var i = 0;
					for(var key in using)
					{
						var svc = using[key];
						var svc_id = svc.id;
						var item = $('<li><span></span><strong></strong><a target="_blank">续费</a><div></div></li>');
						$('strong', item).text(svc.name);
						$('span', item).addClass("svc_icon_" + svc_id);

						if(svc_id == 100){
							if(svc.show_remain){
								$('div',item).html("剩余展示数:"+svc.remainnum);
							}else{
								$('div',item).html("您有"+svc.exp_count+"个订单正在展示中");
							}
						}else if (svc.day > 7){
							var expiry = svc.expiry;
							var idx = expiry.indexOf(" ");
							if (idx != -1)
								expiry = expiry.substr(0, idx);
							$('div', item).html("服务期至" + expiry);
						}else if (svc.day > 0){
							$('div', item).addClass("expire").html('还有' + svc.day + '天到期');
						}else{
							$('div', item).addClass("expire").html('服务已过期');
							$('span', item).removeClass("svc_icon_" + svc_id).addClass("svc_icon_" + svc_id + "_g");
						}
						if (svc.xufei != 1 || svc_id == 33 || svc_id == 4)
							$('a', item).remove();
						else
							$('a', item).attr("href", old_service_url[svc_id].url+'?src_key='+old_service_url[svc_id].src_key+'-xf').attr("tj", old_service_url[svc_id].name);
						if (i === 0)
							item.addClass("first");
						if (svc.hid != -1)
						{
							var cb_id = 'hide_svc' + key;
							var hide_svc = $('<div><input type="checkbox" name="hide_svc" class="hide_svc" id="' + cb_id + '" value="' + svc.hid + '"/><label for="' + cb_id + '">&nbsp;隐藏' + svc.name + '身份</label></div>');
							$('div', item).append(hide_svc);
							// 放在后面做checked勾选，为了兼容ie6下的问题
							checkbox_arr.push([cb_id, svc.hide == 1 ? true : false]);
						}
						svc_list.append(item);
						i++;
					}
					html.append(using_html);
				}
				else
					html.append($("<h2>当前没有使用任何服务</h2>"));
				
				if (rec && $.isArray(rec) && rec.length > 1)
				{
					html.append("<h2>" + rec[0] + "</h2>");
					html.append('<ul class="recommendService"><li><span></span><strong></strong><a target="_blank">续费</a><em></em></li></ul>');
					var rec_list = $(".recommendService", html);
					$("strong", rec_list).text(rec[1]);
					$("em", rec_list).text(rec[2]);
					var svc_id = rec[3];
					if(rec[4] == 'webim_rec_def') rec[4] = old_service_url[svc_id].src_key+'-sq';
					if (svc_id == 33)
						$("a", rec_list).remove();
					else
						$("a", rec_list).attr("href", old_service_url[svc_id].url+'?src_key='+rec[4]).attr("tj", old_service_url[svc_id].name);
					$('span', rec_list).addClass("svc_icon_" + svc_id);
					var rec_t = 2;
					if(rec[0] == "您曾对以下服务感兴趣"){
						$("li",rec_list).attr('rec_t','nopay');
						rec_t = 6;
					}
					$("li",rec_list).attr('p_id',svc_id);
					$("a",rec_list).click(function(){
						view_rec(rec_t,svc_id,2);
					});
				}
				html.append($('<a href="' + im_root_url + 'usercp/service/servicenew.php" target="_blank" class="moreService">更多服务&gt;&gt;</a>'));
				$("#im_myjy .im_list").append(html);
				for (var k in checkbox_arr)
				{
					var v = checkbox_arr[k];
					$("#" + v[0], html).attr("checked", v[1]);
				}
				
				if (auto && info.show)
				{
					reportPV("im_myjy_auto_pop|" + mysex + "|" + myuid);
					$("#im_myjy").show();
					$("#im_bar .myJiayuan").addClass("im_btn_cur");
					$("#im_bar .myJiayuan .im_inner_btn").addClass("active_btn");
					if(rec && $.isArray(rec) && rec[0]=='您曾对以下服务感兴趣'){
						view_rec(5,rec[3],2);					
					}else{
						view_rec(1,rec[3],2);
					}
				}
			}
		});
		return;
	}
	
	function inboxClick()
	{
		reportPV("im_inbox_click|" + mysex + "|" + myuid);
		inbox_btn.removeBtnClass("new");
		getUnreadMailCount();
		var _url = document.location.host + document.location.pathname;
		var reload_url_arr = [
			'msg.jiayuan.com/',
			'msg.jiayuan.com',
			'www.jiayuan.com/msg/',
			'www.jiayuan.com/msg',
			'jiayuan.msn.com.cn/msg',
			'jiayuan.msn.com.cn/msg/',
			'www.miuu.cn/msg/',
			'msg.miuu.cn/'
		];
		var reload = false;
		for (var i = 0; i < reload_url_arr.length; i++)
		{
			if (_url.indexOf(reload_url_arr[i]) != -1)
			{
				reload = true;
				break;
			}
		}
		if (reload)
			window.location = im_root_url + 'msg/';
		else
			window.open(im_root_url + 'msg/');
	}

	function robotClick()
	{
		reportPV("im_robot_click|" + mysex + "|" + myuid);
		window.open(im_root_url + 'usercp/robot/');
	}
	
	function getUnreadMailCount()
	{
		$.ajax({
			type: "GET",
			url: getAjaxUrl("ajax.php?svc=get_nps_info&onlymail=1"),
			dataType: "jsonp",
			jsonp:'jsoncallback',
			success: function(info){
				if (info.code != 0) return;
				if (parseInt(info.unread_count) > 0)
				{
					inbox_btn.changeNumber(info.unread_count);
					inbox_btn.showNumber();
				}
				else
				{
					inbox_btn.changeNumber(0);
					inbox_btn.hideNumber();
				}
				var svc_info = ImGetSCookie("svc");
				if (svc_info)
					svc_info.unread_count = info.unread_count;
				ImSetSCookie("svc", svc_info);
			}
		});
	}
	
	$(".usingService li a").live("click", function(){
		reportPV("im_view_using_service_" + $(this).attr("tj") + "|" + mysex + "|" + myuid);
	});
	
	$(".recommendService li a").live("click", function(){
		reportPV("im_view_rec_service_" + $(this).attr("tj") + "|" + mysex + "|" + myuid);
	});
	
	$("#im_myjy .moreService").live("click", function(){
		reportPV("im_more_old_service|" + mysex + "|" + myuid);
		var rec_ul = $("#im_myjy .recommendService li");
		if(rec_ul.attr('rec_t') == "nopay"){
			view_rec(6,rec_ul.attr('p_id'),3);
		}else{
			view_rec(2,rec_ul.attr('p_id'),3);
		}
	});
	
	$("#im_jyapp .im_item a").live("click", function(){
		reportPV("im_jyapp_item_" + $(this).attr("tj") + "|" + mysex + "|" + myuid);
	});
	
	$("#service_list li").live("click", function(){
		reportPV("im_service_item_" + $(this).attr("class") + "|" + mysex + "|" + myuid);
	});
	
	$("#im_bar .im_btn .amount").live("click", function(){
		var btn = $(this).prev(".im_inner_btn");
		if (btn.length > 0)
			btn.trigger("click");
	});
	
	$(".hide_svc").live("click", function(){
		reportPV("im_hide_service|" + mysex + "|" + myuid);
		var self = this;
		var checked = this.checked ? 1 : 0;
		var val = this.value;
		var sendUrl = getAjaxUrl("ajax.php?svc=hide_service&is_hide=" + checked + "&hide_service=" + val);
		$.ajax({
			type: "GET",
			url: sendUrl,
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(ret){
				if (ret == '-1')
				{
					alert("修改失败！");
					self.checked = !self.checked; 
				}
			}
		});
	});
	
	$('.im_chat_pop_v').click(function()
		{
			var uid = parseInt($(this).attr('uid'));
		});
		
	$('.im_chat_pop_c').click(function()
		{
			$(this).hide();
			chgImBarMode(false);
		});
	
	var service_loading = false;
	function npsMysvcClick(){
		reportPV("im_new_service");
		showServiceInfo();
	}
	
	function showServiceInfo(check){
		var cont = $(".service_current").children();
		if (cont.length > 0 || service_loading) return;
		service_loading = true;
		$.ajax({
			type: "GET",
			url: getAjaxUrl('ajax.php?svc=get_service_info'),
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(info){
				service_loading = false;
				if (info.code == -1)
					return;
				
				var newbar = $(".myService .im_list");
				// 加载使用中或过期的服务信息
				if (info.newsvc)
				{
					var sc = $(".service_current", newbar);
					var h = '<p><strong>正在使用：' + info.newsvc.name;
					if (info.newsvc.flag == 'C')
						h += '<em>1</em>个月';
					else if (info.newsvc.flag == 'D')
						h += '<em>3</em>个月';
					else if (info.newsvc.flag == 'E')
						h += '<em>6</em>个月';
					else if (info.newsvc.flag == 'F')
						h += '<em>12</em>个月';
					h += '</strong></p>';
					if (info.newsvc.flag == 'A' || info.newsvc.flag == 'B' || info.newsvc.flag == 'C')
						h += '<p>已用名额：<em>' + info.newsvc.used + '</em> &nbsp; &nbsp;剩余：<em>' + info.newsvc.balance + '</em></p>';
					else
						h += '<p>无限量免费看信、发信及聊天</p>';
					var expire = info.newsvc.expiry;
					var idx = expire.indexOf(" ");
					if (idx != -1)
						expire = expire.substr(0, idx);
					h += '<p>' + expire + '到期</p><div class="button b1" id="nps_buy_btn"><a href="#"></a></div>';
					sc.html(h);
					var opt = parseInt($(".myService").attr("rec_opt"));
					var btn = $("#nps_buy_btn", sc);
					btn.attr("opt", opt);
					$("a", btn).text(pay_arr[opt]);
					if (info.newsvc.flag == 'F')
						btn.remove();
					idx = $.inArray(info.newsvc.flag, service_flag_arr);
					$("#more_service", newbar).attr("href", im_root_url + 'usercp/service/new_service.php?src_key=im_more_service&on_nps=' + (idx + 1));
				}
				else if (info.oldsvc)
				{
					var sc = $(".service_current", newbar);
					var expire = info.oldsvc.expiry;
					var idx = expire.indexOf(" ");
					if (idx != -1)
						expire = expire.substr(0, idx);
					var h = '<p><strong>' + info.oldsvc.name + '会员已过期</strong></p><p>' + expire + '到期</p><p>您目前不能再享用：</p><div class="button b2" id="nps_buy_btn"><a href="#">续费</a></div>';
					sc.html(h);
					var btn = $("#nps_buy_btn", sc);
					btn.attr("opt", 2);
					if (info.oldsvc.flag == 'F')
						btn.remove();
					$('.myService').attr('rec_flag', info.oldsvc.flag);
					idx = $.inArray(info.oldsvc.flag, service_flag_arr);
					$("#more_service", newbar).attr("href", im_root_url + 'usercp/service/new_service.php?src_key=im_more_service&on_nps=' + (idx + 1));
				}
				else
				{
					var sc = $(".service_current", newbar);
					var recFlag = $(".myService").attr("rec_flag");
					var recPrice = $(".myService").attr("rec_price");
					var recOpt = parseInt($(".myService").attr("rec_opt"));
					var idx = $.inArray(recFlag, service_flag_arr);
					var h = '<p>推荐给您:</p>';
					h += '<p><strong>' + service_realname_arr[idx] + '&nbsp;￥' + recPrice;
					if (recFlag == "A" || recFlag == "B")
						h += '/月';
					h += '</strong></p><div class="button b2" id="nps_buy_btn"><a href="#"></a></div>';
					sc.html(h);
					var btn = $("#nps_buy_btn", sc);
					btn.attr("opt", recOpt);
					$("a", btn).text(pay_arr[recOpt]);
					if (recFlag == 'F')
						btn.remove();
					idx = $.inArray(recFlag, service_flag_arr);
					$("#more_service", newbar).attr("href", im_root_url + 'usercp/service/new_service.php?src_key=im_more_service&on_nps=' + (idx + 1));
				}
				
				//加载特权列表
				if (info.newsvc || info.oldsvc)
				{
					var prev_list = $("#prev_list", newbar);
					var flag = info.newsvc ? info.newsvc.flag : info.oldsvc.flag;
					var service_arr = [
						{name: "谁看过我", href: im_root_url + "usercp/clicked.php?src_key=myjy_lookedme", icon: "ico4", link: "查看", tj_key: "im_prev_look"},
						{name: "上传更多照片", href: im_root_url + "usercp/photo.php", icon: "ico2", link: "设置", tj_key: "im_prev_photo"},
						{name: "免费空间装扮", href: im_profile_url + myuid, icon: "ico3", link: "装扮", tj_key: "im_prev_space"},
						{name: "择偶要求筛选", href: im_root_url + "search", icon: "ico5", link: "设置", tj_key: "im_prev_match_req"},
						{name: "保存搜索条件", href: im_root_url + "search", icon: "ico6", link: "设置", tj_key: "im_prev_search_con"},
						{name: "查看异性最近登陆时间", href: "", icon: "ico9", link: "设置"},
						{name: "限定择偶条件", href: im_root_url + "usercp/condition.php", icon: "ico12", link: "设置", tj_key: "im_prev_match_con"},
						{name: "搜索突出显示", href: "", icon: "ico7", link: "设置"},
						{name: "发出信件置顶", href: "", icon: "ico11", link: "设置"},
						{name: "免费和异性聊天", href: im_root_url + "search/online.php", icon: "ico16", link: "聊天", tj_key: "im_prev_chat"}
					];
					var flag_svc_map = {
						A: [0, 1, 2, 3, 4, 7],
						B: [0, 5, 2, 1, 4, 7],
						C: [0, 5, 6, 4, 2, 8],
						D: [0, 9, 5, 6, 4, 8],
						E: [0, 9, 5, 6, 4, 8],
						F: [0, 9, 5, 6, 4, 8]
					};
					var mysvc = flag_svc_map[flag];
					for(var i = 0; i < mysvc.length; i++)
					{
						var h = $('<li><em></em></li>');
						var item = service_arr[mysvc[i]];
						var bg = ((i + 1) % 2 == 0) ? 2 : 1;
						h.addClass(item.icon);
						h.addClass("bg" + bg);
						$("em", h).text(item.name);
						if (item.href && info.newsvc)
						{
							var setlink = $('<span><a target="_blank"></a></span>');
							$("a", setlink).attr("href", item.href).text(item.link + ">>").attr("tj_key", item.tj_key);
							h.append(setlink);
							$("a", setlink).click(function(){
								var tj_key = $(this).attr("tj_key");
								tj_key += "_" + flag;
								reportPV(tj_key);
							});
						}
						prev_list.append(h);
					}
				}
				else
				{
					var prev_list = $("#prev_list", newbar);
					var flag = $(".myService").attr("rec_flag");
					var service_arr = [
						{name: "可免费联系", icon: "ico1"},
						{name: "查看谁看过我", icon: "ico4"},
						{name: "上传更多照片", icon: "ico2"},
						{name: "免费空间装扮", icon: "ico3"},
						{name: "择偶要求筛选", icon: "ico5"},
						{name: "保存搜索条件", icon: "ico6"},
						{name: "查看异性最近登陆时间", icon: "ico9"},
						{name: "限定择偶条件", icon: "ico12"},
						{name: "发出信件置顶", icon: "ico11"},
						{name: "免费和异性聊天", icon: "ico16"}
					];
					var flag_svc_map = {
						A: [0, 1, 2, 3, 4, 5],
						B: [0, 1, 6, 3, 2, 5],
						C: [0, 1, 6, 7, 5, 8],
						D: [0, 1, 9, 6, 7, 8],
						E: [0, 1, 9, 6, 7, 8],
						F: [0, 1, 9, 6, 7, 8]
					};
					var contact_count = {
						f: ['8位', '30位', '150位', '无限量', '无限量', '无限量'],
						m: ['8位', '30位', '300位', '无限量', '无限量', '无限量']
					};
					var mysvc = flag_svc_map[flag];
					var sex = mysex == 'm' ? "女士" : (mysex == 'f' ? "男士" : "异性");
					var idx = $.inArray(flag, service_flag_arr);
					for(var i = 0; i < mysvc.length; i++)
					{
						var h = $('<li><em></em></li>');
						var item = service_arr[mysvc[i]];
						var bg = ((i + 1) % 2 == 0) ? 2 : 1;
						h.addClass(item.icon);
						h.addClass("bg" + bg);
						if (mysvc[i] == 0)
						{
							var desc = item.name + sex + ":";
							if (contact_count[mysex] != undefined && contact_count[mysex][idx] != undefined)
								desc += contact_count[mysex][idx];
							$("em", h).text(desc);
						}
						else
							$("em", h).text(item.name);
						prev_list.append(h);
					}
				}
				
				if (check && info.show)
				{
					$(".myservice_panel").show();
					$(".myService").addClass("im_btn_cur");
					$(".myService .im_inner_btn").addClass("active_btn");
				}
			}
		});
	}
	
	$("#nps_buy_btn").live("click", function(){
		var flag = $('.myService').attr('rec_flag');
		var level_arr = [1, 2, 3, 4, 5, 6];
		var idx = $.inArray(flag, service_flag_arr);
		if (idx == -1) return;
		var level = level_arr[idx];
		var action = parseInt($('#nps_buy_btn').attr('opt'));
		var month = 1;
		if (level == 1 || level == 2)
			month = 3;
		nps_pay(action, level, month, '', 'im_newsvc');
	});
	
	$(".v4LayerNewClose").live("click", function(){
		window.clearTimeout(zhuanti_pop_timer);
		zhuanti_pop_timer = null;
		//$('#im_zhuanti').animate({"marginBottom": "-=265px"}, 1000, "linear", 
		$('#im_zhuanti').slideUp(1000,
			function(){
				$(this).hide();
				chgImBarMode(false);
				pop_visible = false;
			});
	});
	
	var contact_loaded = false;
	var contact_loading = false;
	function contactClick()
	{
		reportPV("im_contact_click|" + mysex + "|" + myuid);
		contact_btn.changeNumber(0);
		contact_btn.hideNumber();
		if (contact_loaded && !$('#im_friend').is(":visible"))
		{
			sortContacts($('#im_friend .im_list .webimList ul').eq(0));
			sortContacts($('#im_friend .im_list .webimList ul').eq(1));
			sortContacts($('#im_friend .im_list .webimList ul').eq(2));
		}
		if (contact_loading || contact_loaded) return;
		contact_loading = true;
		reportPV("im_load_contact|" + mysex + "|" + myuid);
		var sendUrl = getAjaxUrl("ajax.php?svc=get_relations");
		$.ajax({
			type: "GET",
			url: sendUrl,
			dataType: "jsonp",
			jsonp:'jsoncallback',
			success: function(ret){
				contact_loading = false;
				contact_loaded = true;
				if (ret.code != 0) return;
				
				var contacts = ret.contacts;
				var friends = ret.friends;
				var goodfriends = ret.goodfriends;
				var list = $('#im_friend .im_list .webimList');
				$('#im_friend .im_loading').hide();
				
				var anzhuang_tip = [
					'嘘…上班呢。有佳缘佳信，消息提醒最及时',
					'不方便上网页？有佳缘佳信，缘分不会少',
					'更便捷的聊天记录管理——佳缘佳信',
					'想知道TA的近况？佳缘佳信来帮你',
					'全屏查看大照片，要多爽有多爽',
					'用佳缘佳信发照片、聊视频，表达更真诚',
					'QQ被盗有风险，佳信聊天最方便',
					'聊天一键隐藏，再不担心被人看见',
					'佳缘佳信锁定功能，更安全保护你的隐私'
				];
				var tip_idx = parseInt((new Date()).getTime() / 1000) % anzhuang_tip.length;
				$('#im_friend .webimListAnzhuang a').attr('href', "http://im.jiayuan.com/client/channeldl.php?isstatistics=2&ch=315060&from=webim-1").attr('target', '_blank').text(anzhuang_tip[tip_idx]);
				if (GetPcClient())
				{
					$('#im_friend .webimListAnzhuang').hide();
					var h = list.height()+$('#im_friend .webimListAnzhuang').height();
					list.height(h);
				}
				else
				{
					$('#im_friend .webimListAnzhuang').show();
					reportPV("im_list_show_v3down|" + mysex + "|" + myuid);
				}
				var contact_ul = $('ul', list).eq(0);
				var friend_ul = $('ul', list).eq(1);
				var goodfriend_ul = $('ul', list).eq(2);
				var online_count = 0;
				for(var j = 0; j < contacts.length; j++)
				{
					addContact(contacts[j], contact_ul, 0, 0);
					if (contacts[j].platform != 0) online_count++;
				}
				$('#im_friend .webimZaixianRS span').eq(0).text(online_count);
				online_count = 0;
				for(var j = 0; j < friends.length; j++)
				{
					addContact(friends[j], friend_ul, 0, 0);
					if (friends[j].platform != 0) online_count++;
				}
				$('#im_friend .webimZaixianRS span').eq(1).text(online_count);
				online_count = 0;
				for(var j = 0; j < goodfriends.length; j++)
				{
					addContact(goodfriends[j], goodfriend_ul, 0, 0);
					if (goodfriends[j].platform != 0) online_count++;
				}
				$('#im_friend .webimZaixianRS span').eq(2).text(online_count);
				
				contact_ul.attr("have", ret.hasc).attr("online", contacts.length);
				friend_ul.attr("have", ret.hasf).attr("online", friends.length);
				goodfriend_ul.attr("have", ret.hasg).attr("online", goodfriends.length);
				if (goodfriends.length > 0)
					$('#im_friend .webimTop li').eq(2).trigger("click");
				else
					$('#im_friend .webimTop li').eq(0).trigger("click");
			}
		})
	}
	
	function addContact(contact, parent, insert, isnew)
	{
		var siteUrl = im_root_url;
		var disp_uid = parseInt(contact.uid) + 1000000;
		var pofile_url = im_profile_url + disp_uid;

		var chatUrl = siteUrl + "webchat/pay.php?uid=" + disp_uid;
		var uhash = hex_md5(''+contact.uid);
		var mailUrl = siteUrl + "msg/send.php?uhash=" + uhash;
		//var f = $('<div class=\"im_fi\"><a class=\"headLink fl\"><img /></a><div class="im_fi_n"><a class=\"nameLink fl\"/><div class="uread"></div></div><div class="im_fi_con"></div></div>');
		//var f = $('<li><div class="im_samecity_pic"><a><img width="30" height="30" /></a></div><a href="#" class="im_samecity_picborder">&nbsp;</a><a href="#" class="im_samecity_online_plat">&nbsp;</a><div class=" im_samecity_name"><table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td><a class="im_samecity_nick"></a></td><td><div class="im_samecity_liuyanshu"></div></td></tr></tbody></table></div><a href="#" class="im_samecity_liaotian">&nbsp;</a><a href="#" class="im_samecity_xiexin">&nbsp;</a></li>');
		var f = $('<li><div class="im_samecity_online_plat"></div><div class="webimListPic"><a href="#" class="imglink"><img /></a><a class="webimListPicBorder" href="#">&nbsp;</a></div><div class="webimListName"><a href="#"><span></span></a></div><div class="webimListNum"><table border="0" cellspacing="0" cellpadding="0"><tbody><tr><td class="webimListNumL"></td><td class="webimListNumM"></td><td class="webimListNumR"></td></tr></tbody></table></div><div class="webimListButton"><a class="webimListLT"></a><a class="webimListXX"></a></div></li>');
		if (contact.cash == 1)
		{
			$('a', f).attr("href", chatUrl);
			$('.webimListLT', f).attr("href", chatUrl).css('display', '');
			$('.webimListXX', f).remove();
		}
		else
		{
			$('a', f).attr("href", mailUrl);
			$('.webimListLT', f).remove();
			$('.webimListXX', f).attr("href", mailUrl).css('display', '');
		}
		
		var img = $('img', f);
		img.attr('src', contact.avatar);
		img.parent().attr('href', chatUrl);
		$('a', f).attr('hidefocus', 'true').attr('target', '_blank');
		f.attr('uid', contact.uid).attr('cash', contact.cash);
		$('.webimListName span', f).text(contact.nickname);
		if (contact.uread > 0)
		{
			$('.webimListNumM', f).text(contact.uread);
			$('.webimListNum', f).show();
		}
		else
			$('.webimListNum', f).hide();
		
		if ((contact.platform & 1) == 1)
		{
			if (contact.present == 1)
				$('.im_samecity_online_plat', f).addClass('webimListIcon1').attr("title", "在线");
			else if (contact.present == 2)
				$('.im_samecity_online_plat', f).addClass('webimListIcon2').attr("title", "离开");
			else if (contact.present == 3)
				$('.im_samecity_online_plat', f).addClass('webimListIcon3').attr("title", "忙碌");
			else if (contact.present == 100)
				$('.im_samecity_online_plat', f).addClass('webimListIcon4').attr("title", "安静");
		}
		else if ((contact.platform & 2) == 2)
			$('.im_samecity_online_plat', f).addClass('webimListIcon5').attr("title", "网页在线");
		else if ((contact.platform & 4) == 4)
			$('.im_samecity_online_plat', f).addClass('webimListIcon6').attr("title", "手机在线");

		if (isnew)
		{
			f.addClass('webim_liaddnew');
			var curitem = $("#im_friend .webimTop li").eq(parent.index());
			if (!curitem.hasClass('oning'))
				curitem.addClass("addnew").attr("hidecount", "0");
			else
				curitem.attr("hidecount", "1");
		}
		if (insert)
			parent.prepend(f);
		else
			parent.append(f);
	}
	
	$('#im_friend .webimTop li').live("click", function(e){
		e.preventDefault();
		$('#im_friend .webimTop .oning').removeClass("oning");
		$(this).removeClass("addnew").addClass("oning");
		if ($(this).attr("hidecount") != "")
		{
			var hidecount = parseInt($(this).attr("hidecount"));
			$(this).attr("hidecount", hidecount+1);
		}
		var items = $('#im_friend .webimTop li');
		var uls = $('#im_friend .im_list .webimList ul');
		uls.hide();
		var all_url = [im_root_url+'usercp/friends.php', im_root_url+'usercp/friends.php', im_root_url+'usercp/friends.php'];
		var have = [
		'亲，主动一些爱情会离你更近哟，<br/>来<a href="'+im_root_url+'search" target="_blank">搜搜谁和你有缘吧。</a>',
		'缘分总在擦肩而过中消逝，<br/>关注后慢慢发展吧！',
		'邀请TA成为好友，<br/>让双方的距离再近一些。'];
		var online = ['初次联系人', '我的关注', '我的好友'];
		for(var i = 0; i < items.length; i++)
		{
			if (items[i] == this)
			{
				$('#im_friend .webimZaixianRS span').hide();
				$('#im_friend .webimZaixianRS span').eq(i).show();
				$('#im_friend .webimCakanquanbu a').attr("href", all_url[i]).attr("target", "_blank");
				var this_ul = uls.eq(i);
				if ($('li', this_ul).length == 0)
				{
					var tip = have[i];
					$('#im_friend .im_loading td').html(tip);
					$('#im_friend .im_loading').show();
					$('#im_friend .im_list .webimList').hide();
				}
				else
				{
					$('#im_friend .im_loading').hide();
					this_ul.show();
					$('#im_friend .im_list .webimList').show();
					if ($(this).attr("hidecount") == 2)
					{
						$('.webim_liaddnew', this_ul).removeClass('webim_liaddnew');
						$(this).removeAttr("hidecount");
					}
				}
				break;
			}
		}
	});
	
	$('#im_friend .webimList li').live("hover", function(){
		$('.webim_lihover').removeClass('webim_lihover');
		$(this).addClass('webim_lihover');
	});
	
	$('#im_friend .webimList li').live("click", function(){
		$('.webim_liactive').removeClass('webim_liactive');
		$(this).addClass('webim_liactive');
	});
	
	$('#im_friend .webimList li').live("dblclick", function(){
		if ($('.webimListLT', $(this)).length > 0)
		{
			var uid = parseInt($(this).attr("uid"));
			OpenWebChat(uid);
		}
		if ($('.webimListXX', $(this)).length > 0)
		{
			window.open($('.webimListXX', $(this)).attr("href"));
		}
	});
	
	function updateContactUread(touid, inc, update_cookie)
	{
		touid = parseInt(touid, 10);
		var contact = $("#im_friend .webimList li[uid=" + touid + "]");
		if (contact.length > 0)
		{
			var uread_count = $('.webimListNumM', contact);
			var count = parseInt(uread_count.text());
			count = count ? count : 0;
			count = count + inc;
			if (count < 0) count = 0;
			uread_count.text(count);
			if (count > 0)
				$('.webimListNum', contact).show();
			else
				$('.webimListNum', contact).hide();
		}
		if (update_cookie)
		{
			var history_chat = ImGetCCookie('IM_HC');
			if (!history_chat) history_chat = {};
			var newc = history_chat[touid] ? history_chat[touid] : 0;
			newc = newc + inc;
			if (newc < 0) newc = 0;
			history_chat[touid] = newc;
			ImSetCCookie('IM_HC', history_chat);
		}
	}

	function changeOnlineState(online, msg, incr)
	{
		var uid = parseInt(msg.uid, 10);
		var present = msg.present ? msg.present : 0;
		var platform = msg.platform ? msg.platform : 0;
		var contact = $("#im_friend .webimList li[uid=" + uid + "]");
		if (contact.length > 0)
		{
			var cls = "";
			var title = "";
			if (online)
			{
				if (present == 1){
					cls = 'webimListIcon1';
					title = '在线';
				}
				else if (present == 2){
					cls = 'webimListIcon2';
					title = '离开';
				}
				else if (present == 3){
					cls = 'webimListIcon3';
					title = '忙碌';
				}
				else if (present == 100){
					cls = 'webimListIcon4';
					title = '安静';
				}
				else if ((platform & 2) == 2){
					cls = 'webimListIcon5';
					title = '网页在线';
				}
				else if ((platform & 4) == 4){
					cls = 'webimListIcon6';
					title = '手机在线';
				}
			}
			cls = cls ? "im_samecity_online_plat " + cls : "im_samecity_online_plat";
			$('.im_samecity_online_plat', contact).attr("class", cls);
			if (title) $('.im_samecity_online_plat', contact).attr("title", title);
			updateFriendNum(contact.parent().index(), incr);
		}
	}

	function addRealtimeContact(msg)
	{
		if (contact_loaded)
		{
			var uid = msg.uid;
			var list = $('#im_friend .webimList ul');
			var item = $("li[uid="+uid+"]", list);
			if (item.length > 0)
			{
				if ($(".im_samecity_online_plat", item).attr("class") != "im_samecity_online_plat")
					updateFriendNum(item.parent().index(), -1);
				item.remove();
				contact_btn.changeNumber(contact_btn.getNumber() - 1);
			}
			$.ajax({
				type: "GET",
				url: getAjaxUrl("ajax.php?svc=get_user_info&uid="+uid),
				dataType: "jsonp",
				jsonp:'jsoncallback',
				success: function(ret){
					if (ret.code != 0) return;
					var idx = -1;
					if (ret.is_black == 1)
						idx = -1;
					else if (ret.is_good == 1)
						idx = 2;
					else if (ret.is_friend == 1)
						idx = 1;
					else if (ret.cash == 1)
						idx = 0;
					if (idx == -1) return;
					var panel = list.eq(idx);
					addContact(ret, panel, 1, 1);
					if (idx == $("#im_friend .webimTop ul .oning").index())
					{
						panel.show();
						$('#im_friend .im_loading').hide();
						$('#im_friend .im_list .webimList').show();
					}
					if (ret.platform != 0) updateFriendNum(idx, 1);
				}
			});
		}
		contact_btn.changeNumber(contact_btn.getNumber() + 1);
		if (contact_btn.getNumber() > 0)
			contact_btn.showNumber();
		else
			contact_btn.hideNumber();
	}

	function updateFriendNum(idx, incr)
	{
		var num_obj = $('#im_friend .webimZaixianRS span').eq(idx);
		if (num_obj.length > 0)
		{
			var num = parseInt(num_obj.text());
			num = num ? num : 0;
			num = num + incr;
			num = num > 0 ? num : 0;
			num_obj.text(num);
		}
	}

	function sortContacts(pnl)
	{
		var list = $('.im_samecity_online_plat', pnl);
		var onlines = new Array();
		var pconlines = new Array();
		var offlines = new Array();
		var item;
		for (var i = 0; i < list.length; i++) {
			item = $(list[i]);
			if(item.attr("class") != "im_samecity_online_plat")
			{
				if (item.hasClass('webimListIcon1') || item.hasClass('webimListIcon2') || item.hasClass('webimListIcon3') || item.hasClass('webimListIcon4'))
					pconlines.push(item.parent());
				else
					onlines.push(item.parent());
			}
			else
				offlines.push(item.parent());
		}
		var previous = null;
		for (var i = 0; i < pconlines.length; i++) {
			if(previous)
				pconlines[i].insertAfter(previous);
			else
				pnl.prepend(pconlines[i]);
			previous = pconlines[i];
		}
		for (var i = 0; i < onlines.length; i++) {
			if(previous)
				onlines[i].insertAfter(previous);
			else
				pnl.prepend(onlines[i]);
			previous = onlines[i];
		}
		for (var i = 0; i < offlines.length; i++) {
			if(previous)
				offlines[i].insertAfter(previous);
			else
				pnl.prepend(offlines[i]);
			previous = offlines[i];
		}
	}

	$('#im_profile_pop_win .dzhLayer_iconImg > a').live('mouseover', function(){
		$(this).find("div").show();
	});
	$('#im_profile_pop_win .dzhLayer_iconImg > a').live('mouseout', function(){
		$(this).find("div").hide();
	});

	$('#im_profile_pop_win .dzhLayer_iconImg > a > div').live('click', function(){
		$(this).hide();
		var msg = $(this).find("p").text();
		var touid = uid_disp - 1000000;
		var sendUrl = getAjaxUrl("ajax.php?svc=sendChat2&to=" + touid + "&tjly=hello&body=" + encodeURIComponent(msg));
		$('#im_profile_pop_win .dzhLayer_loaded').hide();
		$('#im_profile_pop_win .dzhLayer_iconImg').hide();
		$('#im_profile_pop_win .dzhLayer_loading').show();
		reportPV('im_profile_pop_sendchat1|' + mysex + '|' + myuid);
		$.ajax({
			type: "GET",
			url: sendUrl,
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(ret){
				if(ret.code == 0 || ret.code == 100)
				{
					reportPV('im_profile_pop_sendchat2|' + mysex + '|' + myuid);
				}
				$('#im_profile_pop_win .dzhLayer_loading').hide();
				$('#im_profile_pop_win .dzhLayer_loaded').show();
				window.setTimeout(function(){
					$('#im_profile_pop_win .dzhLayer_loaded').hide();
					$('#im_profile_pop_win .dzhLayer_iconImg').show();
				}, 3000);
			}
		});
		OpenWebChat(touid);
		return false;
	});

	$('#im_profile_pop_win .dzhLayer_set').live('mouseover', function(){
		$('#im_profile_pop_win .dzhLayer_bxs').show();
	});
	
	$('#im_profile_pop_win .dzhLayer_set').live('mouseout', function(){
		$('#im_profile_pop_win .dzhLayer_bxs').hide();
	});

	$('#im_profile_pop_win .dzhLayer_bxs').live('click', function(){
		var self = $(this);
		$.ajax({
			type: "GET",
			url: getAjaxUrl('ajax.php?svc=set_ppc'),
			dataType: "jsonp",
			jsonp: 'jsoncallback',
			success: function(ret){
				if (ret.code == 0)
				{
					var svc_info = ImGetSCookie('svc');
					svc_info.ppc = ret.t;
					ImSetSCookie("svc", svc_info);
					self.hide();
				}
			}
		});
		return false;
	});
	
	////////////////////
	function closeSession()
	{
		closeTray();
		if(im_switch)
		{
			try{bulletin_div.style.display = '';}catch(e){}
			try{pop.bulletin_div.style.display = '';}catch(e){}
		}
		
	}
	
	var jyimCb = {};
	window.jyim = {
		advise: function(type, fun)
		{
			if(type < 1024) return;
			var cbs = jyimCb[''+type];
			if(!cbs) {cbs = []; jyimCb[''+type] = cbs;}
			cbs.push(fun);
		},
		unadvise: function(type, fun)
		{
			var cbs = jyimCb[''+type];
			if(cbs)
			{
				var i;
				for(i=0; i<cbs.length; i++)
				{
					if(cbs[i] == fun)
					{
						cbs.splice(i, 1);
						return;
					}
				}
			}
		}
	};
	
	//Msg
	JymsgClient.prototype.OnMessage = function(msg, type, master)
	{
		var me = this;
		switch(type)
		{
			case 54: //count info
				{
					if(msg.js && eval(msg.js)) return;
					
					if(master)
					{
						ImSetMultiSCookie(msg);
					}
					showTray(msg);
					if (msg.omc > 0) 
					{
						showOffmsgPop(msg.omc);
						showUnseenChatMsgNum(msg.omc, true);
					}
					
				}
				break;
			/*case 51: //addfriend
				{
					if ($('#im_friend .im_fi[uid="'+msg.uid+'"]').length > 0)
						return;
					
					if(list_loaded)
					{
						addFriend(msg.uid, msg.nick, msg.avatar, msg.online, msg.del, 0);
					}
					
					var e = $('#im_bar .im_btn[t="#im_friend"] span:nth-child(2)');
					var n = parseInt(e.text());
					
					var oe = $('#im_bar .im_btn[t="#im_friend"] span:nth-child(1)');
					var on = parseInt(oe.text());
					
					if(msg.del)
					{
						if(list_loaded)
						{
							var de = $('#im_friend .im_fi[uid="'+msg.del+'"]');
							de.remove();
						}
						n = Math.max(n-1, 0);
						if(msg.delon == '1') on = Math.max(on-1, 0);
					}
					
					++n;
					if (msg.online) ++on;
					
					e.text(n);
					oe.text(on);
					
					if(master) setImSCookie(on, n);
				}
				break;
			case 52: //delfriend
				{
					if(list_loaded) $('#im_friend .im_fi[uid="'+msg.uid+'"]').remove();
					
					var onn = -1;
					if(msg.online)
					{
						var oe = $('#im_bar .im_btn[t="#im_friend"] span:nth-child(1)');
						var on = parseInt(oe.text());
						oe.text(on-1);
						onn = Math.max(on-1, 0);
					}
					
					var e = $('#im_bar .im_btn[t="#im_friend"] span:nth-child(2)');
					var n = parseInt(e.text());
					n = Math.max(n-1, 0);
					e.text(n);
					
					if(master) setImSCookie(onn, n);
				}
				break;*/
			case 56: //logout
				{
					this.stop();
					window.name = "";
					closeSession();
					if(!master) break;
					
					setImSCookie(0, -2);
					window.setTimeout(function()
						{
							ImDelCookie('IM_M');
							ImDelCookie('IM_CON');
							ImDelCookie('IM_CS');
							ImDelCookie('IM_ID');
							ImDelCookie('IM_S');
							ImDelCookie('IM_CT');
						}, 2000);
				}
				break;
			case 57: //redirect
				{
					if(msg)
					{
						ImSetSCookie('IM_SV', msg);
						window.setTimeout(function(){me.restart();}, 100);
						return;
					}
				}
				break;
			case 64:
			{
				msg.chatmsg = "我刚刚用佳缘佳信新版客户端邀请你免费视频聊天，你的版本不支持，下载新版和我视频聊天吧";
				msg.fromnick = msg.nick;
			}
			case 122:
			{
				var touid = msg.from;
				var new_msg = msg.chatmsg;
				var tonick = msg.fromnick;
				
				//当前是否正在和该好友聊天
				var win_name = ImGetCookie('IM_CT');
				
				var chats = win_name.substr('5').split('_');
				var j = $.inArray(touid + "", chats);
				var cur = ImGetCCookie('IM_CUR');
				if(!win_name || ((cur != touid) && !IsPcClientOnline()))
				//if(j == -1 && !IsPcClientOnline())
				{
					if ($("#im_friend").is(":visible"))
					{
						if (j == -1)
						{
							chat_loaded ? insertTOChatList(tonick, touid) : 1;
							showUnseenChatMsgNum(-1, master);
							updateContactUread(touid, 1, true);
						}
					}
					else if (showChatPop(msg))
					{
						startFlashTitle(tonick);
						trayPlaySound(master);
						if (j == -1)
						{
							chat_loaded ? insertTOChatList(tonick, touid) : 1;
							showUnseenChatMsgNum(-1, master);
							updateContactUread(touid, 1, true);
						}
					}
				}
				if(master) reportPV('receive_chat_msg|' + myuid);
			}
			return ;
			case 130:
			{
				if(IsPcClientOnline()) return;
				var insert_msg = buildMsg(msg, type);
				var pv = (master && im_switch);
				var r = show_ms_pop(msg, pv?insert_msg[1]:'');
				insertMsg(insert_msg[0]+((!pv||r)?'':insert_msg[1]), type, r);
			}
			return ;
			case 132:
			{
				stopFlashTitle();
				var num = msg.unread;
				var openuid = msg.openuid;
				var touid = msg.to_uid;
				var tonick = msg.nickname;
				var unread = parseInt(msg.uread_total);
				removeFromChatBox(openuid);
				insertTOChatList(tonick, touid, unread);
				showUnseenChatMsgNum(num, master);
				var updatecount = parseInt(msg.updatecount);
				updateContactUread(openuid, -updatecount, false);
				
				//$('#im_chat_pop_win').animate({"marginBottom": "-=198px"}, 
				//	1000, "linear", function(){$(this).hide();});
				$('#im_chat_pop_win').slideUp(1000, function(){$(this).hide();window.setTimeout(show_jiaxin_pop, 100);});
			}
			return ;
			case 133:
			{
				stopFlashTitle();
				//$('#im_chat_pop_win').animate({"marginBottom": "-=198px"}, 
				//	1000, "linear", function(){ $(this).hide();});
				$('#im_chat_pop_win').slideUp(1000, function(){ $(this).hide();window.setTimeout(show_jiaxin_pop, 100);});
			}
			return;
			case 246:
			case 247:
			{
				if(IsPcClientOnline()) return;
				var insert_msg = buildMsg(msg, type);
				var pv = (master && im_switch);
				var r = show_sub5to1_pop(type, pv?insert_msg[1]:'');
				insertMsg(insert_msg[0]+((!pv||r)?'':insert_msg[1]), type, r);
			}
			return ;
			case 1128:
			{
				changeOnlineState(1, msg, 1);
			}
			return;
			case 1129:
			{
				changeOnlineState(0, msg, -1);
			}
			return;
			case 66:
			{
				changeOnlineState(1, msg, 0);
			}
			return;
			case 1133: // 初次联系人
			{
				addRealtimeContact(msg);
			}
			return;
			case 51: // 添加关注
			{
				addRealtimeContact(msg);
			}
			return;
			case 152: // 添加好友
			{
				addRealtimeContact(msg);
			}
			return;
			case 52: // 主动取消关注
			case 105: // 被别人取消关注
			{
				addRealtimeContact(msg);
			}
			return;
			case 1131: // 被别人加黑名单
			{
				addRealtimeContact(msg);
			}
			return;
			case 1134: // 加别人为黑名单
			{
				addRealtimeContact(msg);
			}
			return;
			case 1135: // 主动取消黑名单
			{
				addRealtimeContact(msg);
			}
			return;
			case 202:
			{
				if (window.location.pathname.indexOf("/broadcast") != -1)
				{
					window.location.reload();
					return;
				}
			}
			break;
			case 50: // mark mail read
			{
				getUnreadMailCount();
			}
			break;
		}
		
		if (type == 101)
			window.setTimeout(function(){
				getUnreadMailCount();
				inbox_btn.addBtnClass("new");
				popMsg(msg, type, (master && im_switch), master);
			}, 10000);
		else
			popMsg(msg, type, (master && im_switch), master);
	};
	
	JymsgClient.prototype.OnError = function(usr_chg)
	{
		if(!usr_chg) 
		{
			var me = this;
			$.ajax({
				type: "GET",
				url: getAjaxUrl("ajax.php?svc=connectError&loc=" + window.location.href + "&ua=" + navigator.userAgent),
				dataType: "jsonp",
				jsonp:'jsoncallback',
				success: function(host){
					if(host == null || me.host == host || host.code == 1)
						me.notify(0, 56);
					else
					{
						ImSetSCookie('IM_SV', host);
						me.restart();
					}
				},
				error: function()
				{
					me.stop();
					me.notify(0, 56);
				}
			});
			
			return;
		}
		closeSession();
	}
	
	function buildMsg(jsonobj, type)
	{
		var siteUrl = im_root_url;
		var profileHost = siteUrl + "profile/";

		var uid = parseInt(jsonobj["uid"]);
		var disp_uid = uid + 1000000;
		var profileNickname = jsonobj["nick"];
		var mid = jsonobj["mid"];

		//get form php global
		var profileUrl = im_profile_url + disp_uid + "?m_type=11&chat=1&ol=1";
		var chatUrl = siteUrl + "webchat/pay.php?uid=" + disp_uid;
		var msgHost = siteUrl + "msg/";
		var msgUrl = msgHost;
		
		if (type == 2 || type == 101)
		{
			if (mid && undefined != mid.length)
		    {
				msgUrl = msgUrl + "showmsg.php?msg_id=" + mid + "&box_type=inbox";
		        //msgUrl = msgUrl + "showmsg.php?msg_id=" + mid + "&box_type=inbox&src_key=pcclienttab";
		    }
		}
		
		var profileInfo = jsonobj["desc"];
		if (type == 101 && jsonobj["time"])
		{
			var date_time = new Date(jsonobj["time"] * 1000);
			var date_str = (date_time.getMonth() + 1) + "月" + date_time.getDate() + "日" + date_time.getHours() + "时";
			profileInfo += '，于' + date_str;
		}
		var paperUrl = siteUrl + "paper/send_record.php#paper";

		var chatJump = siteUrl + "chat/recv_jump.php?from_uid=" + disp_uid + "&jump=1&code=";
		var giftUrl = siteUrl + "usercp/gift/listr.php";
		var phone_client_url = im_subject_base + "2011Q2/wap_clients/?from=pop";
		var phone_desc = '';
		function pv2url(type)
		{
			var pv_url = getPvLink() + "pv=hudong&type=" + type + "&";			
			// 给liliang 统计展示用 DEVELOP-3026
			if(type == 88 || type == 89 || type == 999 || type == 1)
				pv_url = pv_url + "lluid=" + disp_uid;
			
			return "<img src='" + pv_url + "' width='0' height='0'  style='display:none'>";
		}
		
		var raw_msg = '';
		var oldtype;
		switch (type)
		{
		case 102:
			{
				var msg = "<a target=\"_blank\" href=\"" + profileUrl + "&amp;flt=zdlook\">" + profileNickname + "</a>阅读了您发给TA的信件，TA和您很有缘分,别错过哦<br />";
				msg = msg + "<span><a target=\"_blank\" href=\""+chatUrl+"&amp;flt=zdchat\" class ='pop_win_invite_chat'>邀请TA聊天</a>　<a target=\"_blank\" href=\"" + profileUrl + "&amp;flt=zdlook\">查看TA的资料</a></span>";
				
				return [msg, pv2url(803)];
			}
		case 130:
			{
				var broadcast_url = siteUrl + "broadcast/";
				var msg = "";
				var pv_type = 130;
				if (false)
				{
					var summer_url = siteUrl + "webim/client/app/summerkill/?from_src=im_ms_pop";
					msg = "<a target=\"_blank\" href=\"" + profileUrl + "&amp;ddp=6&amp;fxly=cp-yfms&amp;flt=qlcylook\">" + profileNickname + "</a>使用<a target=\"_blank\" href=\"" + summer_url + "\">佳信免费秒杀</a>对你说：<br />";
					msg = msg + jsonobj["msg"];
					msg = msg + "<span><a target=\"_blank\" href=\""+chatUrl+"&amp;flt=qlcychat\" class ='pop_win_invite_chat'>邀请TA聊天</a>　<a target=\"_blank\" href=\"" + profileUrl + "&amp;ddp=6&amp;fxly=cp-yfms&amp;flt=qlcylook\">查看TA的资料</a></span>";
					msg = msg + "<span><a target=\"_blank\" href=\""+summer_url+"\" >&nbsp;&nbsp;获得免费缘分秒杀</a>　</span>";
					pv_type = "pc130";
				}
				else
				{
					msg = "<a target=\"_blank\" href=\"" + profileUrl + "&amp;ddp=6&amp;fxly=cp-yfms&amp;flt=qlcylook\">" + profileNickname + "</a>使用<a target=\"_blank\" href=\"" + broadcast_url + "\">缘分秒杀</a>对你说：<br />";
					msg = msg + jsonobj["msg"];
					msg = msg + "<span><a target=\"_blank\" href=\""+chatUrl+"&amp;flt=qlcychat\" class ='pop_win_invite_chat'>邀请TA聊天</a>　<a target=\"_blank\" href=\"" + profileUrl + "&amp;ddp=6&amp;fxly=cp-yfms&amp;flt=qlcylook\">查看TA的资料</a></span>";
					msg = msg + "<span><a target=\"_blank\" href=\""+broadcast_url+"\" >&nbsp;&nbsp;我也要玩缘分秒杀</a>　</span>";
				}
				return [msg, pv2url(pv_type)];
			}
		case 246:
			{
				var zt_url = siteUrl + "parties/2012/msg5to1/?src_key=im_pop_246";
				var msg = '<div>主动出击，赢美好爱情。主动联系5个他，即可获得一次免费看信机会。<a href="' + zt_url + '" target="_blank" class="submsg5to1_look">去看看>></a></div>';
				return [msg, pv2url(246)];
			}
		case 247:
			{
				var zt_url = siteUrl + "parties/2012/msg5to1/?src_key=im_pop_247";
				var msg = '<div>您在“主动say hi 敢爱有礼”活动中获得了免费看信机会哦，<a href="' + zt_url + '" target="_blank" class="submsg5to1_look">赶快去看信>></a></div>';
				return [msg, pv2url(247)];
			}
		default:
			if(type > 1000 && type < 2000)
				oldtype = type - 1000;
			else if (typeof(jsonobj['phone']) != 'undefined')
			{
				oldtype = new2old_phone_map[''+type];
				if (oldtype && pop_template[oldtype])
				{
					phone_desc = '<a href="' + phone_client_url + '" target="_blank">手机用户:&nbsp;</a>';
					profileUrl += '&fromphone=1';
				}
				else
					oldtype = new2old_map[''+type];
			}
			else
				oldtype = new2old_map[''+type];
			
			if(!oldtype)
			{
				if(jsonobj['html']) return [jsonobj['html'], pv2url(type)];
				else
					return false;
			}
			raw_msg = pop_template[oldtype];
			if(!raw_msg) return false;
		}
		
		var uid_hash = '';
		if(oldtype == 4) //为假聊天邀请 不走该通道
		{
			uid_hash = hex_md5(''+uid);
			var escUrl = profileHost + "?uidhash=" + uid_hash + "&m_type=11&chat=1&ol=1&flt=zdlook"; 
			var chatHello = siteUrl + "chat/get_hello.php?from_uid=" + disp_uid;
			var chatLook = siteUrl + "chat/zd.php?from_uid=" + disp_uid + "&pre_url=" + escUrl;
			var chatRefuse = siteUrl + "chat/recv_invite.php?from_uid=" + disp_uid + "&un=1";
			
			raw_msg = raw_msg.replace(/CHAT_HELLO/gm, chatHello);
			raw_msg = raw_msg.replace(/CHAT_REFUSE/gm, chatRefuse);
			raw_msg = raw_msg.replace(/CHAT_LOOK/gm, chatLook);
		}
		
		raw_msg = raw_msg.replace(/PROFILE_URL/gm, profileUrl)
			.replace(/PROFILE_INFO/gm, profileInfo)
			.replace(/PROFILE_UID/gm, disp_uid)
			.replace(/PROFILE_NICKNAME/gm, profileNickname)
			.replace(/PAPER_URL/gm, paperUrl)
			.replace(/MSG_URL/gm, msgUrl)
			.replace(/CHAT_URL/gm, chatUrl)
			.replace(/PROFILE_INFO/gm, profileInfo)
			.replace(/CHAT_JUMP/gm, chatJump)
			.replace(/PHONE_DESC/gm, phone_desc)
			.replace(/PHONE_CLIENT_URL/gm, phone_client_url)
			.replace(/SITE_URL/gm, siteUrl.substr(0, siteUrl.length-1));
		
		if(raw_msg.indexOf('PROFILE_HASH') != -1)
		{
			if(!uid_hash) uid_hash = hex_md5(''+uid);
			raw_msg = raw_msg.replace('PROFILE_HASH', uid_hash);
		}
		
		return [raw_msg, pv2url(oldtype)];
	}

	function popMsg(msg, type, pv, master)
	{
		//if(IsPcClientOnline()) return;
		
		var msg =  buildMsg(msg, type);
		if(msg)
		{
			if(type == 101) hideList();
			var r = showPop(msg[0]+(pv?msg[1]:''), 1, type);
			insertMsg(msg[0]+((!pv||r)?'':msg[1]), type, r);
			//if(master) ring2.play();
		}
		else
		{
			var cbs = jyimCb[''+type];
			if(cbs)
			{
				var i;
				for(i=0; i<cbs.length; i++)
					cbs[i](msg, type);
			}
		}
	}
	
	var chat_btn, msg_btn, inbox_btn, footbar, jyapp_popup, contact_btn;
	function buildFootbar()
	{
		footbar = new JyFootbar();
		contact_btn = new JyFootbarBtnItem('联系人', 'contact', 'right', contactClick);
		contact_btn.addItemClass("im_btn_last oneCity pr");
		contact_btn.addNumber(0);
		footbar.addBtnItem(contact_btn);
		var contact_popup = new JyFootbarPopup('im_friend', '联系人', 'im_samecity_div', 'webimTop', true);
		contact_popup.getTitle().html('<ul><li class="oning">初次联系</li><li>关注</li><li>好友</li></ul><div class="im_t_close webimClose"></div>');
		//contact_popup.customBody('<div class="im_samecity_zxrs"><div class="im_samecity_zaixianshu">在线(<span></span><span></span><span></span>)</div><div class="im_samecity_cakanquanbu"><a>查看全部&gt;&gt;</a></div></div><div class="im_samecity_body"><ul></ul><ul></ul><ul></ul></div><div class="im_loading"><table><tr><td><img src="'+im_image_base+'loading.gif"></img><span>正在加载，请稍候</span></td></tr></table></div><div class="im_samecity_yuzhuang"><a>安装佳信客户端&nbsp;与TA们免费聊天</a></div>');
    	contact_popup.customBody('<div class="webimZaixian"><div class="webimZaixianRS">在线（<span></span><span></span><span></span>）</div><div class="webimCakanquanbu"><a href="###">查看全部&gt;&gt;</a></div></div><div class="webimList"><ul></ul><ul></ul><ul></ul></div><div class="im_loading"><table><tr><td><img src="'+im_image_base+'loading.gif"></img><span>正在加载，请稍候</span></td></tr></table></div><div class="webimListAnzhuang"><a href="#">安装佳缘佳信3.0，与她免费视频聊天</a></div>');
		contact_popup.appendToBtn(contact_btn);
		/*
		var friend_btn = new JyFootbarBtnItem('同城会员', 'oneCity', 'right', oneCityClick);
		friend_btn.addItemClass("im_btn_last oneCity pr");
		footbar.addBtnItem(friend_btn);
		var friend_popup = new JyFootbarPopup('im_friend', '同城会员', '', 'oneCity_content_title', true);
		friend_popup.customTitle('<a class="change">换一组</a>');
		friend_popup.customBody('<div class="im_loading"><img src="'+im_image_base+'loading.gif"></img><span>正在加载，请稍候</span></div>');
		friend_popup.appendToBtn(friend_btn);
		*/
		
		chat_btn = new JyFootbarBtnItem('聊天', 'onlineTalk', 'right', onlineTalkClick);
		chat_btn.addItemClass("im_btn_second onlineTalk pr");
		chat_btn.addNumber(0);
		footbar.addBtnItem(chat_btn);
		var chat_popup = new JyFootbarPopup('im_chatx', '聊天', 'onlineTalk_content', 'onlineTalk_content_title', true);
		chat_popup.customTitle('<a target="_blank" href="' + msgCenterUrl + '" class="notes">查看全部聊天记录</a>');
		chat_popup.customBody('<div id="im_rec_chat_con"><span class=\'im_list_no_chat\'>暂无未读消息</span><div class="im_rec_chat"><img /><span>和我聊天</span></div><div class="im_rec_chat"><img /><span>和我聊天</span></div><div class="im_rec_chat"><img /><span>和我聊天</span></div></div>');
		chat_popup.appendToBtn(chat_btn);
		
		msg_btn = new JyFootbarBtnItem('消息');
		msg_btn.addItemClass("im_btn_first message pr");
		msg_btn.addNumber(0);
		footbar.addBtnItem(msg_btn);
		var msg_popup = new JyFootbarPopup('im_msgx', '消息', 'message_content', 'message_content_title', true);
		msg_popup.customBody('<img class="im_jy_logo" src="http://images.jiayuan.com/w/global/i/logo_prompt.jpg" />');
		msg_popup.appendToBtn(msg_btn);
		
		inbox_btn = new JyFootbarBtnItem('收件箱', '', 'right', inboxClick);
		inbox_btn.addItemClass("letter");
		inbox_btn.addNumber(0);
		footbar.addBtnItem(inbox_btn);
		
		robot_btn = new JyFootbarBtnItem('缘缘助手', '', 'right', robotClick);
		robot_btn.addItemClass("robot");
		footbar.addBtnItem(robot_btn);
		
		footbar.addDetachedPopup('<div id="im_pop_win" class="pa im_win"><div class="im_title"><div class=\'im_t_tab\'>互动消息</div><div class=\'im_t_tab\'>交友活动</div><div class="title_rightBg fr im_t_close"></div></div><div class="im_pop_content"><img src="http://images.jiayuan.com/w/global/i/logo_prompt.jpg" /></div><div class="im_pop_content"><img src="http://images.jiayuan.com/w/global/i/logo_prompt.jpg" /></div></div>');
		footbar.addDetachedPopup('<div class="im_yfms_tpl0" id="im_yfms"></div>');
		footbar.addDetachedPopup('<div id="im_chat_pop_win" class="pa im_win"><div class="im_chat_win_title"><div class="im_chat_win_nick"></div><div class="fr im_t_close"></div></div><div class="im_chat_win_content  im_pop_content"><div><span></span><span><a class="im_tosee_chatmsg">查看详情</a></span></div></div></div>');
		footbar.addDetachedPopup('<div id="im_zhuanti"></div>');
		footbar.addDetachedPopup('<div id="im_profile_pop_win"><div class="dzhLayer"><div class="dzhLayer_top"><a href="javascript:void(0);" class="im_t_close dzhLayer_close">&nbsp;</a><div class="dzhLayer_set"><a href="javascript:void(0);" class="dzhLayer_bxs" style="display: none;">今日不再提醒</a></div></div><div class="dzhLayer_text"><p>对方在线，和TA聊聊吧</p></div><div class="dzhLayer_icon"><div class="dzhLayer_iconImg"><a href="javascript:void(0);" class="hi"><span/><div class="tishilayer1"><p class="tishilayer_hi">想了解你更多,可以聊聊么?</p></div></a><a href="javascript:void(0);" class="face"><span/><div class="tishilayer2"><p class="tishilayer_face">想了解你更多,可以聊聊么?</p></div></a><a href="javascript:void(0);" class="flower"><span/><div class="tishilayer3"><p class="tishilayer_flower">想了解你更多,可以聊聊么?</p></div></a><a href="javascript:void(0);" class="heart"><span/><div class="tishilayer4"><p class="tishilayer_xin">想了解你更多,可以聊聊么?</p></div></a></div><div class="dzhLayer_loading"><img src="'+im_image_base+'i/dzh/jx3_loading.gif"/>发送中...</div><div class="dzhLayer_loaded"><img src="'+im_image_base+'i/dzh/send_ok.gif"/>发送成功</div></div></div></div>');
		footbar.addDetachedPopup('<div id="im_jiaxin_pop_win"><div class="jx3_0_1_adv jx3_0_1_adv1"><div class="jx3_0_1_advTop"><a href="javascript:void(0);" class="im_t_close jx3_0_1_advClose">&nbsp;</a></div><div class="jx3_0_1_advBody"><a href="http://im.jiayuan.com/?from=rightbottom" target="_blank" class="jx3_0_1_advButton">&nbsp;</a></div></div>');
		
		var svc_info = ImGetSCookie("svc");
		if (!svc_info || svc_info.uid != myuid || typeof(svc_info.ppc) == 'undefined')
		{
			$.ajax({
				type: "GET",
				url: getAjaxUrl("ajax.php?svc=get_nps_info"),
				dataType: "jsonp",
				jsonp:'jsoncallback',
				success: function(info){
					info.uid = myuid;
					ImSetSCookie("svc", info);
					if(info.nps){
						buildExFootbar(info);
					}else{
						$.getScript('http://mai.jiayuan.com/ajax.php?pos=18', function(){
							buildExFootbar(info);
						});	
					}
				}
			});
		}else{
			if(svc_info.nps){
				buildExFootbar(svc_info);
			}else{
				$.getScript('http://mai.jiayuan.com/ajax.php?pos=18', function(){
					buildExFootbar(svc_info);
				});	
			}
		}
	}
	
	function add_ad_style(){//通过通用底的添加按钮的方法添加一个“广告”文字描述，经过修改样式和innerHTML来实现一个文本内容
		ad_text = new JyFootbarBtnItem('广告', '', 'right', '');
		ad_text.removeItemClass("im_btn");//删除默认样式
		var ad_div = ad_text.getDom();
		ad_div.css("padding", "10px 5px 0 0");
		ad_div.css("line-height", "100%");
		ad_div.css("color", "#999");
		ad_div.html('广告');
		footbar.addBtnItem(ad_text);
	}
	
	function buildExFootbar(info){
		if (info.unread_count){
			inbox_btn.changeNumber(info.unread_count);
			inbox_btn.showNumber();
		}
		var myjy_btn = new JyFootbarBtnItem('我的服务', null, 'left', myjyClick);
		myjy_btn.addItemClass("myJiayuan pr");
		footbar.addBtnItem(myjy_btn);
		var myjy_popup = new JyFootbarPopup('im_myjy', '我的服务', 'myjiayuan_content', 'myjiayuan_content_title', true);
		myjy_popup.appendToBtn(myjy_btn);
		
		var using_svc = info.using;
		var diamond = "diamond_g";
		var vipmem = "vip_g";
		var chat = "chat_g";
		var readmail = "readmail_g";
		var brightlist = "brightlist_g";
		var forground = "forground_g";
		var express = "express_g";
		if (using_svc){
			var svc_arr = using_svc.split(',');
			for (var i = 0; i < svc_arr.length; i++){
				var v = parseInt(svc_arr[i]);
				switch (v){
					case 40:
						diamond = "diamond";
						break;
					case 2:
						vipmem = "vip";
						break;
					case 33:
						chat = "chat";
						break;
					case 38:
						readmail = "readmail";
						break;
					case 4:
						brightlist = "brightlist";
						break;
					case 5:
						forground = "forground";
						break;
					case 100:
						express = "express";
						break;
				}
			}
		}
		
		var service_list_btn = new JyFootbarListItem('service_list', 'left');
		service_list_btn.addClass("im_service_list");
		//如果是新用户显示佳缘宝
		if(info.user_type == "10")
		{
			service_list_btn.addItem('佳缘宝', "jybao", '佳缘宝', im_root_url + 'usercp/center/index.php');
		}
		else
		{
			service_list_btn.addItem('佳缘邮票', "jystamp", '佳缘邮票', im_root_url + 'usercp/center/index.php');
		}
		service_list_btn.addItem('特快专递',"speedpost", '特快专递', im_root_url + 'msgapp/ems/?a=info');
		service_list_btn.addItem('钻石会员', diamond, '钻石会员', old_service_url[40].url);
		service_list_btn.addItem('VIP会员', vipmem, 'VIP会员', old_service_url[2].url);
		//service_list_btn.addItem('看信包月', readmail, '看信包月', old_service_url[38].url);
		//service_list_btn.addItem('光明榜', brightlist, '光明榜', old_service_url[4].url);
		service_list_btn.addItem('排名提前', forground, '排名提前', old_service_url[5].url);
		service_list_btn.addItem('超级聚光灯', express, '超级聚光灯', old_service_url[100].url);
		footbar.addListItem(service_list_btn);
		
		var jyapp_btn = new JyFootbarBtnItem('佳缘应用', null, 'left', jyappClick);
		jyapp_btn.addItemClass("jiayuanCenter pr");
		footbar.addBtnItem(jyapp_btn);
		jyapp_popup = new JyFootbarPopupList('im_jyapp', '佳缘应用', 'jiayuanCenter_content', 'jiayuanCenter_content_title', true);
		jyapp_popup.appendToBtn(jyapp_btn);

		if (typeof(publish_webim_ad) !== 'undefined'){
			var webim_ad1 = publish_webim_ad[0];
			var mid_btn = new JyFootbarBtnItem(webim_ad1[0], 'ad_0', 'left',
				function(){
					reportPV("im_brand_click|" + myuid);
					window.open(webim_ad1[1]);
				}
			);	
		}

		$(".im_inner_btn", mid_btn.getDom()).css("width", "300px");
		mid_btn.addItemClass("brand_mid not_hover");
		footbar.addBtnItem(mid_btn);
		afterCustomTray(showMyjyInfo);
		var is_diamond = (diamond == "diamond") ? 1 : 0;
		window.setTimeout(function(){
			show_profile_pop(is_diamond);
		}, 2000);

		if(typeof(publish_webim_ad) !== 'undefined'){
			var len_ads = publish_webim_ad.length;
			var ad_fun = function(obj){
				this.bindfun=function(){
					if(typeof(obj[2])!='undefined'){
						reportPV(obj[2] + myuid);
					}
					window.open(obj[1]);
				}
			};
			for(i=1;i<len_ads;i++){
				var webim_ad = publish_webim_ad[i];
				var af = new ad_fun(publish_webim_ad[i]);
				var mid_btn = new JyFootbarBtnItem(webim_ad[0], 'ad_'+i, 'left',af.bindfun);
				$(".im_inner_btn", mid_btn.getDom()).css("width", "300px");
				mid_btn.addItemClass("brand_mid not_hover");
				mid_btn.getDom().hide();
				footbar.addBtnItem(mid_btn);
			}
		}
		
		var brand_index = 0;
		window.setInterval(function(){
			var brand_list = $(".brand_mid");
			brand_index = (brand_index + 1) % brand_list.length;
			brand_list.hide();
			brand_list.eq(brand_index).show();
		},20000);
	}
	
	(function()
	{
		var init = function()
		{
			jymsg.start();
			//check cookie
			var json = ImGetCookie('IM_S');
			if(json)
			{
				var msg = json_util.decode(json);
				showTray(msg);
			}
			else
			{
				showTray({});
			}
		};
		if(ua.indexOf('FIREFOX') != -1) window.setTimeout(init, Math.floor(100+Math.random()*500));
		else init();
	})();
})(jQuery, document);
