set names utf8;
-- --基础服务---

drop table if exists id_genter;
create table id_genter
(
    id             int(11) not null,
    obj            varchar(30),
    step           int(11)
) engine = innodb DEFAULT CHARACTER SET UTF8;

insert into id_genter(id, obj, step) values(1, 'other', 10);

-- --用户表---
drop table if exists user;
create table user
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    avatar          varchar(128) not null default '',
    name            varchar(32) not null default '',
    usertype        tinyint(4) not null default 0,
    authority       varchar(32) not null,
    status          tinyint(4) not null 
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --用户电车关联表---
drop table if exists ublink;
create table ublink
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    userid          int(11) not null,
    ebikeid         int(11) not null,
    is_delete       tinyint(4) default 0
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --安全认证表---
drop table if exists security;
create table security
(
    userid          int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    token           varchar(32) not null,
    passwd          varchar(64) not null,
    status          tinyint(4) not null default 0
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --用户信息表---
drop table if exists userinfo;
create table userinfo
(
    userid          int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    ebikeid         int(11) not null,
    mobileno        varchar(12) not null,
    email           varchar(32) not null default '',
    qq              varchar(32) not null default '',
    wechat          varchar(32) not null default '',
    gropid          int(11) default 0,
    status          tinyint(4) not null default 0,
    UNIQUE INDEX(mobileno)
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --骑士分组表---
drop table if exists kgrop;
create table kgrop
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    name            varchar(32) not null,
    laborid         int(11) not null,
    is_delete       tinyint(4) default 0   
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --公司表---
drop table if exists company;
create table company
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    name            varchar(64) not null,
    linkman         varchar(16) not null,
    mobileno        varchar(12) not null,
    email           varchar(32) not null default '',
    site            int(11) not null,
    registerid      varchar(16) not null,
    domain          varchar(16) not null,
    logo            varchar(128) not null,
    licence         varchar(128) not null,
    companytype     tinyint(4) not null,
    status          tinyint(4) not null,
    UNIQUE INDEX(name)
) engine = innodb DEFAULT CHARACTER SET UTF8;


-- --后台用户表---
drop table if exists adminuser;
create table adminuser
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    name            varchar(20) not null,
    password        varchar(64) not null
) engine = innodb DEFAULT CHARACTER SET UTF8; 

-- --城市信息表---
drop table if exists citycard;
create table citycard
(
    number          int(11) not null primary key,
    name            varchar(52) not null,
    parent          int(11) not null,
    level           int(11) not null,
    KEY parent (parent)
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --用户公司关联表---
drop table if exists uclink;
create table uclink
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    userid          int(11) not null,
    companyid       int(11) not null,
    is_delete       tinyint(4) not null default 0
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --平台劳务方关联表---
drop table if exists cllink;
create table cllink
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    userid          int(11) not null,
    platformid      int(11) not null,
    laborid         int(11) not null,
    is_delete       tinyint(4) not null
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --登陆日志表---
drop table if exists loginlog;
create table loginlog
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    userid          int(11) not null,
    loginip         varchar(16) not null,
    logintime       datetime,
    status          tinyint(4) default 0 
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --公司车辆关联表---
drop table if exists cblink;
create table cblink
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    ebikeid         int(11) not null,
    companyid       int(11) not null,
    useid           int(11) not null,
    distribute      tinyint(4) default -1,
    is_delete       tinyint(4) default 0
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --设备关联表---
drop table if exists link;
create table link
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    ebikeid         int(11) not null,
    sensorid        int(11) not null,
    status          tinyint not null default 0
) engine = innodb DEFAULT CHARACTER SET UTF8;
-- TDOD 加索引

-- --传感器表---
drop table if exists sensor;
create table sensor
(
    id 				int(11) not null primary key,
    createtime 		datetime not null,
    updatetime  	datetime not null,
    imei 			varchar(16) not null,
    mobileno 		varchar(16) default 0,
    imsi            varchar(16) not null,
    nextver         int(11) not null default 0,
    flag            tinyint not null default 0
) engine = innodb DEFAULT CHARACTER SET UTF8; 

-- --传感器软件包版本表---
drop table if exists sensorversion;
create table sensorversion
(
    code 		    int(11) not null primary key,
    createtime 		datetime not null,
    updatetime  	datetime not null,
    name            varchar(16) not null,
    downloadurl     varchar(128) not null,
    packgecount     int(11) not null,
    packagesize     int(11) not null,
    summd5          varchar(32) not null,
    flag            tinyint not null default 0
) engine = innodb DEFAULT CHARACTER SET UTF8; 

-- --电动车表---
drop table if exists ebike;
create table ebike
(
    id 				int(11) not null primary key,
    createtime		datetime not null,
    updatetime  	datetime not null,
    mobel 			varchar(12) not null,
    brand           varchar(16) not null,
    seqno           varchar(16) not null,
    remarks         varchar(64) default '',
    exception       tinyint(4) default 0,
    allot           tinyint(4) default -1,
    status          tinyint(4) default 2,
    UNIQUE INDEX (seqno)
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --电动车动态表---
drop table if exists ebikestate;
create table ebikestate
(
    sensorid 		int(11) not null primary key,
    createtime 		datetime not null,
    updatetime      datetime not null,
    ebikeid         int(11) not null,
    latitude		double(9,6) not null,
    longitude 		double(9,6) not null,
    batpercent		tinyint(4) default 0,
    voltage         double(3,1) default 0,
    electricity     double(3,2) default 0,
    geohash         varchar(32) not null,
    lastgeohash     varchar(32) not null
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --传感器配置信息表---
drop table if exists sensorconf;
create table sensorconf
(
    id 				int(11) not null primary key,
    createtime 		datetime not null,
    updatetime 		datetime not null,
    sensorid        int(11) not null,
    version 		int(11) not null,
    collectfreq     int(11) not null,
    freq 			smallint(6) not null,
    wi  			smallint(6) not null,
    wf 				smallint(6) not null,
    updatetype      int(11) default 0,
    is_delete       tinyint(4) default 0
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --传感器配置更改日志表---
drop table if exists conflog;
create table conflog
(
    id 				int(11) not null primary key,
    createtime 		datetime not null,
    updatetime 		datetime not null,
    sensorid 		int(11) not null,
    version 		int(11) not null,
    collectfreq     int(11) not null,
    freq 			smallint(6) not null,
    wi  			smallint(6) not null,
    wf 				smallint(6) not null,
    updatetype      int(11)  default 0,
    is_delete       tinyint(4) default 0
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --传感器密钥表---
drop table if exists sensorkey;
create table sensorkey
(
    sensorid        int(11) not null primary key,
    createtime 		datetime not null,
    updatetime 		datetime not null,
    signkey			varchar(64) not null
) engine = innodb DEFAULT CHARACTER SET UTF8;

-- --通告信息表---
drop table if exists callboard;
create table callboard
(
    id              int(11) not null primary key,
    createtime      datetime not null,
    updatetime      datetime not null,
    content         text not null,
    status          tinyint(4) default 0,
    is_delete       tinyint(4) default 0
) engine = innodb DEFAULT CHARACTER SET UTF8;
