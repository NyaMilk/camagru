DROP DATABASE IF EXISTS camagru;
CREATE DATABASE camagru;
-- GRANT USAGE ON *.* TO 'super'@'localhost';
-- DROP USER 'super'@'localhost';
-- CREATE USER 'super'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON camagru.* TO 'super'@'localhost' IDENTIFIED BY '1234';