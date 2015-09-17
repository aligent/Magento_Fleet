<?php
$installer = new Mage_Core_Model_Resource_Setup('core_setup');

$installer->startSetup();

//first clean out any configuration values in lower scopes
$installer->deleteConfigData('web/url/redirect_to_base');
$installer->deleteConfigData('web/cookie/cookie_domain');
$installer->deleteConfigData('system/varnishcache/servers');

//set the Fleet-specific config values in Global scope
$installer->setConfigData('web/url/redirect_to_base', 0);
$installer->setConfigData('web/cookie/cookie_domain', '');
$installer->setConfigData('system/varnishcache/servers', 'varnish-0;varnish-1');

//get the default URL and set it down at website scope level
$oDefaultWebsite = Mage::app()->getDefaultStoreView()->getWebsite();
$vDefaultUrl = Mage::app()->getDefaultStoreView()->getBaseUrl();
$installer->setConfigData('web/secure/base_url', $vDefaultUrl,'website',$oDefaultWebsite->getId());

// So that we can set the base URL for the global scope to be the admin since this will be routed to a separate node
$aDefaultUrlParts = parse_url($vDefaultUrl);
$vDefaultHostname = $aDefaultUrlParts['host'];

$vAdminHostname = preg_replace('/vagrant\./', 'admin.', $vDefaultHostname);
$vAdminUrl = $aDefaultUrlParts['scheme'].'://'.$vAdminHostname.$aDefaultUrlParts['path'];

$installer->setConfigData('web/secure/base_url', $vAdminUrl);
$installer->setConfigData('admin/url/use_custom', $vAdminUrl);

$installer->endSetup();