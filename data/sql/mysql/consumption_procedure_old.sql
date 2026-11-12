
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `sp_phone_number`(phone VARCHAR(24)) RETURNS int(11)
    DETERMINISTIC
BEGIN
    RETURN REPLACE(phone, '-','') *1;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE DEFINER=`root`@`localhost` FUNCTION `sp_room_number`(room VARCHAR(5)) RETURNS int(11)
    DETERMINISTIC
BEGIN
    RETURN LEFT(room, 3) *1;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_room`(IN param1 VARCHAR(5))
BEGIN
    DECLARE xname VARCHAR(5) DEFAULT 'bob';
    DECLARE record_count INT;    

    CREATE TEMPORARY TABLE users_by_results
	SELECT id,enterprise,department,first_name,name_name,last_name,position,phone,phone1
	    FROM listing
	    WHERE room=param1;

    SELECT COUNT(id) INTO record_count
	FROM users_by_results;

    IF record_count = 0 THEN
	SELECT 0 as id, "Not data" AS TITLE;
    ELSE
	SELECT * 
	    FROM users_by_results;
    END IF;	
		
    DROP TABLE IF EXISTS users_by_results;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_users_by_room`(IN param1 VARCHAR(5), OUT record_count INT)
BEGIN
    DECLARE xname VARCHAR(5) DEFAULT 'bob';
    
    CREATE TEMPORARY TABLE users_by_results
	SELECT *
	    FROM listing
	    WHERE room=param1;

    SELECT COUNT(*) INTO record_count
	FROM users_by_results;

    IF record_count = 0 THEN
	SELECT 0 as id, "Not data" AS TITLE;
    ELSE
	SELECT * 
	    FROM users_by_results;
    END IF;	
		
    DROP TABLE IF EXISTS users_by_results;
END ;;
DELIMITER ;

DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `test`(IN param1 VARCHAR(5))
BEGIN
    SELECT COUNT(*) FROM listing where room=param1;
END ;;
DELIMITER ;

