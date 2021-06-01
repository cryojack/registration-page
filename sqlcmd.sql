/*
USE `demo-page`;

CREATE TABLE IF NOT EXISTS `messages` (
  `msg_id` VARCHAR(12) PRIMARY KEY NOT NULL,
  `msg_title` TEXT NOT NULL,
  `msg_content` TEXT NOT NULL,
  `sender_id` VARCHAR(12) NOT NULL,
  `recipient_id` VARCHAR(12) NOT NULL,
  `date_sent` DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` ENUM('Y','N') NOT NULL,
   FOREIGN KEY (`sender_id`) REFERENCES `users`(`user_id`),
   FOREIGN KEY (`recipient_id`) REFERENCES `users`(`user_id`)
);


CREATE TABLE IF NOT EXISTS `yahu` (
  `img_id` VARCHAR(12) PRIMARY KEY NOT NULL,
  `img_title` TEXT NOT NULL,
  `img_desc` TEXT NOT NULL,
  `img_path` VARCHAR(255) NOT NULL,
  `date_uploaded` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` ENUM('Y','N') NOT NULL
);
*/

use `VehicleExpenseAppMainDB`;

create table if not exists `VEHICLE_LIST` (
  `VEHICLE_ID` varchar(16) primary key not null,
  `VEHICLE_NAME` varchar(50) not null,
  `VEHICLE_TYPE` enum('BIKE','CAR') not null,
  `VEHICLE_MODEL_NAME` varchar(100) not null,
  `VEHICLE_DATE_PURCHASED` date not null,
  `VEHICLE_PRICE` int(10) unsigned not null,
  `VEHICLE_OWNER` text not null,
  `VEHICLE_DATE_ADDED` datetime not null default current_timestamp
);

create table if not exists `VEHICLE_SERVICES_TBL` (
  `SERVICE_JOB_NO` varchar(20) primary key not null,
  `VEHICLE_ID` varchar(16) not null,
  `SERVICE_JOB_PRICE` int(10) unsigned not null,
  `SERVICE_JOB_DATE` date not null,
  `SERVICE_JOB_DATE_ADDED` datetime not null default current_timestamp,
  foreign key (`VEHICLE_ID`) references `VEHICLE_LIST`(`VEHICLE_ID`)
);

create table if not exists `VEHICLE_SERVICE_LIST_TBL` (
  `SERVICE_ID` varchar(16) primary key not null,
  `SERVICE_JOB_NO` varchar(20) not null,
  `SERVICE_DETAIL` text not null,
  `SERVICE_PRICE` int(10) unsigned not null,
  `SERVICE_DATE_ADDED` datetime not null default current_timestamp,
  foreign key (`SERVICE_JOB_NO`) references `VEHICLE_SERVICES_TBL`(`SERVICE_JOB_NO`)
);