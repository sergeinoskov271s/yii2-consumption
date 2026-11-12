use consumption
DROP TABLE IF EXISTS `template`;
CREATE TABLE `template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,dt1 DATE,
  `towhom` varchar(200) collate utf8_unicode_ci NOT NULL DEFAULT '',
  `forwhat` varchar(200) collate utf8_unicode_ci NOT NULL DEFAULT '',
  `price` decimal(10,2) unsigned DEFAULT '0.00',
  `cat` varchar(200) collate utf8_unicode_ci NOT NULL DEFAULT '',
  `monthly` enum('yes','no') collate utf8_unicode_ci NOT NULL DEFAULT 'yeas',
  `month` int DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;
 
DELIMITER $$

//
delimiter ; 