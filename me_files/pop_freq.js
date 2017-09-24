	var pop_arr = new Array;
	var content = '';
	var loc_url	=	location.href;
var url_arr	=	loc_url.split('http://');
var url_dom_arr	=	url_arr[1].split('/');
var loc_domain	=	url_dom_arr[0];
switch (loc_domain)
{
case 'jiayuan.msn.com.cn':break;
case 'sina.jiayuan.com': break;
case 'tom.jiayuan.com': break;
default:
loc_domain = 'www.jiayuan.com';break;
}
		contetn = "<a href=\"http:\/\/party.jiayuan.com\/party_info.php?pid=9316\" target=\"_blank\" style=\"color:#636363;\">\u3010\u516c\u76ca\u514d\u8d39\u3011\u957f\u6c99\u6700\u7f8e\u5355\u8eab\u5973\u5b69\u8bc4\u9009\u66a8\u4e03\u5915\u5343\u4eba\u4ea4\u53cb\u6d3e\u5bf9 \u706b\u70ed\u62a5\u540d\u4e2d \u73b0\u573a\u4e0d\u4ec5\u6709\u4f17\u591a\u4f18\u8d28\u5973\u5b69\u53c2\u52a0\uff0c\u8fd8\u6709\u4f17\u591a\u4f18\u8d28\u7537\u5609\u5bbe\u5230\u573a\u54df\uff01\u3010\u8be6\u60c5\u8bf7\u70b9\u51fb\u672c\u6bb5\u6587\u5b57\u5230\u9875\u9762\u4e86\u89e3\u3011<\/a><br\/>".replace(/{pop_domain}/g,loc_domain);
		pop_arr[0] = new Array('once','120','','1,2,5,6,10,','43','4300',contetn,'pop_1442472739', '0');
				contetn = "<a href=\"http:\/\/party.jiayuan.com\/party_info.php?pid=9330\" target=\"_blank\" style=\"color:#636363;\">\u3010\u6b66\u6c49\u516c\u76ca\u514d\u8d39\u30112017\u5e748\u670827\u65e5 \u6211\u4eec\u4e00\u89c1\u949f\u60c5 \u4e03\u5915\u60c5\u4eba\u8282 \u706b\u70ed\u62a5\u540d\u4e2d\uff01\u3010\u8be6\u60c5\u8bf7\u70b9\u51fb\u672c\u6bb5\u6587\u5b57\u5230\u9875\u9762\u4e86\u89e3\u3011<\/a><br\/>".replace(/{pop_domain}/g,loc_domain);
		pop_arr[1] = new Array('once','120','','1,2,5,6,10,','42','4200',contetn,'pop_1503315451', '0');
			insert_con();
	