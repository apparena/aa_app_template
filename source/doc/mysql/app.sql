CREATE TABLE `app_settings` (
    `id`    INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `key`   VARCHAR(255)     NOT NULL,
    `value` VARCHAR(255)     NOT NULL,
    `aa_inst_id` INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`)
)
    COLLATE ='utf8_general_ci'
    ENGINE =InnoDB;


CREATE TABLE `modules` (
    `id`         INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(255)        NOT NULL,
    `is_enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
)
    COLLATE ='utf8_general_ci'
    ENGINE =InnoDB;
