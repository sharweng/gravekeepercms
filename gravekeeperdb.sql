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
    bur_img text,
    description varchar(32)
);

CREATE TABLE section(
    section_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    sec_name varchar(32),
    description varchar(32),
    sec_img text,
    num_plot INT
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
    description varchar(32),

    section_id INT NOT NULL,
    type_id INT NOT NULL,
    stat_id INT NOT NULL,

    CONSTRAINT plot_section_id_fk FOREIGN KEY (section_id) REFERENCES section(section_id) ON DELETE CASCADE,
    CONSTRAINT plot_type_id_fk FOREIGN KEY (type_id) REFERENCES bur_type(type_id) ON DELETE CASCADE,
    CONSTRAINT plot_stat_id_fk FOREIGN KEY (stat_id) REFERENCES status(stat_id) ON DELETE CASCADE
);

CREATE TABLE reservation(
    reserv_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    date_placed date NOT NULL,
    date_reserved date,

    stat_id INT,
    plot_id INT,
    user_id INT NOT NULL,
    CONSTRAINT reservation_stat_id_fk FOREIGN KEY (stat_id) REFERENCES status(stat_id) ON DELETE CASCADE,
    CONSTRAINT reservation_plot_id_fk FOREIGN KEY (plot_id) REFERENCES plot(plot_id) ON DELETE CASCADE,
    CONSTRAINT reservation_user_id_fk FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

CREATE TABLE review(
    rev_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    rev_num INT,
    rev_msg TEXT,

    user_id INT NOT NULL,
    CONSTRAINT review_user_id FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);




INSERT INTO role(description)VALUES
('admin'),
('user');

INSERT INTO status(description)VALUES
('active'),
('deactivated'),
('free'),
('occupied');

INSERT INTO bur_type(bur_img, description)VALUES
(NULL, 'unassigned'),
(NULL, 'normal'),
(NULL, 'create');


INSERT INTO user(email, password, name, phone, role_id, stat_id)VALUES
('marbella@gmail.com', 'd8cb704698c8d6e24e8be1f1f161c030238e0376', 'Sharwin', '09756324515', 1, 1), -- marbellasharwin
('yago@gmail.com', '5931ac353956df19fd34edb1dafa9a350d589981', 'Alvin', '09653548254', 1, 1), -- yagoalvinsymo
('manalo@gmail.com', '8a66bb8c84eec6ee3f0cce4d3eff2fab81e34fef', 'Jett', '09853224562', 1, 1), -- manalojettaxel
('jumoc@gmail.com', '7dd9ff017a73bbfe2c612450e7fb298ac7804330', 'Ernz', '09354528876', 1, 1); -- jumocernzrabbi