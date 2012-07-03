-- Copyright 2012 Aris Karageorgos (http://deepspacehosting.com)

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(64) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(128) NOT NULL ,
  `admin` TINYINT(1) NOT NULL DEFAULT 0 ,
  `nickname` VARCHAR(32) NOT NULL ,
  `salt` VARCHAR(128) NOT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT 0 ,
  `reset_key` VARCHAR(128) NOT NULL ,
  `signup` DATETIME NULL ,
  `signup_ip` VARCHAR(128) NULL ,
  `signup_user_agent` TEXT NULL ,
  `last_login` DATETIME NULL ,
  `last_login_ip` VARCHAR(128) NULL ,
  `last_login_user_agent` TEXT NULL ,
  `locale` VARCHAR(10) NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `permissions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `permissions` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `permissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

SHOW WARNINGS;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'Admin'),(2,'User'),(3,'Public');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `albums`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `albums` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `albums` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `permission_id` INT UNSIGNED NOT NULL ,
  `parent_id` INT UNSIGNED NULL DEFAULT NULL ,
  `photo_id` INT UNSIGNED NOT NULL ,
  `name` VARCHAR(64) NOT NULL ,
  `slug` VARCHAR(255) NOT NULL ,
  `lft` INT NULL DEFAULT NULL ,
  `rght` INT NULL DEFAULT NULL ,
  `description` TEXT NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `albums_slug_UNIQUE` (`slug` ASC) ,
  INDEX `fk_albums_albums1` (`parent_id` ASC) ,
  INDEX `fk_albums_permissions1` (`permission_id` ASC) ,
  INDEX `index_albums_created` (`created` ASC) ,
  CONSTRAINT `fk_albums_albums1`
    FOREIGN KEY (`parent_id` )
    REFERENCES `albums` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_albums_permissions1`
    FOREIGN KEY (`permission_id` )
    REFERENCES `permissions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `photos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `photos` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `photos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `album_id` INT UNSIGNED NOT NULL ,
  `user_id` INT UNSIGNED NOT NULL ,
  `file_path` VARCHAR(255) NOT NULL ,
  `file_name` VARCHAR(255) NOT NULL ,
  `name` VARCHAR(64) NOT NULL ,
  `type` VARCHAR(16) NOT NULL ,
  `hash` VARCHAR(128) NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `published` TINYINT(1) NOT NULL DEFAULT 1 ,
  `description` text,
  `views` INT NULL DEFAULT 0 ,
  `size` INT NULL ,
  `width` INT NULL ,
  `lwidth` INT NULL ,
  `height` INT NULL ,
  `lheight` INT NULL ,
  `taken` DATETIME NULL ,
  `location` VARCHAR(255) NULL DEFAULT NULL ,
  `slug` VARCHAR(255) NULL ,
  `modified` DATETIME NULL ,
  `created` DATETIME NULL ,
  `last_viewed` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_photos_albums1` (`album_id` ASC) ,
  INDEX `fk_photos_users1` (`user_id` ASC) ,
  INDEX `index_photos_created` (`created` ASC) ,
  INDEX `index_photos_published` (`published` ASC) ,
  INDEX `index_photos_hash` (`hash` ASC) ,
  CONSTRAINT `fk_items_albums1`
    FOREIGN KEY (`album_id` )
    REFERENCES `albums` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_items_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `metadata`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `metadata` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `metadata` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `photo_id` INT UNSIGNED NOT NULL ,
  `data` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_photos_metadata_items1` (`photo_id` ASC) ,
  CONSTRAINT `fk_item_metadata_items1`
    FOREIGN KEY (`photo_id` )
    REFERENCES `photos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `settings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `settings` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(64) NOT NULL ,
  `value` VARCHAR(128) NOT NULL ,
  `section` VARCHAR(64) NOT NULL ,
  `type` VARCHAR(45) NOT NULL DEFAULT 'text' ,
  `description` TEXT NULL ,
  `modified` DATETIME NULL ,
  `extra` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `k_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;

SHOW WARNINGS;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'Name','X2','General','text','Name of your gallery','2012-07-03 07:31:29',NULL),(2,'Theme','Cerulean','General','select','Select a theme for your gallery','2012-07-03 07:31:29','a:1:{s:5:\"empty\";s:7:\"Default\";}'),(3,'Show_Children','1','Album','checkbox','Should children be shown along with photos?','2012-07-03 07:31:29',NULL),(4,'Allow_Downloads','1','Photo','checkbox','Should I allow downloads of the original photo','2012-07-03 07:31:29',NULL),(5,'Registration','1','User','checkbox','Should I allow user registration','2012-07-03 07:31:29',NULL),(6,'XSX','64','Size','int','Extra Small width','2012-07-03 07:31:29',NULL),(7,'XSY','48','Size','int','Extra Small height','2012-07-03 07:31:29',NULL),(8,'SX','160','Size','int','Small width','2012-07-03 07:31:29',NULL),(9,'SY','120','Size','int','Small height','2012-07-03 07:31:29',NULL),(10,'LX','1024','Size','int','Large width','2012-07-03 07:31:29',NULL),(11,'LY','768','Size','int','Large height','2012-07-03 07:31:29',NULL),(12,'XS','xs','Dir','text','Extra Small Directory','2012-07-03 07:31:29',NULL),(13,'S','s','Dir','text','Small Directory','2012-07-03 07:31:29',NULL),(14,'L','l','Dir','text','Large Directory','2012-07-03 07:31:29',NULL),(15,'O','o','Dir','text','Original Directory','2012-07-03 07:31:29',NULL),(16,'P','p','Dir','text','Photo Directory','2012-07-03 07:31:29',NULL),(17,'Require_Approval','1','User','checkbox','Require Admin Approval ','2012-07-03 07:31:29',NULL),(18,'Allow_Full','1','Photo','checkbox','Allow Showing of full size photos','2012-07-03 07:31:29',NULL),(19,'Language','en','General','select','Default Language','2012-07-03 07:31:29',NULL),(20,'First','1','User','checkbox','First User of the System? Do not change this unless you understand the consequences','2012-07-03 07:31:29',NULL),(21,'Details','1','Pagination','checkbox','Show pagination details on the front page','2012-07-03 07:31:29',NULL),(22,'Footer_Text','Copyright &copy; 2012 <a href=\"http://arisx2.com\">X2</a>','General','textarea','Footer Copyright Text','2012-07-03 07:31:29',NULL),(23,'Base_URL','','Photo','text','Base URL. If you don\'t know what this means just leave the default value.','2012-07-03 07:31:29',NULL),(24,'Photo_Details','0','Photo','checkbox','Allow users to see photo details?','2012-07-03 07:31:29',NULL),(25,'Show_Photo_Date','0','Photo','checkbox','Show dates below photos','2012-07-03 07:31:29',NULL),(26,'Show_Album_Date','0','Album','checkbox','Show dates below albums','2012-07-03 07:31:29',NULL),(27,'Show_Album_Sort','0','Album','checkbox','Show sorting options for albums','2012-07-03 07:31:29',NULL),(28,'Show_Photo_Sort','0','Photo','checkbox','Show sorting options for photos','2012-07-03 07:31:29',NULL),(29,'Render_Colorbox','0','Photo','checkbox','Render photos with colorbox by default','2012-07-02 10:23:26',NULL),(30,'Loupe','1','Photo','checkbox','Show Loupe for photos','2012-07-02 10:23:26',NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

SHOW WARNINGS;
-- -----------------------------------------------------
-- Table `pages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `pages` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `pages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(128) NOT NULL ,
  `html` TEXT NOT NULL ,
  `published` TINYINT(1) NOT NULL DEFAULT 1 ,
  `promoted` TINYINT(1) NOT NULL DEFAULT 0 ,
  `slug` VARCHAR(128) NULL ,
  `created` DATETIME NULL ,
  `modified` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC) ,
  UNIQUE INDEX `title_UNIQUE` (`title` ASC) )
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `i18n`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `i18n` ;

SHOW WARNINGS;
CREATE  TABLE IF NOT EXISTS `i18n` (
  `id` INT(10) NOT NULL AUTO_INCREMENT ,
  `locale` VARCHAR(6) NOT NULL ,
  `model` VARCHAR(255) NOT NULL ,
  `foreign_key` INT(10) NOT NULL ,
  `field` VARCHAR(255) NOT NULL ,
  `content` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `locale` (`locale` ASC) ,
  INDEX `model` (`model` ASC) ,
  INDEX `row_id` (`foreign_key` ASC) ,
  INDEX `field` (`field` ASC) )
ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;