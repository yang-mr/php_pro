//创建数据库
create database php_mvc;

//创建表

//用户表   type 0 1 2 客户 装修师傅 设计师

create table user (
	id int unique not null primary key auto_increment, 
	username varchar(20),
	password varchar(40),
	phone varchar(20),
	email varchar(40),
	type int not null
);

//需求表  外键 user_id;

create table want (
	id int unique not null primary key auto_increment, 
	title varchar(40) not null,
	des varchar(100) not null,
	area int not null, 
	price int,
	publicdata date,
	user_id int not null,	
  	CONSTRAINT want_fk FOREIGN KEY(user_id) REFERENCES user(id)
);

前端：
	vh单位就是当前屏幕可见高度的1%; 100vh = 100%;

	页面布局 flex

	  	容器flex属性
			flex-direction 主轴的方向
			flex-wrap 是否要换行
			flex-flow 组合上面2个属性
			justify-content 项目如何在主轴上对齐
			align-items 项目如何在交叉轴上对齐
			align-content 多条轴线的对齐方式，一条轴线不起作用

		项目属性
			order 项目的对齐方式 默认0 数字越小越前
			flex-grow 项目放大 默认0 为0的时候不放大 越大得到剩余的空间等比增加
			flex-shrink 项目缩小 默认1 为0的时候不缩小
			flex-basis 项目的本来大小 默认auto 可以设固定值100px
			flex ps:  flex: none | auto | [ <'flex-grow'> <'flex-shrink'>? || <'flex-basis'> ]   :auto (1 1 auto) 和 none (0 0 auto)

			align-self  单个项目与其他的项目不一样的对齐方式 默认auto 表示继承父元素(align-items)


		注意：
			enctype属性:规定在发送到服务器之前应该如何对表单数据进行编码
				1.application/x-www-form-urlencoded
					（默认的编码方式）被编码为名称/值对	

				2.multipart/form-data
					表单数据被编码为一条消息

				3. text/plain
					表单数据中的空格转换为 "+" 加号，但不对特殊字符编码


		Composer:
			composer install 
			composer update  		以上2个命令会更新所有的扩展包，谨慎使用；

			composer require monolog/monolog  推荐使用该命令安装扩展包；

			composer update monolog/monolog  
			更新某个包

			composer remove monolog/monolog
			移除某个包

		monolog 日志库：
			handle日志管理器
				


