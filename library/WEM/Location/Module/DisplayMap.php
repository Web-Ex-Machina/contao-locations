<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Module;

use Contao\Combiner;

use Haste\Http\Response\HtmlResponse;
use Haste\Http\Response\JsonResponse;
use Haste\Input\Input;

use WEM\Location\Controller\ClassLoader;
use WEM\Location\Controller\Util;
use WEM\Location\Model\Map;
use WEM\Location\Model\Location;

/**
 * Front end module "locations map".
 */
class DisplayMap extends \Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_wem_locations_map';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['wem_display_map'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile()
	{
		// Load the map
		$objMap = Map::findByPk($this->wem_location_map);

		if(!$objMap)
			throw new \Exception("No map found.");

		// Load the locations
		$objLocations = Location::findItems(["published"=>1, "pid"=>$this->wem_location_map]);

		if(!$objLocations)
			throw new \Exception("No locations found for this map.");

		// Load the libraries
		ClassLoader::loadLibraries($objMap);
		\System::getCountries();

		// Build the config
		$arrConfig = [];
		if($objMap->mapConfig){
			foreach(deserialize($objMap->mapConfig) as $arrRow){
				if($arrRow["value"] === 'true')
					$varValue = true;
				else if($arrRow["value"] === 'false')
					$varValue = false;
				else
					$varValue = $arrRow["value"];

				$arrConfig[$arrRow["key"]] = $varValue;
			}
		}

		$arrLocations = array();
		while($objLocations->next())
		{
			$strCountry = strtoupper($objLocations->country);
			$strContinent = Util::getCountryContinent($strCountry);

			$arrLocation = [
				"name" => $objLocations->title
				,"address" => $objLocations->street." ".$objLocations->postal." ".$objLocations->city
				,"phone" => $objLocations->phone
				,"email" => $objLocations->email
				,"url" => $objLocations->website
				,"lat" => $objLocations->lat
				,"lng" => $objLocations->lng
				,"country" => [
					"code" => $strCountry
					,"name" => $GLOBALS['TL_LANG']['CNT'][$objLocations->country]
				]
				,"continent" => [
					"code" => $strContinent
					,"name" => $GLOBALS['TL_LANG']['CONTINENT'][$strContinent]
				]
			];

			$arrLocations[] = $arrLocation;
		}
		
		$this->Template->locations = $arrLocations;
		$this->Template->config = $arrConfig;
	}
}