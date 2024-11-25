
CREATE DATABASE IF NOT EXISTS `crud-php-app`;
USE `crud-php-app`;

DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_role_module`$$
CREATE PROCEDURE `sp_role_module` (IN `idRole` INT)   BEGIN
SELECT ROL.role_name AS `role_fk`,MD.module_name AS `role_module`,MD.module_icon,MD.module_description, MD.module_route FROM role_module AS RM 
INNER JOIN role AS ROL ON RM.role_fk=ROL.role_id
INNER JOIN module AS MD ON RM.module_fk=MD.module_id
WHERE ROL.role_id=idRole;
END$$

DROP PROCEDURE IF EXISTS `sp_user_all`$$
CREATE PROCEDURE `sp_user_all` ()   BEGIN
SELECT `user_id`,`user_user`,`user_password`, UST.userStatus_name AS `userStatus_fk`, ROL.role_name AS `role_fk` FROM `user` AS US  
INNER JOIN role AS ROL ON US.role_fk=ROL.role_id
INNER JOIN userstatus AS UST  ON US.userStatus_fk=UST.userStatus_id;
END$$

DROP PROCEDURE IF EXISTS `sp_user_search`$$
CREATE PROCEDURE `sp_user_search` (IN `dataSearch` VARCHAR(60))   BEGIN
SELECT `user_id`,`user_user`,`user_password`, UST.userStatus_name AS `userStatus_fk`, ROL.role_name AS `role_fk` FROM `user` AS US  
INNER JOIN role AS ROL ON US.role_fk=ROL.role_id
INNER JOIN userstatus AS UST  ON US.userStatus_fk=UST.userStatus_id 
WHERE ROL.role_name=dataSearch OR UST.userStatus_name=dataSearch OR US.user_user=dataSearch;
END$$

DELIMITER ;


DROP TABLE IF EXISTS `module`;
CREATE TABLE IF NOT EXISTS `module` (
  `module_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(20) NOT NULL,
  `module_route` varchar(20) NOT NULL,
  `module_icon` varchar(40) NOT NULL,
  `module_description` varchar(200) NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `module_name` (`module_name`),
  UNIQUE KEY `module_route` (`module_route`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `module` (`module_id`, `module_name`, `module_route`, `module_icon`, `module_description`) VALUES
(1, 'Home', 'home/dashboard', '<i class=\"bi bi-house-fill\"></i>', 'This is module home'),
(2, 'User', 'user/index', '<i class=\"bi bi-person-badge-fill\"></i>', 'This is module user');


DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Employee'),
(3, 'Client');


DROP TABLE IF EXISTS `role_module`;
CREATE TABLE IF NOT EXISTS `role_module` (
  `roleModule_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_fk` int(11) NOT NULL,
  `module_fk` int(11) NOT NULL,
  PRIMARY KEY (`roleModule_id`),
  KEY `role_module_role` (`role_fk`),
  KEY `role_module_module` (`module_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `role_module` (`roleModule_id`, `role_fk`, `module_fk`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 3, 1);



DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_user` varchar(30) NOT NULL,
  `user_password` varchar(256) NOT NULL,
  `userStatus_fk` int(11) NOT NULL,
  `role_fk` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_user` (`user_user`),
  KEY `user_role` (`role_fk`),
  KEY `user_status` (`userStatus_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `user` (`user_id`, `user_user`, `user_password`, `userStatus_fk`, `role_fk`) VALUES
(1, 'user@email.com', '$2y$10$zNXemXVFPEbCd7yFTM.rMe3FO2sTze.cW/cOrGTps0dOi1YyFO7nW', 1, 1),
(2, 'user1@email.com', '$2y$10$zNXemXVFPEbCd7yFTM.rMe3FO2sTze.cW/cOrGTps0dOi1YyFO7nW', 3, 2),
(3, 'user2@email.com', '$2y$10$zNXemXVFPEbCd7yFTM.rMe3FO2sTze.cW/cOrGTps0dOi1YyFO7nW', 1, 3);



DROP TABLE IF EXISTS `userstatus`;
CREATE TABLE IF NOT EXISTS `userstatus` (
  `userStatus_id` int(11) NOT NULL AUTO_INCREMENT,
  `userStatus_name` varchar(20) NOT NULL,
  PRIMARY KEY (`userStatus_id`),
  UNIQUE KEY `userStatus_name` (`userStatus_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `userstatus` (`userStatus_id`, `userStatus_name`) VALUES
(1, 'Active'),
(2, 'Inactive'),
(3, 'Blocked');



ALTER TABLE `role_module`
  ADD CONSTRAINT `role_module_module` FOREIGN KEY (`module_fk`) REFERENCES `module` (`module_id`),
  ADD CONSTRAINT `role_module_role` FOREIGN KEY (`role_fk`) REFERENCES `role` (`role_id`);


ALTER TABLE `user`
  ADD CONSTRAINT `user_role` FOREIGN KEY (`role_fk`) REFERENCES `role` (`role_id`),
  ADD CONSTRAINT `user_status` FOREIGN KEY (`userStatus_fk`) REFERENCES `userstatus` (`userStatus_id`);
COMMIT;

