-- -----------------------------------------------------
-- Copyright 2017 BookStore
--
-- Licensed under the Apache License, Version 2.0 (the "License");
-- you may not use this file except in compliance with the License.
-- You may obtain a copy of the License at
--
--  http://www.apache.org/licenses/LICENSE-2.0
--
-- Unless required by applicable law or agreed to in writing, software
-- distributed under the License is distributed on an "AS IS" BASIS,
-- WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
-- See the License for the specific language governing permissions and
-- limitations under the License.
-- -----------------------------------------------------

-- MySQL Workbench Forward Engineering
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `cities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `city_id` INT NOT NULL AUTO_INCREMENT,
  `city` NVARCHAR(15) NOT NULL,
  PRIMARY KEY (`city_id`),
  UNIQUE INDEX `city_id_UNIQUE` (`city_id` ASC),
  UNIQUE INDEX `city_name_UNIQUE` (`city` ASC))
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` NVARCHAR(50) NOT NULL COMMENT 'Must be a valid email address',
  `username` NVARCHAR(50) NOT NULL COMMENT 'its the email, only used by fuel',
  `password` VARCHAR(255) NOT NULL COMMENT 'PBKDF2 hash using salt',
  `group` int(11) NOT NULL DEFAULT '1' COMMENT 'Fuel groups, not used',
  `last_login` VARCHAR(25) NOT NULL,
  `login_hash` VARCHAR(255) NOT NULL COMMENT 'Login hash',
  `profile_fields` text NOT NULL COMMENT 'All user fields (real name, city and address)',
  `created_at` int(11) NOT NULL DEFAULT '0',
  `updated_at` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `user_email_UNIQUE` (`email` ASC))
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` INT NOT NULL AUTO_INCREMENT,
  `category` NVARCHAR(25) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE INDEX `category_id_UNIQUE` (`category_id` ASC),
  UNIQUE INDEX `category_name_UNIQUE` (`category` ASC))
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `books`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `book_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` NVARCHAR(50) NOT NULL,
  `author` NVARCHAR(50) NOT NULL,
  `is_new` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1 is new, 0 is second hand',
  `category_id` INT NOT NULL,
  `price` INT NOT NULL DEFAULT 0 COMMENT 'colombian pesos',
  `preview` NVARCHAR(255) NOT NULL COMMENT 'Link to a pdf with the book preview',
  `units` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`book_id`, `user_id`),
  UNIQUE INDEX `book_id_UNIQUE` (`book_id` ASC),
  INDEX `fk_books_users_idx` (`user_id` ASC),
  INDEX `fk_books_category_idx` (`category_id` ASC),
  CONSTRAINT `fk_books_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_books_categories`
    FOREIGN KEY (`category_id`)
    REFERENCES `categories` (`category_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
DEFAULT CHARACTER SET utf8
COLLATE utf8_general_ci
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
