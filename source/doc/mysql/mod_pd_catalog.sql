CREATE TABLE `mod_pd_catalog` (
    `id`              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `sku`             INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `image`           LONGBLOB         NULL,
    `image_type_code` VARCHAR(2)       NOT NULL,
    `additional`      TEXT             NULL,
    `last_update`     DATETIME         NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `sku` (`sku`)
)
    COLLATE ='latin1_swedish_ci'
    ENGINE =InnoDB;