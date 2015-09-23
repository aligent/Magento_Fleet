<?php

require_once 'abstract.php';

class Shell_Synchronize extends Mage_Shell_Abstract {

    protected $_storage = 1;
    protected $_connection = 'default_setup';

    public function run()
    {
        $this->sync();
    }

    /**
     * Return file storage singleton
     *
     * @return Mage_Core_Model_File_Storage
     */
    protected function _getSyncSingleton()
    {
        return Mage::getSingleton('core/file_storage');
    }

    /**
     * Return synchronize process status flag
     *
     * @return Mage_Core_Model_File_Storage_Flag
     */
    protected function _getSyncFlag()
    {
        return $this->_getSyncSingleton()->getSyncFlag();
    }

    protected function sync() {
        session_write_close();
        $flag = $this->_getSyncFlag();
        $flag->setState(Mage_Core_Model_File_Storage_Flag::STATE_RUNNING)->save();
        Mage::getSingleton('admin/session')->setSyncProcessStopWatch(false);

        $storage = array('type' => (int) $this->_storage);
        $storage['connection'] = $this->_connection;


        try {
            $this->_getSyncSingleton()->synchronize($storage);
            $flag->setState(Mage_Core_Model_File_Storage_Flag::STATE_FINISHED)->save();
            $oConfig = new Mage_Core_Model_Config();
            $oConfig->saveConfig('system/media_storage_configuration/media_storage', "1");
        } catch (Exception $e) {
            echo ($e->getMessage());
            Mage::logException($e);
            $flag->passError($e);
        }
    }


}

$shell = new Shell_Synchronize();
$shell->run();