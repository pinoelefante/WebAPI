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