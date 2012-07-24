SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`locations`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`locations` (
  `location_id` INT NOT NULL ,
  `location_name` VARCHAR(45) NULL ,
  `location_delivery_value` DECIMAL(7,2) NULL ,
  PRIMARY KEY (`location_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`customers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`customers` (
  `customer_id` INT NOT NULL AUTO_INCREMENT ,
  `customer_name` VARCHAR(45) NULL ,
  `customer_phone` VARCHAR(45) NULL ,
  `customer_mobile` VARCHAR(45) NULL ,
  `customer_address` VARCHAR(45) NULL ,
  `customer_address2` VARCHAR(45) NULL ,
  `location_id` INT NULL ,
  `customer_notes` VARCHAR(45) NULL ,
  `customer_email` VARCHAR(45) NULL ,
  `customer_password` VARCHAR(45) NULL ,
  PRIMARY KEY (`customer_id`) ,
  INDEX `location_id` (`location_id` ASC) ,
  CONSTRAINT `location_id`
    FOREIGN KEY (`location_id` )
    REFERENCES `mydb`.`locations` (`location_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`growers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`growers` (
  `grower_id` INT NOT NULL AUTO_INCREMENT ,
  `grower_name` VARCHAR(45) NULL ,
  `grower_mobile` VARCHAR(45) NULL ,
  `grower_phone` VARCHAR(150) NULL ,
  `grower_address` VARCHAR(150) NULL ,
  `grower_address2` VARCHAR(45) NULL ,
  `grower_suburb` VARCHAR(45) NULL ,
  `grower_postcode` VARCHAR(45) NULL ,
  `grower_distance_kms` VARCHAR(45) NULL ,
  `grower_bank_account_name` VARCHAR(45) NULL ,
  `grower_bank_bsb` VARCHAR(45) NULL ,
  `grower_bank_acc` VARCHAR(45) NULL ,
  `grower_email` VARCHAR(100) NULL ,
  PRIMARY KEY (`grower_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`grower_items`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`grower_items` (
  `item_id` INT NOT NULL AUTO_INCREMENT ,
  `grower_id` INT NULL ,
  `item_name` VARCHAR(45) NULL ,
  `item_value` DECIMAL(7,2) NULL ,
  `item_unit` ENUM('KG','EA') NULL ,
  `item_available_from` DATE NULL ,
  `item_available_to` DATE NULL ,
  PRIMARY KEY (`item_id`) ,
  INDEX `grower_id` (`grower_id` ASC) ,
  CONSTRAINT `grower_id`
    FOREIGN KEY (`grower_id` )
    REFERENCES `mydb`.`growers` (`grower_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`box_sizes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`box_sizes` (
  `box_sizes` INT NOT NULL AUTO_INCREMENT ,
  `box_size_name` VARCHAR(45) NULL ,
  `box_size_value` VARCHAR(45) NULL ,
  `box_size_markup` VARCHAR(45) NULL ,
  `box_size_price` VARCHAR(45) NULL ,
  PRIMARY KEY (`box_sizes`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`weeks`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`weeks` (
  `week_id` INT NOT NULL AUTO_INCREMENT ,
  `week_num` VARCHAR(45) NULL ,
  `week_notes` VARCHAR(45) NULL ,
  PRIMARY KEY (`week_id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`boxes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`boxes` (
  `box_id` INT NOT NULL AUTO_INCREMENT ,
  `size_id` INT NULL ,
  `box_price` DECIMAL(7,2) NULL ,
  `week_id` INT NULL ,
  PRIMARY KEY (`box_id`) ,
  INDEX `week_id` (`week_id` ASC) ,
  INDEX `size_id` (`size_id` ASC) ,
  CONSTRAINT `week_id`
    FOREIGN KEY (`week_id` )
    REFERENCES `mydb`.`weeks` (`week_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `size_id`
    FOREIGN KEY (`size_id` )
    REFERENCES `mydb`.`box_sizes` (`box_sizes` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`box_items`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`box_items` (
  `box_item_id` INT NOT NULL ,
  `item_name` VARCHAR(45) NULL ,
  `box_id` INT NULL ,
  `item_value` VARCHAR(45) NULL ,
  `grower_id` INT NULL ,
  PRIMARY KEY (`box_item_id`) ,
  INDEX `box_id` (`box_id` ASC) ,
  INDEX `grower_id` (`grower_id` ASC) ,
  CONSTRAINT `box_id`
    FOREIGN KEY (`box_id` )
    REFERENCES `mydb`.`boxes` (`box_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `grower_id`
    FOREIGN KEY (`grower_id` )
    REFERENCES `mydb`.`growers` (`grower_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`customer_boxes`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`customer_boxes` (
  `customer_box_id` INT NOT NULL AUTO_INCREMENT ,
  `customer_id` INT NULL ,
  `box_id` INT NULL ,
  `quantity` INT NULL ,
  PRIMARY KEY (`customer_box_id`) ,
  INDEX `customer_id` (`customer_id` ASC) ,
  INDEX `box_id` (`box_id` ASC) ,
  CONSTRAINT `customer_id`
    FOREIGN KEY (`customer_id` )
    REFERENCES `mydb`.`customers` (`customer_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `box_id`
    FOREIGN KEY (`box_id` )
    REFERENCES `mydb`.`boxes` (`box_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`customer_payments`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `mydb`.`customer_payments` (
  `payment_id` INT NOT NULL AUTO_INCREMENT ,
  `payment_value` DECIMAL(7,2) NULL ,
  `payment_type` VARCHAR(45) NULL ,
  `payment_date` DATETIME NULL ,
  `customer_id` INT NULL ,
  PRIMARY KEY (`payment_id`) ,
  INDEX `customer_id` (`customer_id` ASC) ,
  CONSTRAINT `customer_id`
    FOREIGN KEY (`customer_id` )
    REFERENCES `mydb`.`customers` (`customer_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

