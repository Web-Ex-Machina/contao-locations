<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Elements;

use Contao\Combiner;

use Haste\Http\Response\HtmlResponse;
use Haste\Http\Response\JsonResponse;
use Haste\Input\Input;

use WEM\Location\Controller\Util;
use WEM\Location\Model\Map;
use WEM\Location\Model\Location;

/**
 * Front end module "locations map".
 */
class DisplayMap extends \ContentElement
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_wem_locations_map';

	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		try
		{
			$objLocations = Location::findItems(["published"=>1, "pid"=>$this->wem_location_map]);

			if(!$objLocations)
				throw new \Exception("No locations found for this map");

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

			//dump($arrLocations);

			$this->Template->locations = $arrLocations;
		}
		catch(\Exception $e)
		{
			$this->Template->blnError = true;
			$this->Template->strError = "Une erreur est survenue : ".$e->getMessage();
		}
	}
}