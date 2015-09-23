<?php
/**
 * @author William Tran (william@aligent.com.au)
 * Create media table in accordance to http://docs.anchorfleet.com/fleet-magento-1/configuring-magento-for-fleet/media-in-database/
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

if ($installer->getConnection()->isTableExists($this->getTable('core_file_storage')) != true){

    $installer->run("
        CREATE TABLE `{$this->getTable('core_file_storage')}` (
          `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'File Id',
          `content` longblob NOT NULL COMMENT 'File Content',
          `upload_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Upload Timestamp',
          `filename` varbinary(255) DEFAULT NULL,
          `directory_id` int(10) unsigned DEFAULT NULL COMMENT 'Identifier of Directory where File is Located',
          `directory` varbinary(255) DEFAULT NULL,
          PRIMARY KEY (`file_id`),
          UNIQUE KEY `UNQ_CORE_FILE_STORAGE_FILENAME_DIRECTORY_ID` (`filename`,`directory_id`),
          KEY `IDX_CORE_FILE_STORAGE_DIRECTORY_ID` (`directory_id`),
          CONSTRAINT `FK_CORE_FILE_STORAGE_DIR_ID_CORE_DIR_STORAGE_DIR_ID` FOREIGN KEY (`directory_id`) REFERENCES `{$this->getTable('core_directory_storage')}` (`directory_id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) DEFAULT CHARSET=utf8 COMMENT='File Storage';
  ");

    $installer->run("
        CREATE TABLE `{$this->getTable('core_directory_storage')}` (
          `directory_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Directory Id',
          `name` varbinary(255) DEFAULT NULL,
          `path` varbinary(255) DEFAULT NULL,
          `upload_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Upload Timestamp',
          `parent_id` int(10) unsigned DEFAULT NULL COMMENT 'Parent Directory Id',
          PRIMARY KEY (`directory_id`),
          UNIQUE KEY `UNQ_CORE_DIRECTORY_STORAGE_NAME_PARENT_ID` (`name`,`parent_id`),
          KEY `IDX_CORE_DIRECTORY_STORAGE_PARENT_ID` (`parent_id`),
          CONSTRAINT `FK_CORE_DIR_STORAGE_PARENT_ID_CORE_DIR_STORAGE_DIR_ID` FOREIGN KEY (`parent_id`) REFERENCES `{$this->getTable('core_directory_storage')}` (`directory_id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) DEFAULT CHARSET=utf8 COMMENT='Directory Storage';
  ");
}

$installer->endSetup();
