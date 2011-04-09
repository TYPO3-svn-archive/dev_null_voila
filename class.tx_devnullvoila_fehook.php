<?php

/***************************************************************
*  Copyright notice
*  
*  (c) 2011 Wolfgang Rotschek <scotty@dev-null.at>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is 
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

require_once(PATH_t3lib.'class.t3lib_div.php');

require_once(t3lib_extMgm::extPath('dev_null_voila', 'renderer/class.tx_devnullvoila_cssconfigs.php'));
require_once(t3lib_extMgm::extPath('dev_null_voila', 'renderer/class.tx_devnullvoila_sitelinks.php'));

class tx_devnullvoila_fehook extends tslib_pibase {    

	var $renderer;
	
	public function hookInitConfig(array &$parameters, tslib_fe &$parentObject) {
		
		$TSconf = &$parameters['config'];

		// some debugging stuff
		$GLOBALS['TSFE']->additionalHeaderData[] = '<!-- dev_null.voila.configArrayPostProc begin -->';

		// instantiate our renderer and call it
		$this->renderer = t3lib_div::makeInstance('tx_devnullvoila_cssconfigs');
		$this->renderer->renderConfig($TSconf);
		
		// instantiate our renderer and call it
		$this->renderer = t3lib_div::makeInstance('tx_devnullvoila_sitelinks');
		$this->renderer->renderConfig($TSconf);

		// some debugging stuff
		$GLOBALS['TSFE']->additionalHeaderData[] = '<!-- dev_null.voila.configArrayPostProc end -->';

    }	
}


if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/dev_null_voila/class.tx_devnullvoila_fehook.php"]){
        include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/dev_null_voila/class.tx_devnullvoila_fehook.php"]);
}

?>
