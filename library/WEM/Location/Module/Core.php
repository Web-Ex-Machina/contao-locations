<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018-2019 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Module;

use WEM\Location\Controller\Util;
use WEM\Location\Model\Location;

/**
 * Parent class for locations modules.
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */
abstract class Core extends \Module
{
	protected function getLocations(){
		try{
			$objLocations = Location::findItems(["published"=>1, "pid"=>$this->wem_location_map]);

			if(!$objLocations)
				throw new \Exception("No locations found for this map.");

			$arrLocations = array();
			while($objLocations->next())
				$arrLocations[] = $this->getLocation($objLocations->row());

			return $arrLocations;
		}
		catch(\Exception $e){
			throw $e;
		}
	}

	protected function getLocation($varItem, $blnAbsolute = false){
		try{
			if(is_object($varItem))
				$arrItem = $varItem->row();
			else if(is_array($varItem))
				$arrItem = $varItem;
			else if($objItem = Location::findByIdOrAlias($varItem))
				$arrItem = $objItem->row();
			else
				throw new \Exception("No location found for : ".$varItem);

			// Get country and continent
			$strCountry = strtoupper($arrItem['country']);
			$strContinent = Util::getCountryContinent($strCountry);

			$arrItem["address"] = $arrItem["street"]." ".$arrItem["postal"]." ".$arrItem["city"];
			$arrItem["country"] = ["code" => $strCountry, "name" => $GLOBALS['TL_LANG']['CNT'][$arrItem['country']]];
			$arrItem["continent"] = ["code" => $strContinent, "name" => $GLOBALS['TL_LANG']['CONTINENT'][$strContinent]];

			// Build the item URL
			if($this->objJumpTo instanceof \PageModel){
				$params = (\Config::get('useAutoItem') ? '/' : '/items/') . ($arrItem['alias'] ?: $arrItem['id']);
				$arrItem["url"] = ampersand($blnAbsolute ? $this->objJumpTo->getAbsoluteUrl($params) : $this->objJumpTo->getFrontendUrl($params));
			}

			return $arrItem;
		}
		catch(\Exception $e){
			throw $e;
		}
	}
}