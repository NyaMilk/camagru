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
    created_at_like DATETIME DEFAULT CURRENT_TIMESTAMP,
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

DROP TABLE IF EXISTS Stickers;
CREATE TABLE Stickers
(
    sticker_id SERIAL,
    path VARCHAR(255),
    PRIMARY KEY (sticker_id)
);

DROP TABLE IF EXISTS Filters;
CREATE TABLE Filters
(
    filter_id SERIAL,
    path VARCHAR(255),
    PRIMARY KEY (filter_id)
);

-- INSERT INTO Stickers (path) VALUES
-- ("img/stickers/1.png"),
-- ("img/stickers/2.png"),
-- ("img/stickers/3.png"),
-- ("img/stickers/4.png"),
-- ("img/stickers/5.png");
INSERT INTO Stickers (path) VALUES
("img/stickers/var\ II/angry.svg"),
("img/stickers/var\ II/coffee.svg"),
("img/stickers/var\ II/crying.svg"),
("img/stickers/var\ II/king.svg"),
("img/stickers/var\ II/sick.svg"),
("img/stickers/var\ II/bathing.svg"),
("img/stickers/var\ II/cold.svg"),
("img/stickers/var\ II/rainbow.svg"),
("img/stickers/var\ II/in-love.svg"),
("img/stickers/var\ II/laughing.svg"),
("img/stickers/var\ II/pirate.svg");

INSERT INTO Filters (path) VALUES
("img/filters/1.png"),
("img/filters/2.png"),
("img/filters/3.png"),
("img/filters/4.png"),
("img/filters/5.png");


INSERT INTO Users (name, email, password, confirm) VALUES
('mgrass', 'amilyukovadev@gmail.com', SHA2('XyZzy12*_123', 512), 'yes');

INSERT INTO Photo (user_id, path) VALUES
('3', 'img/test/test9.png');

-- INSERT INTO Photo (user_id, path) VALUES ('1', 'img/test/th.jpg');
-- INSERT INTO Photo (user_id, path) VALUES ('1', 'img/test/tw.jpg');

-- INSERT INTO Users (name, email, password, confirm) VALUES ('kus', 'nyamilk@yandex.ru', SHA2('XyZzy12*_1', 512), 'yes');
-- INSERT INTO Users (name, email, password, confirm) VALUES ('testtest', 'test@test', SHA2('XyZzy12*_1', 512), 'no');
