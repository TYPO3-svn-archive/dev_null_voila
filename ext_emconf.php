<?php

########################################################################
# Extension Manager/Repository config file for ext "dev_null_voila".
#
# Auto generated 28-03-2011 21:09
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Template for YAML and Templavoila',
	'description' => 'Framework for use of CSS-Framework YAML and templavoila.

Work is derived from extension db_ttv',
	'category' => 'templates',
	'author' => 'Wolfgang',
	'author_email' => 'scotty@dev-null.at',
	'shy' => '',
	'dependencies' => 'cms,css_styled_content,templavoila',
	'conflicts' => 'db_tap,db_ttv,db_yamltv',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.2.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-4.9.9',
			'cms' => '1.1.0-',
			'css_styled_content' => '1.0.0-',
			'templavoila' => '1.5.0-',
		),
		'conflicts' => array(
			'db_tap' => '',
			'db_ttv' => '',
			'db_yamltv' => '',
		),
		'suggests' => array(
			'ics_awstats' => '',
			'tt_news' => '',
		),
	),
	'_md5_values_when_last_written' => 'a:11:{s:9:"ChangeLog";s:4:"0885";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"d725";s:17:"ext_localconf.php";s:4:"f3c5";s:14:"ext_tables.php";s:4:"9d86";s:14:"doc/manual.sxw";s:4:"712b";s:33:"pi1/class.tx_devnullvoila_pi1.php";s:4:"a657";s:25:"static/menu/constants.txt";s:4:"dec1";s:21:"static/menu/setup.txt";s:4:"8462";s:26:"static/voila/constants.txt";s:4:"091c";s:22:"static/voila/setup.txt";s:4:"f673";}',
	'suggests' => array(
	),
);

?>