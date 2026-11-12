
DELIMITER $$

DROP procedure if EXISTS get_cats$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_cats`(IN param1 VARCHAR(200))
BEGIN
    SELECT cat,COUNT(*) as main_count, SUM(price) as total_summ FROM outlay where dt1=param1 group by cat order by cat;
END $$

DROP procedure if EXISTS get_cat$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_cat`(IN param1 VARCHAR(200), param3 VARCHAR(64))
BEGIN
    SELECT towhom,COUNT(*) as main_count, SUM(price) as total_summ FROM outlay where dt1 like concat(param1,'%') AND cat=param3 group by towhom  order by towhom;
END $$

DROP procedure if EXISTS get_catsyear$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_catsyear`(IN param1 VARCHAR(200))
BEGIN
    SELECT cat,COUNT(*) as main_count, SUM(price) as total_summ FROM outlay where dt1 like concat(param1,'%')  group by cat  order by cat;
END $$

DROP procedure if EXISTS get_forwhat$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_forwhat`(IN param1 VARCHAR(200), param3 VARCHAR(64))
BEGIN
    SELECT towhom,COUNT(*) as main_count, SUM(price) as total_summ FROM outlay where dt1 like concat(param1,'%') AND forwhat=param3 group by towhom  order by towhom;
END $$

DROP procedure if EXISTS get_towhom$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_towhom`(IN param1 VARCHAR(200), param3 VARCHAR(64))
BEGIN
    SELECT forwhat,COUNT(*) as main_count, SUM(price) as total_summ FROM outlay where dt1 like concat(param1,'%') AND towhom=param3 group by forwhat  order by forwhat;
END $$


DROP procedure if EXISTS get_cats_year$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_cats_year`(IN param1 VARCHAR(200))
BEGIN

    SELECT cat,COUNT(*) as main_count, SUM(price) as total_summ FROM outlay where dt1 like concat(param1,'%') group by cat order by cat;
	
END $$

DELIMITER ;
delimiter //
DROP FUNCTION IF EXISTS sp_month_year//

CREATE FUNCTION sp_month_year(param2 VARCHAR(24)) RETURNS INT DETERMINISTIC
BEGIN
    DECLARE month_count INT DEFAULT 0;
    select count(distinct month(dt1)) into month_count FROM outlay where dt1 like concat(param2,'%') group by cat order by cat LIMIT 1;
    
    
    RETURN  month_count;
END;
//
delimiter ; 