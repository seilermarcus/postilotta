-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: postilotta_msgng
-- ------------------------------------------------------
-- Server version	5.7.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Inbox`
--

DROP TABLE IF EXISTS `Inbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inbox` (
  `BoxID` int(11) NOT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `PubKey` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`BoxID`),
  UNIQUE KEY `Address` (`Address`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Inbox`
--

LOCK TABLES `Inbox` WRITE;
/*!40000 ALTER TABLE `Inbox` DISABLE KEYS */;
INSERT INTO `Inbox` VALUES (15182036,'wikileaks','FMuyQWPS%2BR7Ylm1TEUwnB1HABxV34Ey3GrqhL79BNOk4NDas7aizYpLweetceJTI7e0XTSn%2F2I11Ljrh77qvhA%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(55111016,'amnesty-international','8bh9luyAiv5iCqm7ARNSGz4GtT2O47TBTdRAXsL4GvMGcqsdrVn7fhhrbKdq28snYw6vFpvEo8uLobmq9Fq%2BxA%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(169720857,'new','Fhefu56533dgr%2FdgCQ80pbeEjbY4Rce5hIEYVq%2BctyrLwsb6DJNk8zTK0WX%2Bgnvdx6bjYboDCYMl8CWKNGyCXw%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(173244611,'survey2017@vw-europe','LQGTdtZDFu%2FyZo3B20iUOQFQ9WgsJI5yNiyMDv0My7YGZGqP55SA7UB5OXTwQ%2BO416cdm%2FzRlkegKUAmjI%2BUCA%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(383223723,'tax-authority-swiss','o5CZlYVdlWnN6JSupePLWqeQohOzJwa5UZ0b9EktT10lKl7jYVWtRQi%2Bwp5NOhDTsbfeleQAkwjpu7uq%2Fv3Ckg%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(502057861,'weiÃŸer-ring','osnhsEE5Gd6UAHvREM69eSyDq8Ia0F3vDxJItYpUxp%2BSrl2yBDfFVmXK5BvJWYwlFhjaIb6LPajWPv%2FN8z4VrQ%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(517364725,'1','DAdSzqREEo9xYh7zveqkbpAA6Debz%2ByleiyQqQ9Fn6PINZQ3WXetaLu8tvOdKitYgZCbEq3QOuKRWocEhrN4zA%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(623268423,'polizei-rlp','7kck19Pk6gwY2f3IuBIoMzlnDkpm%2FCK6cVpEvJXnszUvGWZfBRKHOG3osJyyRw%2B6WCHx1LGACWQ4lWvkj4rQ6w%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(778350801,'frauenhaus-berlin','c4U3%2ByHAMPJ5%2Bf1hdATMnesHu%2BlctFQ8ajTbZSb%2FDPzukJxZs5PQ6gGl6WQc6eJTfpjJraaCTuY2qJmX0k%2BtDg%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',''),(834683367,'nypd','zQA7BzbiZJn8vTWgvCEPUKJPnPI%2BaXZz9MQ%2BR%2F1qlIDtLAF6rKhfmuVovtLqb2QII4zBi%2BYMksNT5QmS4EEGdw%3D%3D','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','');
/*!40000 ALTER TABLE `Inbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Message`
--

DROP TABLE IF EXISTS `Message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Message` (
  `MsgID` int(11) NOT NULL,
  `Recipient` varchar(255) DEFAULT NULL,
  `Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `State` varchar(255) DEFAULT 'NEW',
  `Content` mediumblob,
  `ReturnPubKey` blob,
  `ReturnLink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MsgID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Message`
--

LOCK TABLES `Message` WRITE;
/*!40000 ALTER TABLE `Message` DISABLE KEYS */;
/*!40000 ALTER TABLE `Message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Paranoia`
--

DROP TABLE IF EXISTS `Paranoia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Paranoia` (
  `PLink` varchar(255) NOT NULL,
  `Passphrase` varchar(255) DEFAULT NULL,
  `Watchword` varchar(255) DEFAULT NULL,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`PLink`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Paranoia`
--

LOCK TABLES `Paranoia` WRITE;
/*!40000 ALTER TABLE `Paranoia` DISABLE KEYS */;
/*!40000 ALTER TABLE `Paranoia` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-09-06  8:30:17
