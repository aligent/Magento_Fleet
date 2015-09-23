<?php
/**
 * Apply Anchor's recommended index to the core_file_storage table.
 * Ref: http://docs.anchorfleet.com/fleet-magento-1/troubleshooting/cms-images/
 *
 * @author Jim O'Halloran <jim@aligent.com.au>
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

if (!in_array('INDEX IDX_CORE_FILE_STORAGE_DIRECTORY', $installer->getConnection()->getIndexList($this->getTable('core_file_storage')))) {

    $installer->run("CREATE INDEX IDX_CORE_FILE_STORAGE_DIRECTORY ON {$this->getTable('core_file_storage')} (directory ASC);");

}

$installer->endSetup();
