CREATE TABLE `mod_friendsselected` (
    `id`           INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `aa_inst_id`   INT(11) UNSIGNED    NOT NULL,
    `invited_user` BIGINT(20) NOT NULL,
    `invited_by`   BIGINT(20) NOT NULL,
    `has_accepted` TINYINT(1) UNSIGNED NULL DEFAULT '0',
    `date_added`   TIMESTAMP  NULL DEFAULT NULL,
    `last_update`  TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
    COLLATE ='utf8_general_ci'
    ENGINE =InnoDB;
