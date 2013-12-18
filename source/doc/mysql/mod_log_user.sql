CREATE TABLE `mod_log_adminpanel` (
    `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `hash`       VARCHAR(32)      NOT NULL,
    `aa_inst_id` INT(11) UNSIGNED NOT NULL,
    `scope`      VARCHAR(100)     NOT NULL,
    `value`      VARCHAR(255)     NOT NULL,
    `counter`    INT(11) UNSIGNED NOT NULL DEFAULT '0',
    `date_added` TIMESTAMP        NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `hash` (`hash`)
)
    COLLATE ='latin1_swedish_ci'
    ENGINE =InnoDB;

CREATE TABLE `mod_log_user` (
    `id`            INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `aa_inst_id`    INT(11) UNSIGNED    NOT NULL,
    `auth_uid`      BIGINT(20) UNSIGNED NULL DEFAULT '0',
    `auth_uid_temp` VARCHAR(32)         NOT NULL DEFAULT '0',
    `scope`         VARCHAR(255)        NOT NULL,
    `data`          TEXT                NOT NULL,
    `code`          INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    `agend_id`      INT(11) UNSIGNED    NULL DEFAULT NULL,
    `ip`            INT(11) UNSIGNED    NULL DEFAULT NULL,
    `date_added`    TIMESTAMP           NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
)
    COLLATE ='utf8_general_ci'
    ENGINE =InnoDB;

CREATE TABLE `mod_log_user_agents` (
    `id`      INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `hash_id` VARCHAR(32)      NOT NULL,
    `data`    TEXT             NOT NULL,
    PRIMARY KEY (`id`)
)
    COLLATE ='utf8_general_ci'
    ENGINE =InnoDB;
