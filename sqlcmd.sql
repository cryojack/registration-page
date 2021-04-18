
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
*/

CREATE TABLE IF NOT EXISTS `yahu` (
  `img_id` VARCHAR(12) PRIMARY KEY NOT NULL,
  `img_title` TEXT NOT NULL,
  `img_desc` TEXT NOT NULL,
  `img_path` VARCHAR(255) NOT NULL,
  `date_uploaded` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_deleted` ENUM('Y','N') NOT NULL
);
