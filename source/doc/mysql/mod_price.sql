CREATE TABLE `mod_price` (
    `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `aa_inst_id` INT(11) UNSIGNED NOT NULL,
    `auth_uid`   INT(11) UNSIGNED NOT NULL,
    `item_id`    INT(11) UNSIGNED NOT NULL,
    `data`       TEXT             NULL,
    `date_added` INT(11) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`)
)
    COLLATE ='utf8_general_ci'
    ENGINE =InnoDB;
