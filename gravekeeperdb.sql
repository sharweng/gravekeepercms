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

<<<<<<< HEAD
INSERT INTO status(description)VALUES
('active'),
('deactivated'),
('free'),
('occupied');

INSERT INTO bur_type(bur_img, description)VALUES
(NULL, 'unassigned'),
(NULL, 'normal'),
(NULL, 'create');

=======

INSERT INTO `status` (`stat_id`, `description`) VALUES
(1, 'active'),
(2, 'deactivated'),
(3, 'available'),
(4, 'occupied'),
(5, 'pending'),
(6, 'confirmed');

INSERT INTO bur_type(bur_img, description)VALUES
(NULL, 'unassigned'),
(NULL, 'buried'),
(NULL, 'cremated');
>>>>>>> 8ecb094 (jett reservation and finished plots)

INSERT INTO user(email, password, name, phone, role_id, stat_id)VALUES
('marbella@gmail.com', 'd8cb704698c8d6e24e8be1f1f161c030238e0376', 'Sharwin', '09756324515', 1, 1), -- marbellasharwin
('yago@gmail.com', '5931ac353956df19fd34edb1dafa9a350d589981', 'Alvin', '09653548254', 1, 1), -- yagoalvinsymo
('manalo@gmail.com', '8a66bb8c84eec6ee3f0cce4d3eff2fab81e34fef', 'Jett', '09853224562', 1, 1), -- manalojettaxel
<<<<<<< HEAD
('jumoc@gmail.com', '7dd9ff017a73bbfe2c612450e7fb298ac7804330', 'Ernz', '09354528876', 1, 1); -- jumocernzrabbi
=======
('jumoc@gmail.com', '7dd9ff017a73bbfe2c612450e7fb298ac7804330', 'Ernz', '09354528876', 1, 1); -- jumocernzrabbi

INSERT INTO `section` (`section_id`, `sec_name`, `description`, `sec_img`, `num_plot`) VALUES
(1, 'Section 1', 'Aywan', 'images/section1.png', 20),
(2, 'Section 2', 'Di ko alam', 'images/section2.png', 5),
(3, 'Section 3', 'Bahala na', 'images/section3.png', 7),
(4, 'Section 4', 'I don\'t know', 'images/section4.png', 10),
(5, 'Section 5', 'Don\'t know', 'images/section5.png', 11),
(6, 'Section 6', 'Ernz', 'images/section6.png', 16),
(7, 'Section 7', 'Pogi', 'images/section7.png', 17),
(8, 'Section 8', 'wohoi', 'images/section8.png', 10);

