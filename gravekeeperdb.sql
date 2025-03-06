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
    sec_name VARCHAR(32),
    sec_img TEXT,
    num_plot INT,
    min_price DECIMAL(10,2),
    max_price DECIMAL(10,2)
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
    description VARCHAR(32),

    section_id INT NOT NULL,
    stat_id INT NOT NULL,
    price DECIMAL(10,2),

    CONSTRAINT plot_section_id_fk FOREIGN KEY (section_id) REFERENCES section(section_id) ON DELETE CASCADE,
    CONSTRAINT plot_stat_id_fk FOREIGN KEY (stat_id) REFERENCES status(stat_id) ON DELETE CASCADE
);

CREATE TABLE deceased (
    dec_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    lname varchar(32) NOT NULL,
    fname varchar(32),
    date_born date,
    date_died date,
    picture varchar(128)
);

CREATE TABLE burial (
    burial_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    burial_date DATE NOT NULL,

    dec_id INT NOT NULL,
    plot_id INT NOT NULL,
    type_id INT NOT NULL,
    CONSTRAINT burial_dec_id_fk FOREIGN KEY (dec_id) REFERENCES deceased(dec_id) ON DELETE CASCADE,
    CONSTRAINT burial_plot_id_fk FOREIGN KEY (plot_id) REFERENCES plot(plot_id) ON DELETE CASCADE,
    CONSTRAINT burial_type_id_fk FOREIGN KEY (type_id) REFERENCES bur_type(type_id) ON DELETE CASCADE
);

