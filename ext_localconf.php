<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['configArrayPostProc'][$_EXTKEY] = 'EXT:dev_null_voila/class.tx_devnullvoila_fehook.php:&tx_devnullvoila_fehook->hookInitConfig';

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_devnullvoila_pi1.php', '_pi1', '', 1);
?>