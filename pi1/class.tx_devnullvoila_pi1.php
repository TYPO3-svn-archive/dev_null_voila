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

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'YAML for templavoila' for the 'dev_null_voila' extension.
 *
 * @author	Wolfgang <scotty@dev-null.at>
 * @package	TYPO3
 * @subpackage	tx_devnullvoila
 */
class tx_devnullvoila_pi1 extends tslib_pibase {
	// Same as class name
	var $prefixId      = 'tx_devnullvoila_pi1';
	// Path to this script relative to the extension dir.
	var $scriptRelPath = 'pi1/class.tx_devnullvoila_pi1.php';
	// The extension key.
	var $extKey        = 'dev_null_voila';
	
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */

    function main($content, $conf)	{
        $this->conf 	= $conf;
        $err_flag 		= 0; 
        $returnCode 	= 0; 
		
		$yaml_TO 		= 0;
		$yaml_TO_next 	= 0;
		
		// uncomment for debugging
		// t3lib_utility_Debug::printArray($this->conf);

		if(isset($this->conf["uid"])) 
			$pageUID = $this->conf["uid"]; 
		else 
			$pageUID = $GLOBALS['TSFE']->id;
        
		$xmlDevNullRoot = $this->conf['xmlDevNullRoot'];
		
		// we start with the current page
		$treeUID = $pageUID;
		
		// as long we are not at the root
        while($treeUID != 0) {
			// build query
			$findit = mysql_query("SELECT pid, tx_templavoila_to, tx_templavoila_next_to FROM pages WHERE uid = $treeUID");
            if($rowarray = mysql_fetch_array($findit, MYSQL_BOTH)) {
				
				// template object this page
				$tmpTO    = $rowarray['tx_templavoila_to'];
				// template object sub pages
				$tmpNext  = $rowarray['tx_templavoila_next_to'];
				// parent page
				$tmpPID   = $rowarray['pid'];
				
				// check subpages template design only if we allready walked up
				if($treeUID != $pageUID && empty($yaml_TO))
					// convert empty values to 0
					$yaml_TO = empty($tmpNext) ? 0 : $tmpNext;

				// check template design to use
				if($yaml_TO == 0)
					// convert empty values to 0
					$yaml_TO = empty($tmpTO) ? 0 : $tmpTO;
				
				// template design reference found ?
				if($yaml_TO != 0)
				{
					// get local processing xml
				    $datastructure = mysql_query("SELECT localprocessing FROM tx_templavoila_tmplobj WHERE uid = $yaml_TO");
					// hopefully we got one row
					if($rowarray = mysql_fetch_array($datastructure, MYSQL_NUM))
					{
						// fetch local processing xml for TO
						if(empty($rowarray[0]))
							// field localprocessing is empty
							return 'Error: tx_yamlvoila_empty_xml';
						else {
							$yamlXml = t3lib_div::xml2array($rowarray[0]);
							if(! is_array($yamlXml)) {
								// malformed xml data
								return "Error: tx_devnull_empty_xml_error";
							}
						}
					}

					// uncomment for debugging
					// t3lib_utility_Debug::printArray($rowarray, "datastructure");
					// t3lib_utility_Debug::printArray($yamlXml, "xml array");
					// t3lib_utility_Debug::printArray($this->conf);

					foreach($this->conf['cssConfigs.'] as $cssKey => $cssConfig) {
						// uncomment for debugging
						// t3lib_utility_Debug::printArray($cssConfig);
						
						if(!is_array($cssConfig))
						{
							$cssFile = $cssConfig;
							$cssConfig = $conf['cssConfigs.'][$cssKey . '.'];
						}
						elseif(empty($cssConfig['field'])) {
							continue;
						}
						else {
							$xmlField = $cssConfig['field'];

							$xmlValue = $yamlXml['ROOT'][$xmlDevNullRoot][$xmlField];
							
							if(empty($xmlValue))
							{
								echo "Error: tx_devnull_empty_xml_field - [$xmlDevNullRoot] - field [$xmlField]";
							}
							$cssPath = empty($cssConfig['cssPath']) ? $this->conf['cssPath'] : $cssConfig['cssPath'];
							$cssFile = $cssPath . $xmlValue;
						}
						
						// get configuration pidOnly
						$pidOnly = empty($cssConfig['pidOnly']) ? 0 : $cssConfig['pidOnly'];
						
						// only for one given page ?
						if($pidOnly != 0 && $pageUID != $pidOnly)
							continue;
						
						// Get item data
						$cssMedia = empty($cssConfig['media']) ? $this->conf['media'] : $cssConfig['media'];
						$cssTitle = empty($cssConfig['title']) ? $this->conf['title'] : $cssConfig['title'];

						// Wrap attributes
						$cssTmp = $this->cObj->stdWrap($cssFile,  $this->conf['linkWraps.']['href_sdtWrap.'])
						        . $this->cObj->stdWrap($cssMedia, $this->conf['linkWraps.']['media_stdWrap.'])
								. $this->cObj->stdWrap($cssTitle, $this->conf['linkWraps.']['title_stdWrap.']);
						
						// Get wrap strings
						$cssWrapItem = empty($cssConfig['wrap']) ? '|' : $cssConfig['wrap'];
						
						// wrap everything
						$cssLink = $this->cObj->stdWrap($cssTmp, $this->conf['linkWraps.']['css_stdWrap.']);
						$cssItem = $this->cObj->wrap($cssLink, $cssWrapItem);
						
						// append CSS Link tag to header
						$GLOBALS['TSFE']->additionalHeaderData["dev_null.voila.css-$cssKey"] = $cssItem;
						
					}
					
					return;
				}
				
				$treeUID = $tmpPID;
			}
			else 
				$treeUID = 0;
		}
    }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dev_null_voila/pi1/class.tx_devnullvoila_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dev_null_voila/pi1/class.tx_devnullvoila_pi1.php']);
}

?>