数据库：
	数据库和数据库实例
		数据库是存在硬盘里的文件，数据库实例siud操作数据库文件；

	存储引擎
		innoDB 支持事务，外键等
		myISAM 不支持事务，表锁设计；支持全文索引
		NDB 集群存储引擎
		Memory 将数据保存在内存中
		Archive 
		Federated
		Maria 总要取代之前的myISAM

	数据类型
		数值类型
		bit类型
			insert into table_name values(11), (b'11'); //后面是插入二进制11
			select id+0 from table_name;  //以十进制查询
			select bin(id+0) from table_name;  //以二进制查询
		字符串类型
		日期和时间类型

	操作表
		create table table_name (
		...); //创建表
		drop table table_name; //删除表
		describe table_name; //查看表结构
		show create table table_name; //查看表的定义信息

		alter 修改表
			alter table old_table_name rename new_table_name;  //修改表名
			alter table table_name add 属性名 属性类型 [first] | [after 属性名]; //增加字段(默认在表的最后) [first]:在表的最前面添加一个字段 [after 属性名]:在表的哪个字段之后添加字段
			alter table table_name drop 属性名; //删除字段
			alter table table_name modify 属性名 属性类型; //修改字段类型
			alter table table_name change 旧属性名 新属性名 旧属性类型; //修改字段名
			alter table table_name change 旧属性名 新属性名 新属性类型; //修改字段名和属性 
			alter table table_name modify 属性名1 数据类型 [first] | [alter 属性名2]; //修改字段顺序 [first]:将属性名1调到表的第一个位置; [alter 属性名2]:调到字段2的后面;

		表的约束
			not null 值不能为空;
			default 设置默认值;
			unique key 值是唯一的;
			primary key 设置主键;
			auto_increment 设置自动增长;
			foreign key设置表的外键;

			constraint pk_name primary key(属性名); 设置主键标识符
			[constraint pk_name] primary key(属性名1,属性名2,属性名3...); //设置多个主键
			constraint 外键约束名 foreign key(属性名1) references 表名(属性名2); 设置属性名1所在表的外键
				ps:
				create table dept (
					dept_id int auto_increment primary key,
					dept_name varchar(40)
				);
				create table person (
					person_id int auto_increment primary key,
					person_name varchar(20),
					dept_id int,
					constraint fk_dept_id foreign key(dept_id) references dept(dept_id)
				);
	操作索引（创建，删除）
		创建索引
			[unique] index|key 索引名 (属性名1 [长度] [asc|desc],[属性名2 [长度] [asc|desc]]); //创建表的时候创建索引 [属性名2 [长度] [asc|desc]]:创建多列索引 [unique]:创建唯一索引
			explain select * from table_name where 属性名 = 1; //查看索引是否启用
			create index 索引名 no table_name (属性名 [长度] [asc|desc]); //在已经存在的表创建索引
			alter table table_name add index|key 索引名 (属性名 [长度] [asc|desc]); 用alter创建普通索引
		删除索引
			drop index index_name on table_name;
			alter table table_name drop index index_name;
	视图（创建，查看，删除，修改）
		创建视图
			create view view_name as 查询语句; 
		查看视图
			show create view view_name; //查看视图的定义信息
			show table_name|view_name status [from db_name] [like 'name']; //查看详细信息
			describe view_name; //查看视图设计信息
		删除视图
			drop view view_name;
		修改视图
			create or replace view_name as 查询;
			alter view view_name as 查询;
		注意：
			对视图增删改后，基本表也会跟着改变；
			视图来自多个表时，不允许添加，删除，修改数据；
	触发器trigger(创建，查看，删除)
		创建trigger
			create trigger trigger_name before|after insert|delete|update on table_name for each row trigger_stmt; //创建单条执行语句  trigger_stmt: 激活触发器后执行的语句

			delimiter $$
			create trigger trigger_name before|after insert|delete|update on table_name for each row begin trigger_stmt end$$
			delimiter ;    //创建多条执行语句
		查看触发器
			show triggers \G
			select * from triggers where trigger_name=tri_test;
		删除触发器
			drop trigger tri_name;
	数据的操作（增，删，改，查crud）
		insert into table_name(...) values(...);
		update table_name set 属性名=属性值 where ...;
		delete from table_name where ...;
		select distinct 属性名 from table_name; //distinct: 去除重复的数据
		select 属性名[+-*/%] from table_name;//实现数学运算数据查询
		order by fileld_name asc|desc; //asc: 升序  desc: 降序
		limit [offset_start] row_count;  //限制查询条数

		分组数据查询(单字段)
			select dept_id, group_concat(person_id) persons, count(person_id) number from person order by dept_id;
		分组数据查询(多字段)
			select dept_id, group_concat(person_id) persons, count(person_id) number from person order by dept_id, birthday;
		分组数据查询(having)
			select dept_id, group_concat(person_id) persons, count(person_id) number from person order by dept_id having avg(money) > 1000;

		多表查询：
			并(union)
				union 除掉了重复的数据
				union all 包括了重复的数据
			笛卡尔积(cartesian product)
			内连接(inner join)
				自连接(natural join)
				等值连接 
					MariaDB [php_mvc]> select p.person_id, p.time, p.money, d.position from person p, dept d where p.dept_id=d.dept_id;
					MariaDB [php_mvc]> select p.person_id, p.time, p.money, d.position from person p inner join dept d on p.dept_id=d.dept_id;
				不等连接
			外连接(outer join)
				左外连接
				右外连接
				全外连接
		子查询
			单行单列
				select * from person where money > (select money from person where person_id = 2);
			单行多列
				select * from person where (dept_id, money) = (select dept_id, money from person where person_id = 13);
			多行单列
				[not] in, =|>=|>|<|<=any, >=|>|<|<=all, exists
				select * from person where dept_id [not] in (select dept_id from dept);
				select * from dept d where exists (select * from person where dept_id = d.dept_id);
			多行多列 (一般在主查询的from)
				select d.dept_id, d.position, number average from dept d inner join (select dept_id, count(person_id) number, avg(money) average from person group by dept_id desc) person on person.dept_id = d.dept_id;
		注意：
			not in 条件：当有null时，查询的结果为空;
			当没有数据的时候：count() 函数返回0; 其他的函数返回null

	运算符(成立返回1, 否则返回0)
		注意：null=null 为null; null<=>null 为1; 前面的等号不能比较null
			  !=, <> 不能操作null

	存储过程和函数的操作
		

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
		


