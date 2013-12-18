CREATE TABLE `mod_facebook_friends` (
    `id`               INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `request_id`       BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
    `fb_uid`           BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
    `auth_uid`         BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
    `aa_inst_id`       INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `request_accepted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `door_id`          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    `date_added`       TIMESTAMP           NULL DEFAULT NULL,
    `last_update`      TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
)
    COLLATE ='latin1_swedish_ci'
    ENGINE =InnoDB;