CREATE TABLE reservation(
    reserv_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    date_placed date NOT NULL,
    date_reserved date,

    stat_id INT,
    section_id INT,
    plot_id INT,
    user_id INT NOT NULL,
    CONSTRAINT reservation_stat_id_fk FOREIGN KEY (stat_id) REFERENCES status(stat_id) ON DELETE CASCADE,
    CONSTRAINT reservation_section_id_fk FOREIGN KEY (section_id) REFERENCES section(section_id) ON DELETE CASCADE,
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

INSERT INTO `status` (`stat_id`, `description`) VALUES
(1, 'active'),
(2, 'deactivated'),
(3, 'available'),
(4, 'occupied'),
(5, 'pending'), 
(6, 'confirmed'),
(7, 'reserved'),
(8, 'soft-deleted');

INSERT INTO bur_type(description)VALUES
('unassigned'),
('buried'),
('cremated');

INSERT INTO user(email, password, name, phone, role_id, stat_id)VALUES
('marbella@gmail.com', 'd8cb704698c8d6e24e8be1f1f161c030238e0376', 'Sharwin', '09756324515', 1, 1), -- marbellasharwin
('yago@gmail.com', '5931ac353956df19fd34edb1dafa9a350d589981', 'Alvin', '09653548254', 1, 1), -- yagoalvinsymo
('manalo@gmail.com', '8a66bb8c84eec6ee3f0cce4d3eff2fab81e34fef', 'Jett', '09853224562', 1, 1), -- manalojettaxel
('jumoc@gmail.com', '7dd9ff017a73bbfe2c612450e7fb298ac7804330', 'Ernz', '09354528876', 1, 1), -- jumocernzrabbi
('piad@gmail.com', '0053b006c11e6c091991846c1c7ba39335818d4b', 'Evan', '09756235625', 2, 1); -- piadevancarl

INSERT INTO `section` (`section_id`, `sec_name`, `sec_img`, `num_plot`, `min_price`, `max_price`) VALUES
(1, 'Section 1', 'images/section1.png', 20, 500000, 1000000),
(2, 'Section 2', 'images/section2.png', 5, 500000, 1000000),
(3, 'Section 3', 'images/section3.png', 7, 500000, 1000000),
(4, 'Section 4', 'images/section4.png', 10, 500000, 1000000),
(5, 'Section 5', 'images/section5.png', 11, 100000, 400000),
(6, 'Section 6', 'images/section6.png', 16, 100000, 400000),
(7, 'Section 7', 'images/section7.png', 17, 100000, 400000),
(8, 'Section 8', 'images/section8.png', 10, 100000, 400000);

INSERT INTO `plot` (`plot_id`, `description`, `section_id`, `stat_id`, `price`) VALUES
(1, 'Plot 1', 1, 3, 750000.00),
(2, 'Plot 2', 1, 3, 780000.00),
(3, 'Plot 3', 1, 3, 800000.00),
(4, 'Plot 4', 1, 3, 820000.00),
(5, 'Plot 5', 1, 3, 850000.00),
(6, 'Plot 6', 1, 3, 870000.00),
(7, 'Plot 7', 1, 3, 890000.00),
(8, 'Plot 8', 1, 3, 910000.00),
(9, 'Plot 9', 1, 3, 930000.00),
(10, 'Plot 10', 1, 3, 950000.00),
(11, 'Plot 11', 1, 3, 970000.00),
(12, 'Plot 12', 1, 3, 990000.00),
(13, 'Plot 13', 1, 3, 1010000.00),
(14, 'Plot 14', 1, 3, 1030000.00),
(15, 'Plot 15', 1, 3, 1050000.00),
(16, 'Plot 16', 1, 3, 1070000.00),
(17, 'Plot 17', 1, 3, 1090000.00),
(18, 'Plot 18', 1, 3, 1110000.00),
(19, 'Plot 19', 1, 3, 1130000.00),
(20, 'Plot 20', 1, 3, 1150000.00),
(21, 'Plot 1', 2, 3, 700000.00),
(22, 'Plot 2', 2, 3, 720000.00),
(23, 'Plot 3', 2, 3, 740000.00),
(24, 'Plot 4', 2, 3, 760000.00),
(25, 'Plot 5', 2, 3, 780000.00),
(26, 'Plot 1', 3, 3, 650000.00),
(27, 'Plot 2', 3, 3, 670000.00),
(28, 'Plot 3', 3, 3, 690000.00),
(29, 'Plot 4', 3, 3, 710000.00),
(30, 'Plot 5', 3, 3, 730000.00),
(31, 'Plot 6', 3, 3, 750000.00),
(32, 'Plot 7', 3, 3, 770000.00),
(33, 'Plot 1', 4, 3, 600000.00),
(34, 'Plot 2', 4, 3, 620000.00),
(35, 'Plot 3', 4, 3, 640000.00),
(36, 'Plot 4', 4, 3, 660000.00),
(37, 'Plot 5', 4, 3, 680000.00),
(38, 'Plot 6', 4, 3, 700000.00),
(39, 'Plot 7', 4, 3, 720000.00),
(40, 'Plot 8', 4, 3, 740000.00),
(41, 'Plot 9', 4, 3, 760000.00),
(42, 'Plot 10', 4, 3, 780000.00),
(43, 'Plot 1', 5, 3, 550000.00),
(44, 'Plot 2', 5, 3, 570000.00),
(45, 'Plot 3', 5, 3, 590000.00),
(46, 'Plot 4', 5, 3, 610000.00),
(47, 'Plot 5', 5, 3, 630000.00),
(48, 'Plot 6', 5, 3, 650000.00),
(49, 'Plot 7', 5, 3, 670000.00),
(50, 'Plot 8', 5, 3, 690000.00),
(51, 'Plot 9', 5, 3, 710000.00),
(52, 'Plot 10', 5, 3, 730000.00),
(53, 'Plot 11', 5, 3, 750000.00),
(54, 'Plot 1', 6, 3, 400000.00),
(55, 'Plot 2', 6, 3, 420000.00),
(56, 'Plot 3', 6, 3, 440000.00),
(57, 'Plot 4', 6, 3, 460000.00),
(58, 'Plot 5', 6, 3, 480000.00),
(59, 'Plot 6', 6, 3, 500000.00),
(60, 'Plot 7', 6, 3, 520000.00),
(61, 'Plot 8', 6, 3, 540000.00),
(62, 'Plot 9', 6, 3, 560000.00),
(63, 'Plot 10', 6, 3, 580000.00),
(64, 'Plot 11', 6, 3, 600000.00),
(65, 'Plot 12', 6, 3, 620000.00),
(66, 'Plot 13', 6, 3, 640000.00),
(67, 'Plot 14', 6, 3, 660000.00),
(68, 'Plot 15', 6, 3, 680000.00),
(69, 'Plot 16', 6, 3, 700000.00),
(70, 'Plot 1', 7, 3, 350000.00),
(71, 'Plot 2', 7, 3, 370000.00),
(72, 'Plot 3', 7, 3, 390000.00),
(73, 'Plot 4', 7, 3, 410000.00),
(74, 'Plot 5', 7, 3, 430000.00),
(75, 'Plot 6', 7, 3, 450000.00),
(76, 'Plot 7', 7, 3, 470000.00),
(77, 'Plot 8', 7, 3, 490000.00),
(78, 'Plot 9', 7, 3, 510000.00),
(79, 'Plot 10', 7, 3, 530000.00),
(80, 'Plot 11', 7, 3, 550000.00),
(81, 'Plot 12', 7, 3, 570000.00),
(82, 'Plot 13', 7, 3, 590000.00),
(83, 'Plot 14', 7, 3, 610000.00),
(84, 'Plot 15', 7, 3, 630000.00),
(85, 'Plot 16', 7, 3, 650000.00),
(86, 'Plot 17', 7, 3, 670000.00),
(87, 'Plot 1', 8, 3, 300000.00),
(88, 'Plot 2', 8, 3, 320000.00),
(89, 'Plot 3', 8, 3, 340000.00),
(90, 'Plot 4', 8, 3, 360000.00),
(91, 'Plot 5', 8, 3, 380000.00),
(92, 'Plot 6', 8, 3, 400000.00),
(93, 'Plot 7', 8, 3, 420000.00),
(94, 'Plot 8', 8, 3, 440000.00),
(95, 'Plot 9', 8, 3, 460000.00),
(96, 'Plot 10', 8, 3, 480000.00);

