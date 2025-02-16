DROP DATABASE IF EXISTS gravekeeperDB;
CREATE DATABASE gravekeeperDB;
use gravekeeperDB;

CREATE TABLE role(
    role_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

CREATE TABLE status(
    stat_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

CREATE TABLE bur_type(
    type_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

CREATE TABLE section(
    section_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32),
    sec_row INT,
    sec_col INT
);

CREATE TABLE user (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email char(64),
    password varchar(64),
    name varchar(32) NOT NULL,
    phone varchar(16),

    role_id INT NOT NULL,
    stat_id INT NOT NULL,
    CONSTRAINT user_role_id_fk FOREIGN KEY (role_id) REFERENCES role(role_id) ON DELETE CASCADE,
    CONSTRAINT user_stat_id_fk FOREIGN KEY (stat_id) REFERENCES status(stat_id) ON DELETE CASCADE
);


CREATE TABLE plot(
    plot_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    show_id INT NOT NULL,
    description varchar(32),

    sec_id INT NOT NULL,
    type_id INT NOT NULL,
    stat_id INT NOT NULL,

    CONSTRAINT plot_sec_id_fk FOREIGN KEY (sec_id) REFERENCES section(sec_id) ON DELETE CASCADE,
    CONSTRAINT plot_type_id_fk FOREIGN KEY (type_id) REFERENCES bur_type(type_id) ON DELETE CASCADE,
    CONSTRAINT plot_stat_id_fk FOREIGN KEY (stat_id) REFERENCES status(stat_id) ON DELETE CASCADE
);


INSERT INTO role(description)VALUES
('admin'),
('user');

INSERT INTO status(description)VALUES
('active'),
('deactivated');