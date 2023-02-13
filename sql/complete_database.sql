-- Progettazione Web 
DROP DATABASE if exists game_db; 
CREATE DATABASE game_db; 
USE game_db; 
-- MySQL dump 10.13  Distrib 5.7.28, for Win64 (x86_64)
--
-- Host: localhost    Database: game_db
-- ------------------------------------------------------
-- Server version	5.7.28

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
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `black` varchar(255) NOT NULL,
  `white` varchar(255) NOT NULL,
  `board` varchar(255) NOT NULL DEFAULT 'XXXXXXXXXXXXXXXXXXXXXXXXXXXBWXXXXXXWBXXXXXXXXXXXXXXXXXXXXXXXXXXX',
  `currentPlayer` varchar(255) NOT NULL DEFAULT 'white',
  `gameover` int(11) NOT NULL DEFAULT '0',
  `timer` int(11) NOT NULL DEFAULT '10',
  PRIMARY KEY (`black`,`white`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pending`
--

DROP TABLE IF EXISTS `pending`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pending` (
  `user` varchar(255) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pending`
--

LOCK TABLES `pending` WRITE;
/*!40000 ALTER TABLE `pending` DISABLE KEYS */;
/*!40000 ALTER TABLE `pending` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stats`
--

DROP TABLE IF EXISTS `stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stats` (
  `user` varchar(255) NOT NULL,
  `cpu_win` int(11) NOT NULL DEFAULT '0',
  `cpu_loss` int(11) NOT NULL DEFAULT '0',
  `online_win` int(11) NOT NULL DEFAULT '0',
  `online_loss` int(11) NOT NULL DEFAULT '0',
  `online_draw` int(11) NOT NULL DEFAULT '0',
  `cpu_draw` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stats`
--

LOCK TABLES `stats` WRITE;
/*!40000 ALTER TABLE `stats` DISABLE KEYS */;
INSERT INTO `stats` VALUES ('admin',0,0,1,1,0,0),('test1',4,2,6,6,0,0),('test2',1,0,5,6,1,0),('yughias',6,3,6,6,0,0);
/*!40000 ALTER TABLE `stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','$2y$10$MV/R3cjG8KVNW7uaJlp8HuIO83n.M4GWjaGkSHR3BOXCHzRsFURAC'),('test1','$2y$10$GK2DBkDC6zlg19JsQFTx6eZhoGYqsey2PVPxzzBxXlzSADvoNp9Sm'),('test2','$2y$10$xj6XG.9JhYKtXwO5dJslrOUOxXHWr4Wdz.UfrFwaR7E/yo4hoPgFe'),('yughias','$2y$10$gb2nT/X44BIev8JVdmCf2Okxi8QxXy61b0ghaNkOTv2GAoF2IkLoi');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-04 15:34:38


SET GLOBAL event_scheduler := 1;

DROP EVENT IF EXISTS decrease_timer;
CREATE EVENT decrease_timer
    ON SCHEDULE
      EVERY 1 SECOND
    DO
      UPDATE games SET timer = timer-1 WHERE timer <> -10;

DROP EVENT IF EXISTS remove_abbandoned_games;
DELIMITER //
CREATE EVENT remove_abbandoned_games
    ON SCHEDULE
      EVERY 1 SECOND
    DO
      BEGIN
        UPDATE stats
        SET online_loss = online_loss+1
        WHERE user IN (
          SELECT black
          FROM games
          WHERE timer = -10 AND currentPlayer = "black"
        ) OR user IN (
                        SELECT white
                        FROM games
                        WHERE timer = -10 AND currentPlayer = "white"
        );

        UPDATE stats
        SET online_win = online_win+1
        WHERE user IN (
          SELECT black
          FROM games
          WHERE timer = -10 AND currentPlayer = "white"
        ) OR user IN (
                        SELECT white
                        FROM games
                        WHERE timer = -10 AND currentPlayer = "black"
        );

        DELETE 
        FROM games
        WHERE timer = -10;
      END //
DELIMITER ;
