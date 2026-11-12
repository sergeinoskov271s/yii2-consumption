
use consumption
DROP TABLE IF EXISTS `costa`;
CREATE TABLE `costa` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,dt1 DATE,	
  `price` decimal(10,2) unsigned DEFAULT '0.00',
  `cat` varchar(200) collate utf8_unicode_ci NOT NULL DEFAULT '',
  `month` varchar(200) collate utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;



