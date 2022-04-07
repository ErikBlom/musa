<?php
// https://sqldbm.com/Project/Dashboard/All/

require_once 'environment.php';

// http://swing/musa/setup.php?userdbcreate

/*
ALTER TABLE `musaUsers` ADD CONSTRAINT `fk_status_code` FOREIGN KEY (`status_code`) REFERENCES `musaUserStatus`(`status_code`) ON DELETE CASCADE ON UPDATE CASCADE;
*/
if(isset($_REQUEST['userdbcreate'])){
    $sql="
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `musaTokens`;
DROP TABLE IF EXISTS `musaUsers`;
DROP TABLE IF EXISTS `musaRoleTypes`;
DROP TABLE IF EXISTS `musaUserStatus`;

CREATE TABLE IF NOT EXISTS `musaRoleTypes`
(
    `role_code`   varchar(45) NOT NULL ,
    `role_name`   varchar(45) NOT NULL ,
    `permissions` text NOT NULL ,
PRIMARY KEY (`role_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;
INSERT musaRoleTypes (role_code, role_name, permissions) VALUES ('ROOT','Root','root');
INSERT musaRoleTypes (role_code, role_name, permissions) VALUES ('SUPER','Superuser','super');
INSERT musaRoleTypes (role_code, role_name, permissions) VALUES ('ADMIN','Administratör','admin');
INSERT musaRoleTypes (role_code, role_name, permissions) VALUES ('USER','Användare','user');


CREATE TABLE IF NOT EXISTS `musaUserStatus`
(
 `status_code` varchar(45) NOT NULL ,
 `status_name` varchar(45) NOT NULL ,
PRIMARY KEY (`status_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;
INSERT musaUserStatus (status_code, status_name) VALUES ('INVITED','Inbjuden');
INSERT musaUserStatus (status_code, status_name) VALUES ('NORMAL','Normal');
INSERT musaUserStatus (status_code, status_name) VALUES ('DISABLED','Avstängd');
INSERT musaUserStatus (status_code, status_name) VALUES ('DELETED','Raderad');

CREATE TABLE IF NOT EXISTS `musaUsers` (
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    `email` varchar(45) NOT NULL,
    `email_verified` tinyint DEFAULT NULL,
    `name` varchar(45) NOT NULL,
    `password` varchar(100) DEFAULT NULL,
    `status_code` varchar(45) DEFAULT NULL,
    `role_code` varchar(45) DEFAULT NULL,
    `last_login` datetime DEFAULT NULL,
    `created` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`user_id`),
    KEY `status_idx` (`status_code`),
    KEY `role_code_idx` (`role_code`),
    CONSTRAINT `role_code` FOREIGN KEY (`role_code`) REFERENCES `musaRoleTypes` (`role_code`) ON DELETE NO ACTION ON UPDATE CASCADE,
    CONSTRAINT `status_code` FOREIGN KEY (`status_code`) REFERENCES `musaUserStatus` (`status_code`) ON DELETE NO ACTION ON UPDATE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;
INSERT musaUsers (name, email, password, role_code) VALUES ('Thomas','thomas@tclarsson.se',".'\'$2y$10$EwlLs6xsjQLwQIFTlTOak.oknzEB/1Ja0VvYgoExDVTcskOHHm1mu\''.",'ROOT');
INSERT musaUsers (name, email, password, role_code) VALUES ('Erik','erblom@gmail.com',".'\'$2y$10$EwlLs6xsjQLwQIFTlTOak.oknzEB/1Ja0VvYgoExDVTcskOHHm1mu\''.",'ROOT');
INSERT musaUsers (name, email, password, role_code) VALUES ('Testson','test@tclarsson.se',".'\'$2y$10$EwlLs6xsjQLwQIFTlTOak.oknzEB/1Ja0VvYgoExDVTcskOHHm1mu\''.",'USER');

CREATE TABLE IF NOT EXISTS `musaTokens` (
    `token_id` int(11) NOT NULL AUTO_INCREMENT,
    `token` varchar(100) NOT NULL,
    `user_id` int(11) NOT NULL,
    `expiry_date` datetime NOT NULL,
    `created` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`token_id`),
    KEY `FK_21` (`user_id`),
    CONSTRAINT `FK_19` FOREIGN KEY (`user_id`) REFERENCES `musaUsers` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;
  
SET FOREIGN_KEY_CHECKS = 1;          
";
pa($sql);
$a=$db->executeQry($sql);
    pa($a);
    pa($db->getPdo()->errorInfo());
}

// https://www.tclarsson.se/musa/setup.php?showtables
if(isset($_REQUEST['showtables'])){
    $sql="show tables;
";
    pa($sql);
    $a=$db->getRecFrmQry($sql);
    pa($a);
}

// http://swing/musa/setup.php?setpassword=musa&email=thomas@tclarsson.se

if(isset($_REQUEST['setpassword'])&&isset($_REQUEST['email'])){
    $rec['user_id']=$user->email2user($_REQUEST['email']);
    $rec['email']=$_REQUEST['email'];
    $rec['password'] = password_hash($_REQUEST['setpassword'], PASSWORD_DEFAULT); //encrypt password
    $sql="INSERT INTO musaUsers SET user_id=$rec[user_id],email='$rec[email]',password='$rec[password]' 
    ON DUPLICATE KEY UPDATE password='$rec[password]',email='$rec[email]'";
    pa($sql);
    $r=$db->executeQry($sql);
    pa($r);
}

?>