
DELIMITER $$

DROP procedure if EXISTS get_groupcats$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_groupcats`(IN param1 VARCHAR(200))
BEGIN
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
	CREATE TEMPORARY TABLE `temp_rows` AS
    SELECT
        `cat`,
        `price`,
        `towhom`,
        `forwhat`,
		`dt1`
    FROM
        `budget`
    WHERE
        `dt1` like concat(param1,'%');
    SELECT ANY_VALUE(cat) as group_cat,sum(price) as group_summ, count(price) as main_count, avg(price) as group_avg FROM temp_rows group by cat order by group_summ DESC;
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
END $$ 

DROP procedure if EXISTS get_grouptowhoms$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_grouptowhoms`(IN param1 VARCHAR(200))
BEGIN
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
	CREATE TEMPORARY TABLE `temp_rows` AS
    SELECT
        `cat`,
        `price`,
        `towhom`,
        `forwhat`,
		`dt1`
    FROM
        `budget`
    WHERE
        `dt1` like concat(param1,'%');
    SELECT ANY_VALUE(towhom) as group_towhom,sum(price) as group_summ, count(price) as main_count, avg(price) as group_avg FROM temp_rows group by towhom order by group_summ DESC;
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
END $$ 

DROP procedure if EXISTS get_groupforwhats$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_groupforwhats`(IN param1 VARCHAR(200))
BEGIN
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
	CREATE TEMPORARY TABLE `temp_rows` AS
    SELECT
        `cat`,
        `price`,
        `towhom`,
        `forwhat`,
		`dt1`
    FROM
        `budget`
    WHERE
        `dt1` like concat(param1,'%');
    SELECT ANY_VALUE(forwhat) as group_forwhat,sum(price) as group_summ, count(price) as main_count, avg(price) as group_avg FROM temp_rows group by forwhat order by group_summ DESC;
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
END $$ 

DROP procedure if EXISTS get_groupforwhatsfromcat$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_groupforwhatsfromcat`(IN param1 VARCHAR(200), param2 VARCHAR(200))
BEGIN
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
	CREATE TEMPORARY TABLE `temp_rows` AS
    SELECT
        `cat`,
        `price`,
        `towhom`,
        `forwhat`,
		`dt1`
    FROM
        `budget`
    WHERE
        `dt1` like concat(param1,'%') AND cat=param2;
    SELECT ANY_VALUE(forwhat) as group_forwhat,sum(price) as group_summ, count(price) as main_count, avg(price) as group_avg FROM temp_rows group by forwhat order by group_summ DESC;
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
END $$ 

DROP procedure if EXISTS get_groupdatecat$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_groupdatecat`(IN param1 VARCHAR(200), param2 VARCHAR(200))
BEGIN
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
	CREATE TEMPORARY TABLE `temp_rows` AS
    SELECT
        `cat`,
        `price`,
        `towhom`,
        `forwhat`,
		`dt1`
    FROM
        `budget`
    WHERE
        `dt1` like concat(param1,'%') AND cat=param2;
    SELECT year(dt1) as group_year, month(dt1) as group_month,sum(price) as group_summ FROM temp_rows group by 1,2 order by group_month ASC;
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
END $$ 


DROP procedure if EXISTS get_groupforwhatstowhomsfromcat$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_groupforwhatstowhomsfromcat`(IN param1 VARCHAR(200), param2 VARCHAR(200))
BEGIN
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
	CREATE TEMPORARY TABLE `temp_rows` AS
    SELECT
        `cat`,
        `price`,
        `towhom`,
        `forwhat`,
		`dt1`
    FROM
        `budget`
    WHERE
        `dt1` like concat(param1,'%') AND cat=param2;
    SELECT ANY_VALUE(forwhat) as group_forwhat,ANY_VALUE(towhom) as group_towhom,sum(price) as group_summ, count(price) as main_count, avg(price) as group_avg FROM temp_rows group by forwhat, towhom order by group_summ DESC;
	DROP TEMPORARY TABLE IF EXISTS `temp_rows`;
END $$ 

delimiter ;  