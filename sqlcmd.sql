


/*
CREATE DATABASE IF NOT EXISTS `demo-page`;

USE `demo-page`;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` VARCHAR(12) PRIMARY KEY NOT NULL,
  `login_name` VARCHAR(100) NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `email_id` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `date_created` DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `users` CHANGE `user_id` `user_id` VARCHAR(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `login_name` `login_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `first_name` `first_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `last_name` `last_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `email_id` `email_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `date_created` `date_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
*/
