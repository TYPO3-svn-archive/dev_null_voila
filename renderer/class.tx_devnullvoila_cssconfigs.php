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
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_t3lib.'class.t3lib_div.php');

require_once(PATH_t3lib.'utility/class.t3lib_utility_debug.php');


/**
 * Plugin 'YAML for templavoila' for the 'dev_null_voila' extension.
 *
 * @author	Wolfgang <scotty@dev-null.at>
 * @package	TYPO3
 * @subpackage	tx_devnullvoila
 */
class tx_devnullvoila_cssconfigs {

	var $conf = array();
	
	var $cObj;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */

    function renderConfig($conf)	{
        $this->conf 	= $conf['devnullvoila.'];
		
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->cObj->start(array());

		$yaml_TO 		= 0;
		$yaml_TO_next 	= 0;
		
		// uncomment for debugging
		// t3lib_utility_Debug::printArray($this->conf);
		
		$pageRenderer = $GLOBALS['TSFE']->getPageRenderer();
		
		// t3lib_utility_Debug::debug($pageRenderer, 'renderer');

		if(isset($this->conf["uid"])) 
			$pageUID = $this->conf["uid"]; 
		else 
			$pageUID = $GLOBALS['TSFE']->id;
        
		$devNullXmlRoot = $this->conf['devNullXmlRoot'];
		
		// we start with the current page
		$treeUID = $pageUID;

		// as long we are not at the root
        while($treeUID != 0) {
			// build query to acces page data
			$dbRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery('pid, tx_templavoila_to, tx_templavoila_next_to', 'pages', "uid = $treeUID");
			$rowarray = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbRes);
			$GLOBALS['TYPO3_DB']->sql_free_result($dbRes);
			
            if(! is_array($rowarray)) {
				$this->addHeaderComment("<!-- Error: select page record failed -->");
				return;
			}

			// parent page
			$tmpPID   = $rowarray['pid'];
			// template object this page
			$tmpTO    = $rowarray['tx_templavoila_to'];
			// template object sub pages
			$tmpNext  = $rowarray['tx_templavoila_next_to'];

			// check subpages template design only if we allready walked up
			if($treeUID != $pageUID && empty($yaml_TO)) {
				// convert empty values to 0
				$yaml_TO = empty($tmpNext) ? 0 : $tmpNext;
			}
			
			// check template design to use
			if($yaml_TO == 0) {
				// convert empty values to 0
				$yaml_TO = empty($tmpTO) ? 0 : $tmpTO;
			}
			
			// template design reference found ?
			if($yaml_TO != 0) {
				// get local processing xml
				$dbRes = $GLOBALS['TYPO3_DB']->exec_SELECTquery('localprocessing', 'tx_templavoila_tmplobj', "uid = $yaml_TO");
				if($tvRow = $GLOBALS['TYPO3_DB']->sql_fetch_row($dbRes)) {
					// fetch local processing xml for TO if any
					$localXml = t3lib_div::xml2array($tvRow[0]);
				}
				// free db resource
				$GLOBALS['TYPO3_DB']->sql_free_result($dbRes);

				// uncomment for debugging
				// t3lib_utility_Debug::printArray($rowarray, "datastructure");
				// t3lib_utility_Debug::printArray($localXml, "xml array");
				// t3lib_utility_Debug::printArray($this->conf);

				foreach($this->conf['cssConfigs.'] as $cssKey => $cssConfig) {
					// uncomment for debugging
					// t3lib_utility_Debug::printArray($cssConfig);
					
					if(!is_array($cssConfig)) {
						$cssFile = $cssConfig;
						$cssConfig = $this->conf['cssConfigs.'][$cssKey . '.'];							
					}
					elseif(empty($cssConfig['field'])) {
						continue;
					}
					else {
						// field localprocessing is empty or malformed
						if(! is_array($localXml))
							$this->addHeaderComment("<!-- Error: templavoila local processing xml - no valid xml -->");

						if(empty($devNullXmlRoot))
							$this->addHeaderComment("<!-- Error: no value for devNullXmlRoot - can't access xml -->");

						$xmlField = $cssConfig['field'];

						if(empty($xmlField))
							$this->addHeaderComment("<!-- Error: cssConfigs.$cssKey - field is empty -->");

						$xmlValue = $localXml['ROOT'][$devNullXmlRoot][$xmlField];
						
						if(empty($xmlValue))
							$this->addHeaderComment("<!-- Error: cssConfigs.$cssKey - xml is empty for field $xmlField -->");

						$cssPath = empty($cssConfig['cssPath']) ? $this->conf['cssPath'] : $cssConfig['cssPath'];
						$cssFile = $cssPath . $xmlValue;
					}
					
					// get configuration pidOnly
					$pidOnly = empty($cssConfig['pidOnly']) ? 0 : $cssConfig['pidOnly'];
					
					// only for one given page ?
					if($pidOnly != 0 && $pageUID != $pidOnly)
						continue;
					
					// Get item data
					$cssMedia = empty($cssConfig['media']) ? 'all' : $cssConfig['media'];
					$cssTitle = empty($cssConfig['title']) ? ''    : $cssConfig['title'];
					$cssWrap  = empty($cssConfig['wrap'])  ? ''    : $cssConfig['wrap'];

					// append CSS Link to pageRenderer
					$pageRenderer->addCssFile($cssFile, 'stylesheet', $cssMedia, $cssTitle, true, false, $cssWrap);
				}
				
				// we got it - done
				return;
			}
			
			$treeUID = $tmpPID;
		}
	}
	
	protected function addHeaderComment($comment) {
		$GLOBALS['TSFE']->additionalHeaderData[] = $comment;
	}
}

?>