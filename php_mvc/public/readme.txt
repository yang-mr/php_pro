//创建数据库
create database php_mvc;

//创建表

//用户表

create table user (
	id int unique not null primary key auto_increment, 
	username varchar(20),
	password varchar(40),
	phone varchar(20),
	email varchar(40)
);

