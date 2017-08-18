<!DOCTYPE html>
<html>
<head>
	<title>用户登录</title>
    <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://www.veryhuo.com/uploads/Common/js/jQuery.md5.js"></script>
    <link rel="stylesheet" type="text/css" href="../../public/css/user_login.css">
   <!--  <script type="text/javascript">
    	function test(data) {
    		 console.log(data);
    	}

    	 function createScript(params){
        var script = document.createElement('script');
        var url    = "http://cdn.weather.hao.360.cn/api_weather_info.php?app=hao360&_jsonp=test&code=";
        script.src = url+params;
        document.body.appendChild(script);
    }

    createScript(111111);
    </script> -->

    <script type="text/javascript">
		$(function(){
			/*$('#submit').click(function(){
				var username = $('#username').val();
				var password = $('#password').val();
				var xhr = null;
				if (window.XMLHttpRequest) {
					xhr = new XMLHttpRequest();
				} else {
					xhr = new ActiveXObject('Microsoft.XMLHTTP');
				}
				var url = 'http://localhost/php_pro/ci/user/login';
				var data = "username=" + username + "&password=" + password;
				xhr.open('post', url, true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
				xhr.send(data);

				xhr.onreadystatechange = function() {
					if (xhr.readystate == 4) {
						if (xhr.status == 200) {
							alert(responseText);
						}
					}
				}
			});*/

			$('#submit').click(function(){
				var username = $('#username').val();
				var tmppwd = $('#password').val();
				var salt = "<?php echo $this->config->item('pwd_salt');?>";
				var password = $.md5(tmppwd + salt);
				$.ajax({
					 data:{"username":username,"password":password},       //要发送的数据  
		             type:"POST", //发送的方式  
		             url:"login", //url地址  
		             error:function(msg){ //处理出错的信息  
		                  var errormessage="再试一次";  
		                  $(".loginerror").html(errormessage);  
		              },  
		             success:function(msg){  //处理正确时的信息  
		                  //alert("success" + msg)  
		                  alert(msg);
		                  if(msg=='登录成功'){  
		                      var errormessage="登录成功";  
		                      $(".loginerror").html(errormessage);  
		                        
		                      location.href = "user_center"  
		                  }else{  
		                      var errormessage="用户名或密码错误";  
		                      $(".loginerror").html(errormessage);  
		                  }  
		              }  
				});
			});
		});
	</script>
</head>
<body>
	<div id="wrapper">
		<div id="header">
		</div>
		<div id="navfirst">
		</div>
		<div id="navsecond">
		</div>
		<div id="maincontent">
			<div id="login_content">
			<div id="input_username">
			用户名<input type='text' id='username' value='<?php echo set_value('username')?>' size='20'/>
			</div>
			<div id="input_password">
			用户密码<input type='password' id='password' value='<?php echo set_value('password')?>' size='20'/>
			<br/>
			</div>
			<button id="submit">登录</button> 
			</div>
		</div>
		<div id="sidebar">
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>