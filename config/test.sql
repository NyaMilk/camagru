USE Camagru;
DROP procedure IF EXISTS addMulti;
DELIMITER //

CREATE PROCEDURE addMulti ()
BEGIN
    DECLARE COUNTER INT;
    SET COUNTER = 0;

    WHILE COUNTER < 10000 DO
        INSERT INTO Photo (user_id, path) VALUES ('2', 'img/test/test2.jpg');
        SET COUNTER = COUNTER + 1;
    END WHILE;
END //

DELIMITER ;
call addMulti();