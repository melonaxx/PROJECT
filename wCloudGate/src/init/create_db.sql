set names utf8;
-- --基础服务---

-- drop table if exists id_genter;
-- create table id_genter
-- (
--     id             int(11) not null,
--     obj            varchar(30),
--     step           int(11)
-- ) engine = innodb DEFAULT CHARACTER SET UTF8;

-- insert into id_genter(id, obj, step) values(1, 'other', 10);

-- --用户表---
-- --drop table if exists user;
-- --create table user
-- --(
-- --	id 				int(11) not null primary key,
-- --	createtime 		datetime not null,
-- --	updatetime 		datetime not null,
-- --	mobileno 	    varchar(16) not null,
-- --	passwd          varchar(64) not null,
-- --    name            varchar(30) not null default '',
-- --    email           varchar(60) not null default '',
-- --    company         varchar(40) not null default '',
-- --    domain          varchar(40) not null default '',
-- --    logo            varchar(128) not null default '',
-- --    UNIQUE INDEX (mobileno)
-- --) engine = innodb DEFAULT CHARACTER SET UTF8;

-- -- 坐标数据表 ----
-- DROP TABLE if exists cloudpoint;
-- CREATE TABLE cloudpoint
-- (
-- 	id 					int(11) not null AUTO_INCREMENT PRIMARY KEY,
-- 	createtime			datetime not null,
-- 	updatetime			datetime not null,
-- 	latitude			double(9,6) not null,
-- 	longitude 			double(9,6) not null

-- ) ENGINE innodb DEFAULT CHARACTER SET UTF8;
