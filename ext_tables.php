<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
t3lib_extMgm::addStaticFile($_EXTKEY,'static/voila/', 'dev/null voila');

t3lib_extMgm::addStaticFile($_EXTKEY,'static/menu/', 'dev/null menu');
?>