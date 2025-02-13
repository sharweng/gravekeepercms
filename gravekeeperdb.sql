DROP DATABASE IF EXISTS gravekeeperDB;
CREATE DATABASE gravekeeperDB;
use gravekeeperDB;

CREATE TABLE user (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email char(64),
    password varchar(64),
    name varchar(32) NOT NULL,
    phone varchar(16),
    role_id INT NOT NULL,
    stat_id INT NOT NULL
);

CREATE TABLE role(
    role_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

CREATE TABLE status(
    stat_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

INSERT INTO role(description)VALUES
('admin'),
('user');

INSERT INTO status(description)VALUES
('active'),
('deactivated');