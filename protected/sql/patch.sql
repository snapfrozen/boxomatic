CREATE TABLE IF NOT EXISTS `customer_locations` (
  `customer_location_id` INT NOT NULL AUTO_INCREMENT,
  `customer_id` INT NULL ,
  `location_id` INT NULL ,
  `address` VARCHAR(150) NULL ,
  `address2` VARCHAR(150) NULL ,
  `suburb` VARCHAR(45) NULL ,
  `state` VARCHAR(45) NULL ,
  `postcode` VARCHAR(45) NULL ,
  `phone` VARCHAR(45) NULL ,
  PRIMARY KEY (`customer_location_id`) ,
  INDEX `fk_custlocation_cust` (`customer_id` ASC) ,
  INDEX `fk_custlocation_loc` (`location_id` ASC) ,
  CONSTRAINT `fk_custlocation_cust`
    FOREIGN KEY (`customer_id` )
    REFERENCES `customers` (`customer_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_custlocation_loc`
    FOREIGN KEY (`location_id` )
    REFERENCES `locations` (`location_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

ALTER TABLE `locations` ADD COLUMN `is_pickup` TINYINT NULL DEFAULT 1 AFTER `location_delivery_value`;
ALTER TABLE `customers` DROP FOREIGN KEY `location_id`;
ALTER TABLE `customers` DROP COLUMN `location_id` , DROP INDEX `location_id`;
ALTER TABLE `customer_weeks` ADD COLUMN `customer_location_id` INT NULL  AFTER `location_id`;

ALTER TABLE `customers` ADD COLUMN `customer_location_id` INT NULL  AFTER `customer_notes` , ADD COLUMN `location_id` INT NULL  AFTER `customer_location_id` , 
  ADD CONSTRAINT `fk_customer_custLocation`
  FOREIGN KEY (`customer_location_id` )
  REFERENCES `customer_locations` (`customer_location_id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_customer_location`
  FOREIGN KEY (`location_id` )
  REFERENCES `locations` (`location_id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `fk_customer_custLocation` (`customer_location_id` ASC) 
, ADD INDEX `fk_customer_location` (`location_id` ASC) ;