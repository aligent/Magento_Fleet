<?php
/**
 * Apply Anchor's recommended index to the core_file_storage table.
 *
 * @author William Tran <william@aligent.com.au>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

if (!array_key_exists($installer->getIdxName($this->getTable('core_file_storage'), 'upload_time'), $installer->getConnection()->getIndexList($this->getTable('core_file_storage')))) {

    $installer->run("CREATE INDEX {$installer->getIdxName($this->getTable('core_file_storage'), 'upload_time')} ON {$this->getTable('core_file_storage')} (upload_time ASC);");

}

$installer->endSetup();
