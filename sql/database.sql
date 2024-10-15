-- SIM card table
CREATE TABLE `sim` (
  `iccid` bigint(20) UNSIGNED NOT NULL,
  `subscriber_id` int(11) UNSIGNED DEFAULT NULL,
  `balance` decimal(20,6) NOT NULL DEFAULT 0.000000,
  PRIMARY KEY (`iccid`)
);

-- Subscribers table
CREATE TABLE `subscribers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `min_balance` decimal(20,6) NOT NULL DEFAULT 0.000000,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
);

-- Balance write-off table
CREATE TABLE `sim_balance_away` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `amount` decimal(20,6) UNSIGNED NOT NULL,
  `iccid` bigint(20) UNSIGNED NOT NULL,
  `comment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Balance replenishment table
CREATE TABLE `sim_balance_come` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `amount` decimal(20,6) UNSIGNED NOT NULL,
  `iccid` bigint(20) UNSIGNED NOT NULL,
  `comment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Decrease SIM card balance
DELIMITER //
CREATE TRIGGER `after_insert_sim_balance_away`
AFTER INSERT ON `sim_balance_away` FOR EACH ROW
BEGIN
  UPDATE `sim` SET `balance` = `balance` - NEW.amount
  WHERE `iccid` = NEW.iccid;
END//
DELIMITER ;

-- Increase SIM card balance
DELIMITER //
CREATE TRIGGER `after_insert_sim_balance_come`
AFTER INSERT ON `sim_balance_come` FOR EACH ROW
BEGIN
  UPDATE `sim` SET `balance` = `balance` + NEW.amount
  WHERE `iccid` = NEW.iccid;
END//
DELIMITER ;
