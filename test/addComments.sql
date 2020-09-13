USE Camagru;
DROP procedure IF EXISTS addMulti;
DELIMITER //

CREATE PROCEDURE addMulti ()
BEGIN
    DECLARE COUNTER INT;
    SET COUNTER = 0;

    WHILE COUNTER < 1000 DO
        INSERT INTO Comment (user_id, img_id, comment) VALUES (1, 2, COUNTER);
        SET COUNTER = COUNTER + 1;
    END WHILE;
END //

DELIMITER ;
call addMulti();