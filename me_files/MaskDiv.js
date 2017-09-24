//显示遮罩层
function openMaskDiv(divName, width, height, noMask, autoPos)
{
	if (typeof width !=	'number' ||	width <	1) { width = 400; }
	if (typeof height != 'number' || height	< 1) { height =	300; }
	if (typeof noMask == 'undefined') {	noMask = false;	}
	if (typeof noMask == 'undefined') {	autoPos = false;	}
	if (!noMask) {
		var	m =	"mask";
		if(document.getElementById(m)) document.body.removeChild(document.getElementById(m));
		var	newMask	= document.createElement("div");
		newMask.id = m;
		newMask.style.position = "absolute";
		newMask.style.zIndex = "120000";
		_scrollWidth = Math.max(document.body.scrollWidth,document.documentElement.scrollWidth);
		_scrollHeight =	Math.max(document.body.scrollHeight,document.documentElement.scrollHeight);
		newMask.style.width	= _scrollWidth + "px";
		newMask.style.height = _scrollHeight + "px";
		newMask.style.top =	"0";
		newMask.style.left = "0";
		newMask.style.background = "#33393C";
		newMask.style.filter = "alpha(opacity=60)";
		newMask.style.opacity =	"0.60";
		document.body.appendChild(newMask);
	}
	showDiv	= document.getElementById(divName);
	showDiv.style.display =	"block";
	showDiv.style.position = "absolute";
	showDiv.style.zIndex = "120001";
	showDivWidth = width;
	showDivHeight =	height;
	var scrolltop = document.documentElement.scrollTop | document.body.scrollTop ;
	var scrollleft = document.documentElement.scrollLeft | document.body.scrollLeft ;
	if(!autoPos){
		showDiv.style.top = 90 + "px";
	}
	else{	
		showDiv.style.top =	(scrolltop	+ document.documentElement.clientHeight/2 -	showDivHeight/2) +	"px";
	}
	showDiv.style.left = (scrollleft +	document.documentElement.clientWidth/2 -  showDivWidth/2) + "px";
}
//隐藏遮罩层
function closeMaskDiv(divName,isRefresh)
{
	var	m =	"mask";
	if(document.getElementById(m)) document.body.removeChild(document.getElementById(m));
	showDiv	= document.getElementById(divName);
	showDiv.style.display =	"none";
	//增加关闭层刷新页面
	if(isRefresh)
	{
		var t =window.setTimeout(function (){
			if(t)
			{
				 window.clearTimeout(t);
			}
			location.reload();
			},5000);
	}
}
