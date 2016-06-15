
set names utf8;
-- --基础服务---/*{{{*/

drop table if exists id_genter;
create table id_genter
(
    id             int(11) not null,
    obj            varchar(30),
    step           int(11)
) engine = innodb;

insert into id_genter(id, obj, step) values(65535, 'other', 10);
--/*}}}*/ 

-- MySQL dump 11.13  Distrib 5.5.47, for Linux (x86_64)
--
-- Host: localhost    Database: firstblood
-- ------------------------------------------------------
-- Server version	5.5.47-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40101 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `etc_ask`
--
-- MySQL dump 11.13  Distrib 5.5.47, for Linux (x86_64)
--
-- Host: localhost    Database: firstblood
-- ------------------------------------------------------
-- Server version 5.5.47-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40101 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `etc_ask`
--

DROP TABLE IF EXISTS `etc_ask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_ask` (
  `id` int(11) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_associated`
--

DROP TABLE IF EXISTS `etc_associated`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_associated` (
  `uid` int(11) unsigned NOT NULL,
  `jid` int(11) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into etc_associated(uid,jid) values(1,1);
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_categary`
--

DROP TABLE IF EXISTS `etc_categary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_categary` (
  `id` int(11) unsigned NOT NULL,
  `categary` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_class`
--

DROP TABLE IF EXISTS `etc_class`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_class` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(55) NOT NULL,
  `bid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15202 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_experience`
--

DROP TABLE IF EXISTS `etc_experience`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_experience` (
  `id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  `expercontent` varchar(200) NOT NULL,
  `sotime` varchar(33) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_heartclass`
--

DROP TABLE IF EXISTS `etc_heartclass`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_heartclass` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `heartname` varchar(110) DEFAULT NULL,
  `cid` int(11) unsigned NOT NULL,
  `lowpay` int(11) unsigned NOT NULL,
  `highpay` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16532 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_job`
--

DROP TABLE IF EXISTS `etc_job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_job` (
  `id` int(11) unsigned NOT NULL,
  `jobname` varchar(55) NOT NULL,
  `bid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_judge`
--

DROP TABLE IF EXISTS `etc_judge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_judge` (
  `id` int(11) unsigned NOT NULL,
  `content` varchar(55) NOT NULL,
  `correct` tinyint(4) DEFAULT '0',
  `cid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_kq`
--

DROP TABLE IF EXISTS `etc_kq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_kq` (
  `id` int(11) NOT NULL,
  `createtime` datetime DEFAULT NULL,
  `userid` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `firsttime` time DEFAULT NULL,
  `lasttime` time DEFAULT NULL,
  `status` enum('1','2','3','4','5','6','7','8','9','10') DEFAULT '1',
  `holiday` enum('1','2','3','4','5','6') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_payslip`
--

DROP TABLE IF EXISTS `etc_payslip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_payslip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL,
  `price` mediumint(8) unsigned NOT NULL,
  `kpi` mediumint(8) unsigned NOT NULL,
  `agemoney` mediumint(8) unsigned NOT NULL,
  `fund` mediumint(8) NOT NULL,
  `safe` mediumint(8) NOT NULL,
  `countwages` mediumint(8) NOT NULL,
  `ypay` mediumint(8) NOT NULL,
  `rpay` mediumint(8) NOT NULL,
  `ctime` date NOT NULL,
  `reward` mediumint(8) NOT NULL,
  `overtimepay` mediumint(8) unsigned NOT NULL,
  `late` mediumint(8) NOT NULL,
  `absent` mediumint(8) NOT NULL,
  `earlyleave` mediumint(8) NOT NULL,
  `toleave` mediumint(8) DEFAULT NULL,
  `little` mediumint(8) DEFAULT NULL,
  `score` mediumint(20) DEFAULT NULL,
  `houseprice` mediumint(8) DEFAULT NULL,
  `subsidyprice` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_peopleinfo`
--

DROP TABLE IF EXISTS `etc_peopleinfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_peopleinfo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(55) NOT NULL,
  `sex` enum('男','女') DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `wed` enum('已婚','未婚') DEFAULT NULL,
  `education` varchar(55) NOT NULL,
  `graduate` varchar(55) NOT NULL,
  `specialty` varchar(55) NOT NULL,
  `phone` char(23) NOT NULL,
  `actphone` char(23) NOT NULL,
  `idnumber` char(30) NOT NULL,
  `safe` enum('有','无') NOT NULL DEFAULT '无',
  `drive` enum('有','无') NOT NULL DEFAULT '无',
  `hiredate` char(22) NOT NULL,
  `pact` varchar(55) NOT NULL,
  `pacttime` char(22) DEFAULT NULL,
  `try` char(25) NOT NULL,
  `pactover` date DEFAULT NULL,
  `outtime` char(22) NOT NULL,
  `banknumber` char(32) DEFAULT NULL,
  `bid` int(11) unsigned NOT NULL,
  `xid` int(11) unsigned NOT NULL,
  `pactsigntime` date DEFAULT NULL,
  `email` varchar(55) NOT NULL,
  `del` tinyint(4) unsigned DEFAULT '1',
  `jid` int(11) unsigned NOT NULL,
  `lodge` enum('是','否') DEFAULT '否',
  `cpf` enum('有','无') DEFAULT '无',
  `nowaddress` varchar(55) NOT NULL,
  `photo` varchar(100) DEFAULT 'nophoto',
  `peoplestatus` enum("在职","停职","离职","孕假") default "在职",
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17672 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_rewards`
--

DROP TABLE IF EXISTS `etc_rewards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_rewards` (
  `id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  `time` date NOT NULL,
  `reason` varchar(88) NOT NULL,
  `flag` enum('1','-1') NOT NULL,
  `acount` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_role`
--

DROP TABLE IF EXISTS `etc_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_role` (
  `id` int(11) unsigned NOT NULL,
  `rolename` varchar(20) NOT NULL,
  `auth` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
insert into etc_role(id,rolename,auth) values(1,'root','-1');
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_selection`
--

DROP TABLE IF EXISTS `etc_selection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_selection` (
  `id` int(11) unsigned NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `content` varchar(255) NOT NULL,
  `correct` tinyint(4) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_skill`
--

DROP TABLE IF EXISTS `etc_skill`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_skill` (
  `id` int(11) unsigned NOT NULL,
  `skillcontent` varchar(66) NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_topic`
--

DROP TABLE IF EXISTS `etc_topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_topic` (
  `id` int(11) unsigned NOT NULL,
  `topic` varchar(200) NOT NULL,
  `type` int(11) unsigned NOT NULL,
  `cid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_userr`
--

DROP TABLE IF EXISTS `etc_userr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_userr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(16) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `yonghuming` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=17352 DEFAULT CHARSET=utf8;
insert into etc_userr(id,username,password) values(1,'root','83353d597cbad458989f2b1a5c1fa1f9f665c858');
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `etc_wages`
--

DROP TABLE IF EXISTS `etc_wages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etc_wages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jwages` varchar(20) default 0,
  `zwages` varchar(20) default 0,
  `addwork` varchar(20) default 0,
  `late` varchar(20) default 0,
  `earlyleave` varchar(20) default 0,
  `absent` varchar(20) default 0,
  `sale` varchar(20) default 0,
  `stick` varchar(20) default 0,
  `workagenum` varchar(20) default 0,
  `fund` varchar(20) default 0,
  `safe` varchar(20) default 0,
  `house` varchar(20) default 0,
  `subsidy` varchar(20) default 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17672 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` int(11) unsigned NOT NULL,
  `logcontent` varchar(120) NOT NULL,
  `happentime` datetime DEFAULT NULL,
  `uid` int(11) unsigned NOT NULL,
  `type` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `remarks`
--

DROP TABLE IF EXISTS `remarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `remarks` (
  `id` int(11) unsigned NOT NULL,
  `remarkcontent` varchar(66) NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `respondents`
--

DROP TABLE IF EXISTS `respondents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `respondents` (
  `id` int(11) unsigned NOT NULL,
  `uname` varchar(18) DEFAULT NULL,
  `phone` varchar(23) DEFAULT NULL,
  `score` varchar(20) DEFAULT NULL,
  `cid` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `showpaper`
--

DROP TABLE IF EXISTS `showpaper`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `showpaper` (
  `id` int(11) unsigned NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `asid` varchar(70) NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  `type` tinyint(4) unsigned NOT NULL,
  `score` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-16 16:39:51

DROP TABLE IF EXISTS `adnexa`;
CREATE TABLE `adnexa` (
  `id` int(11) unsigned NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  `adnexaname` varchar(100) NOT NULL,
  `adnexahash` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `capital`;
CREATE TABLE `capital` (
  `id` int(11) unsigned NOT NULL,
  `capitalname` varchar(90) NOT NULL,
  `argument` varchar(255) NOT NULL,
  `number` varchar(90) NOT NULL,
  `inputtime` date DEFAULT NULL,
  `cid` int(11) unsigned NOT NULL,
  `status` enum('T','F') DEFAULT 'T',
  `giveout` enum('T','F') DEFAULT 'F',
  `pname` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `capital`;
CREATE TABLE `capital` (
  `id` int(11) unsigned NOT NULL,
  `classname` varchar(90) NOT NULL,
  `prefix` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

