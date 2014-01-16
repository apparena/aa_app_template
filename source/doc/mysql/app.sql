CREATE TABLE `app_settings` (
    `id`    INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `key`   VARCHAR(255)     NOT NULL,
    `value` VARCHAR(255)     NOT NULL,
    `i_id` INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`)
)
    COLLATE ='utf8_general_ci'
    ENGINE =InnoDB;