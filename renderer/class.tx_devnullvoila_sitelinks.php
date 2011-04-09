<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Wolfgang Rotschek <scotty@dev-null.at>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');

require_once(PATH_tslib.'class.tslib_content.php');

require_once(PATH_t3lib.'class.t3lib_div.php');

require_once(PATH_t3lib.'utility/class.t3lib_utility_debug.php');


/**
 * Plugin 'YAML for templavoila' for the 'dev_null_voila' extension.
 *
 * @author	Wolfgang <scotty@dev-null.at>
 * @package	TYPO3
 * @subpackage	tx_devnullvoila
 */
class tx_devnullvoila_sitelinks extends tslib_pibase{

	var $conf = array();
	
	var $cObj = NULL;

	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */

    function renderConfig($conf) {

		// Same as class name
		$this->prefixId      = 'tx_devnullsearch_pi1';
		
		// Path to this script relative to the extension dir.
		$this->scriptRelPath = 'renderer/class.tx_devnullvoila_sitelinks.php';
		
		// The extension key.
		$this->extKey        = 'dev_null_voila';

        $this->conf 	= $conf['devnullvoila.'];

		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->cObj->start(array());

		$this->pi_loadLL();

		// uncomment for debugging
		// t3lib_utility_Debug::printArray($this->conf);

		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();

		if($this->conf['linkAuthor']) {
			$link = $this->getPageLink($this->conf['linkAuthor']);
			$lang = $this->pi_getLL('dev-null-voila.linkAuthor');
			$pageRenderer->addHeaderData('<link rel="author" title="' . $lang . '" href="' . $link . '" />');
		}

		if($this->conf['linkContents']) {
			$link = $this->getPageLink($this->conf['linkContents']);
			$lang = $this->pi_getLL('dev-null-voila.linkContents');
			$pageRenderer->addHeaderData('<link rel="contents" title="Inhaltsverzeichnis" href="' . $link . '" />');
		}

		if($this->conf['linkIndex']) {
			$link = $this->getPageLink($this->conf['linkIndex']);
			$lang = $this->pi_getLL('dev-null-voila.linkIndex');
			$pageRenderer->addHeaderData('<link rel="index" title="Stichwortverzeichnis" href="' . $link . '" />');
		}

		if($this->conf['linkSearch']) {
			$link = $this->getPageLink($this->conf['linkSearch']);
			$lang = $this->pi_getLL('dev-null-voila.linkSearch');
			$pageRenderer->addHeaderData('<link rel="search" title="Suche" href="' . $link . '" />');
		}

		if($this->conf['linkHelp']) {
			$link = $this->getPageLink($this->conf['linkHelp']);
			$lang = $this->pi_getLL('dev-null-voila.linkHelp');
			$pageRenderer->addHeaderData('<link rel="help" title="Hilfe" href="' . $link . '">');
		}

		if($this->conf['linkCopyright']) {
			$link = $this->getPageLink($this->conf['linkCopyright']);
			$lang = $this->pi_getLL('dev-null-voila.linkCopyright');
			$pageRenderer->addHeaderData('<link rel="copyright" title="Copyright" href="' . $link . '" />');
		}
	}
	
	/**
	 * Creates a link to a single page
	 *
	 * @param	array	$pageId	Page ID
	 * @return	string	Full URL of the page including host name (escaped)
	 */

	 protected function getPageLink($pageId) {
		$conf = array(
			'parameter' => $pageId,
			'returnLast' => 'url',
		);
		$link = htmlspecialchars($this->cObj->typoLink('', $conf));
		return t3lib_div::locationHeaderUrl($link);
	}
}

?>