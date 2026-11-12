DROP DATABASE IF EXISTS consumption;
CREATE DATABASE consumption DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
#GRANT ALL PRIVILEGES ON consumption.* TO 'user_db'@'localhost' identified by 'user_db';

use consumption
DROP TABLE IF EXISTS `outlay`;
CREATE TABLE `outlay` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,dt1 DATE,	
  `towhom` varchar(200) collate utf8_unicode_ci NOT NULL DEFAULT '',
  `forwhat` varchar(200) collate utf8_unicode_ci NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned DEFAULT '0.00',
  `cat` varchar(200) collate utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;



