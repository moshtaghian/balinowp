-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.22-MariaDB


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema bouali
--



--
-- Definition of table `cells`
--

DROP TABLE IF EXISTS `bwp_cells`;
CREATE TABLE  `bwp_cells` (
  `cells_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cells_fname` varchar(250) DEFAULT NULL,
  `cells_lname` varchar(250) DEFAULT NULL,
  `cells_mobile` varchar(11) NOT NULL,
  `cells_email` varchar(150) DEFAULT NULL,
  `cells_address` text,
  `cells_gender` tinyint(1) DEFAULT NULL,
  `cells_credit` varchar(250) DEFAULT NULL,
  `cells_smscode` int(6) DEFAULT NULL,
  `cells_validated` tinyint(1) DEFAULT '0',
  `cells_lastlogin` datetime DEFAULT NULL,
  `cells_smsdate` datetime DEFAULT NULL,
  `cells_register_date` datetime NOT NULL,
  PRIMARY KEY (`cells_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Definition of table `doctor`
--

DROP TABLE IF EXISTS `bwp_doctor`;
CREATE TABLE `bwp_doctor` (
  `doctor_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `doctor_fname` varchar(45) CHARACTER SET utf8 NOT NULL,
  `doctor_lname` varchar(45) CHARACTER SET utf8 NOT NULL,
  `doctor_nationalCode` varchar(10) NOT NULL,
  `doctor_age` int(10) unsigned DEFAULT NULL,
  `doctor_cell` varchar(11) NOT NULL,
  `doctor_cr_date` bigint(15) unsigned NOT NULL,
  `doctor_cr_user` int(11) unsigned NOT NULL,
  `doctor_edit_date` bigint(20) unsigned DEFAULT NULL,
  `doctor_edit_user` int(11) unsigned NOT NULL,
  PRIMARY KEY (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


--
-- Definition of table `patient`
--

DROP TABLE IF EXISTS `bwp_patient`;
CREATE TABLE `bwp_patient` (
  `patient_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `patient_fname` varchar(45) CHARACTER SET utf8 NOT NULL,
  `patient_lname` varchar(45) CHARACTER SET utf8 NOT NULL,
  `patient_gender` int(2) unsigned NOT NULL,
  `patient_cell` varchar(11) NOT NULL,
  `patient_age` int(10) unsigned NOT NULL,
  `patient_comment` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `patient_fileNumber` varchar(100) CHARACTER SET utf8 NOT NULL,
  `patient_cr_date` bigint(15) unsigned NOT NULL,
  `patient_cr_user` int(11) unsigned NOT NULL,
  `patient_edit_user` int(11) unsigned DEFAULT NULL,
  `patient_edit_date` bigint(20) unsigned DEFAULT NULL,
  `patient_coordinate` varchar(100) CHARACTER SET utf8 NOT NULL,
  `patient_address` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `patient_cells_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`patient_id`),
  KEY `FK_patient_user` (`patient_cells_id`) USING BTREE,
  CONSTRAINT `FK_patient_cells` FOREIGN KEY (`patient_cells_id`) REFERENCES `bwp_cells` (`cells_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Definition of table `service`
--

DROP TABLE IF EXISTS `bwp_service`;
CREATE TABLE `bwp_service` (
  `service_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `service_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `service_price` bigint(20) unsigned NOT NULL,
  `service_isMulti` tinyint(1) NOT NULL,
  `service_comment` varchar(300) CHARACTER SET utf8 DEFAULT NULL,
  `service_cr_date` bigint(15) unsigned NOT NULL,
  `service_cr_user` int(11) unsigned NOT NULL,
  `service_edit_date` bigint(15) unsigned DEFAULT NULL,
  `service_edit_user` int(11) unsigned DEFAULT NULL,
  `service_isVisit` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service`
--

/*!40000 ALTER TABLE `bwp_service` DISABLE KEYS */;
INSERT INTO `bwp_service` (`service_id`,`service_title`,`service_price`,`service_isMulti`,`service_comment`,`service_cr_date`,`service_cr_user`,`service_edit_date`,`service_edit_user`,`service_isVisit`) VALUES
 (1,'ویزیت',10000,0,NULL,0,0,NULL,NULL,1),
 (2,'سونداژ ادرار',10000,0,NULL,0,0,NULL,NULL,0),
 (3,'سونداژ معده',200000,0,NULL,0,0,NULL,NULL,0),
 (4,'تعویض پانسمان',100000,1,NULL,0,0,NULL,NULL,0),
 (5,'سرم',300000,1,NULL,0,0,NULL,NULL,0),
 (6,'تزریقات',100000,1,NULL,0,0,NULL,NULL,0);
/*!40000 ALTER TABLE `bwp_service` ENABLE KEYS */;


--
-- Definition of table `visit`
--

DROP TABLE IF EXISTS `bwp_visit`;
CREATE TABLE `bwp_visit` (
  `visit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visit_patient_id` int(11) unsigned NOT NULL,
  `visit_doctor_id` int(11) unsigned DEFAULT NULL,
  `visit_date` bigint(20) unsigned NOT NULL,
  `visit_time` varchar(45) NOT NULL,
  `visit_status` int(10) unsigned NOT NULL,
  `visit_comment` varchar(45) DEFAULT NULL,
  `visit_full_price` bigint(20) unsigned NOT NULL,
  `visit_cr_date` bigint(15) unsigned NOT NULL,
  `visit_cr_user` int(11) unsigned NOT NULL,
  `visit_edit_date` bigint(15) unsigned DEFAULT NULL,
  `visit_edit_user` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`visit_id`),
  KEY `FK_visit_doctor` (`visit_doctor_id`),
  KEY `FK_visit_patient` (`visit_patient_id`),
  CONSTRAINT `FK_visit_doctor` FOREIGN KEY (`visit_doctor_id`) REFERENCES `bwp_doctor` (`doctor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_visit_patient` FOREIGN KEY (`visit_patient_id`) REFERENCES `bwp_patient` (`patient_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Definition of table `visit_info`
--

DROP TABLE IF EXISTS `bwp_visit_info`;
CREATE TABLE `bwp_visit_info` (
  `visit_info_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visit_info_service_id` int(11) unsigned NOT NULL,
  `visit_info_visit_id` int(11) unsigned NOT NULL,
  `visit_info_service_num` int(10) unsigned NOT NULL,
  `visit_info_price` bigint(20) unsigned NOT NULL,
  `visit_info_cr_date` bigint(15) unsigned NOT NULL,
  `visit_info_cr_user` int(11) unsigned NOT NULL,
  `visit_info_edit_user` int(11) unsigned DEFAULT NULL,
  `visit_info_edit_date` bigint(15) unsigned DEFAULT NULL,
  PRIMARY KEY (`visit_info_id`),
  KEY `FK_visit_info_service` (`visit_info_service_id`),
  KEY `FK_visit_info_visit` (`visit_info_visit_id`),
  CONSTRAINT `FK_visit_info_service` FOREIGN KEY (`visit_info_service_id`) REFERENCES `bwp_service` (`service_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_visit_info_visit` FOREIGN KEY (`visit_info_visit_id`) REFERENCES `bwp_visit` (`visit_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;





/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
