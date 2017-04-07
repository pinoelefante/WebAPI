CREATE DATABASE IF NOT EXISTS `webapi` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `webapi`;

CREATE TABLE IF NOT EXISTS `log_request` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `_POST` text,
  `_GET` text,
  `_SERVER` text,
  `_SESSION` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- L’esportazione dei dati non era selezionata.
-- Dump della struttura di tabella postapp.log_response
CREATE TABLE IF NOT EXISTS `log_response` (
  `request_id` bigint(20) unsigned NOT NULL,
  `response` text,
  PRIMARY KEY (`request_id`),
  CONSTRAINT `FK_log_response_log_request` FOREIGN KEY (`request_id`) REFERENCES `log_request` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL,
  `password` varchar(60) NOT NULL,
  `firstName` varchar(20) DEFAULT NULL,
  `lastName` varchar(20) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `registration_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codice_utente` (`codice_utente`),
  KEY `FK_utente_comune` (`comune_residenza`),
  CONSTRAINT `FK_utente_comune` FOREIGN KEY (`comune_residenza`) REFERENCES `comune` (`istat`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `push_devices` (
  `id_user` int(11) unsigned NOT NULL,
  `token` text NOT NULL,
  `deviceOS` tinyint(3) unsigned NOT NULL COMMENT '1 android; 2 ios; 3 windows 10;',
  `deviceId` varchar(80) NOT NULL,
  KEY `FK_push_devices_user` (`id_user`),
  CONSTRAINT `FK_push_devices_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;