INSERT INTO `plot` (`plot_id`, `description`, `section_id`, `type_id`, `stat_id`) VALUES
(1, 'Plot 1', 1, 1, 3),
(2, 'Plot 2', 1, 1, 3),
(3, 'Plot 3', 1, 1, 3),
(4, 'Plot 4', 1, 1, 3),
(5, 'Plot 5', 1, 1, 3),
(6, 'Plot 6', 1, 1, 3),
(7, 'Plot 7', 1, 1, 3),
(8, 'Plot 8', 1, 1, 3),
(9, 'Plot 9', 1, 1, 3),
(10, 'Plot 10', 1, 1, 3),
(11, 'Plot 11', 1, 1, 3),
(12, 'Plot 12', 1, 1, 3),
(13, 'Plot 13', 1, 1, 3),
(14, 'Plot 14', 1, 1, 3),
(15, 'Plot 15', 1, 1, 3),
(16, 'Plot 16', 1, 1, 3),
(17, 'Plot 17', 1, 1, 3),
(18, 'Plot 18', 1, 1, 3),
(19, 'Plot 19', 1, 1, 3),
(20, 'Plot 20', 1, 1, 3),
(21, 'Plot 1', 2, 1, 3),
(22, 'Plot 2', 2, 1, 3),
(23, 'Plot 3', 2, 1, 3),
(24, 'Plot 4', 2, 1, 3),
(25, 'Plot 5', 2, 1, 3),
(26, 'Plot 1', 3, 1, 3),
(27, 'Plot 2', 3, 1, 3),
(28, 'Plot 3', 3, 1, 3),
(29, 'Plot 4', 3, 1, 3),
(30, 'Plot 5', 3, 1, 3),
(31, 'Plot 6', 3, 1, 3),
(32, 'Plot 7', 3, 1, 3),
(33, 'Plot 1', 4, 1, 3),
(34, 'Plot 2', 4, 1, 3),
(35, 'Plot 3', 4, 1, 3),
(36, 'Plot 4', 4, 1, 3),
(37, 'Plot 5', 4, 1, 3),
(38, 'Plot 6', 4, 1, 3),
(39, 'Plot 7', 4, 1, 3),
(40, 'Plot 8', 4, 1, 3),
(41, 'Plot 9', 4, 1, 3),
(42, 'Plot 10', 4, 1, 3),
(43, 'Plot 1', 5, 1, 3),
(44, 'Plot 2', 5, 1, 3),
(45, 'Plot 3', 5, 1, 3),
(46, 'Plot 4', 5, 1, 3),
(47, 'Plot 5', 5, 1, 3),
(48, 'Plot 6', 5, 1, 3),
(49, 'Plot 7', 5, 1, 3),
(50, 'Plot 8', 5, 1, 3),
(51, 'Plot 9', 5, 1, 3),
(52, 'Plot 10', 5, 1, 3),
(53, 'Plot 11', 5, 1, 3),
(54, 'Plot 1', 6, 1, 3),
(55, 'Plot 2', 6, 1, 3),
(56, 'Plot 3', 6, 1, 3),
(57, 'Plot 4', 6, 1, 3),
(58, 'Plot 5', 6, 1, 3),
(59, 'Plot 6', 6, 1, 3),
(60, 'Plot 7', 6, 1, 3),
(61, 'Plot 8', 6, 1, 3),
(62, 'Plot 9', 6, 1, 3),
(63, 'Plot 10', 6, 1, 3),
(64, 'Plot 11', 6, 1, 3),
(65, 'Plot 12', 6, 1, 3),
(66, 'Plot 13', 6, 1, 3),
(67, 'Plot 14', 6, 1, 3),
(68, 'Plot 15', 6, 1, 3),
(69, 'Plot 16', 6, 1, 3),
(70, 'Plot 1', 7, 1, 3),
(71, 'Plot 2', 7, 1, 3),
(72, 'Plot 3', 7, 1, 3),
(73, 'Plot 4', 7, 1, 3),
(74, 'Plot 5', 7, 1, 3),
(75, 'Plot 6', 7, 1, 3),
(76, 'Plot 7', 7, 1, 3),
(77, 'Plot 8', 7, 1, 3),
(78, 'Plot 9', 7, 1, 3),
(79, 'Plot 10', 7, 1, 3),
(80, 'Plot 11', 7, 1, 3),
(81, 'Plot 12', 7, 1, 3),
(82, 'Plot 13', 7, 1, 3),
(83, 'Plot 14', 7, 1, 3),
(84, 'Plot 15', 7, 1, 3),
(85, 'Plot 16', 7, 1, 3),
(86, 'Plot 17', 7, 1, 3),
(87, 'Plot 1', 8, 1, 3),
(88, 'Plot 2', 8, 1, 3),
(89, 'Plot 3', 8, 1, 3),
(90, 'Plot 4', 8, 1, 3),
(91, 'Plot 5', 8, 1, 3),
(92, 'Plot 6', 8, 1, 3),
(93, 'Plot 7', 8, 1, 3),
(94, 'Plot 8', 8, 1, 3),
(95, 'Plot 9', 8, 1, 3),
(96, 'Plot 10', 8, 1, 3);

>>>>>>> 8ecb094 (jett reservation and finished plots)
