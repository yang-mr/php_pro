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

