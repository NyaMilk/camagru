DROP DATABASE IF EXISTS camagru;
CREATE DATABASE camagru;
USE camagru;

DROP TABLE IF EXISTS Users;
CREATE TABLE Users
(
    user_id SERIAL,
    name VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(512) NOT NULL,
    confirm ENUM('no', 'yes') NOT NULL DEFAULT 'no', /* default */
    hash VARCHAR(512),
    status VARCHAR(255) DEFAULT 'user',
    avatar VARCHAR (255) DEFAULT 'img/icon/user.svg',
    notification ENUM('no', 'yes') NOT NULL DEFAULT 'yes',
    description_user VARCHAR (255) DEFAULT 'About me...',
    created_at_user DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id)
);

DROP TABLE IF EXISTS Photo;
CREATE TABLE Photo
(
    img_id SERIAL,
    user_id BIGINT UNSIGNED NOT NULL,
    likes INT UNSIGNED DEFAULT 0,
    path VARCHAR (255),
    description_photo VARCHAR (255),
    created_at_photo DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (img_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Comment;
CREATE TABLE Comment
(
	comment_id SERIAL,
	user_id BIGINT UNSIGNED NOT NULL,
	img_id BIGINT UNSIGNED NOT NULL,
	comment TEXT,
    created_at_comment DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (comment_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (img_id) REFERENCES Photo(img_id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Likes;
CREATE TABLE Likes
(
	user_id BIGINT UNSIGNED NOT NULL,
	img_id BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (img_id) REFERENCES Photo(img_id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS Views;
CREATE TABLE Views
(
    views_id SERIAL,
    img_id BIGINT UNSIGNED NOT NULL,
    counter INT UNSIGNED DEFAULT 0,
    date_views DATE NOT NULL,
    PRIMARY KEY (views_id)
    -- FOREIGN KEY (img_id) REFERENCES Photo(img_id) ON DELETE CASCADE
);


INSERT INTO Users (name, email, password, confirm) VALUES
('admin', 'admin@localhost.ru', SHA2('XyZzy12*_123', 512), 'yes'),
('kusmene', 'kus@mene.ru', SHA2('XyZzy12*_456', 512), 'yes'),
('kitik', 'kiti@test.com', SHA2('XyZzy12*_789', 512), 'no');

UPDATE Users SET status='admin' WHERE name='admin';

INSERT INTO Photo (user_id, path) VALUES
('2', 'img/test/test2.jpg'),
('2', 'img/test/test3.jpg'),
('2', 'img/test/test4.jpg'),
('2', 'img/test/test2.jpg'),
('2', 'img/test/test3.jpg'),
('2', 'img/test/test4.jpg'),
('2', 'img/test/test2.jpg'),
('2', 'img/test/test3.jpg'),
('2', 'img/test/test4.jpg'),
('3', 'img/test/test5.jpg'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test5.jpg'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test5.jpg'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test7.jpg'),
('3', 'img/test/test8.jpg'),
('3', 'img/test/test9.png'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test7.jpg'),
('3', 'img/test/test8.jpg'),
('3', 'img/test/test9.png'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test7.jpg'),
('3', 'img/test/test8.jpg'),
('3', 'img/test/test9.png'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test7.jpg'),
('3', 'img/test/test8.jpg'),
('3', 'img/test/test9.png'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test7.jpg'),
('3', 'img/test/test8.jpg'),
('3', 'img/test/test9.png'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test7.jpg'),
('3', 'img/test/test8.jpg'),
('3', 'img/test/test9.png'),
('3', 'img/test/test6.jpg'),
('3', 'img/test/test7.jpg'),
('3', 'img/test/test8.jpg'),
('3', 'img/test/test9.png');

INSERT INTO Photo (user_id, path) VALUES ('1', 'img/test/th.jpg');
INSERT INTO Photo (user_id, path) VALUES ('1', 'img/test/tw.jpg');

INSERT INTO Users (name, email, password, confirm) VALUES ('test', 'test@test.ru', SHA2('XyZzy12*_123', 512), 'yes');