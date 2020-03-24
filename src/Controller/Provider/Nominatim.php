<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\LocationsBundle\Controller\Provider;

use Contao\Controller;

/**
 * Provide Nominatim utilities functions to Locations Extension
 */
class Nominatim extends Controller
{
	/**
	 * Nominating Geocoding URL to request (sprintf pattern)
	 * @var String
	 */
	protected static $strGeocodingUrl = "https://nominatim.openstreetmap.org/search%s&format=json&addressdetails=1&email=%s";

	/**
	 * Return the coords lat/lng for a given address
	 * @param  [Mixed]   $varAddress [Address to geocode, can be a String, or a Location Model]
	 * @param  [Object]  $objMap     [Map Model]
	 * @param  [Integer] $intResults [Number of API results wanted]
	 * @return [Array]               [Address Components]
	 */
	public static function geocoder($varAddress, $objMap, $intResults = 1)
	{
		// Before everything, check if we can geocode this
		if("nominatim" != $objMap->geocodingProvider)
			throw new \Exception($GLOBALS['TL_LANG']['WEM']['LOCATIONS']['ERROR']['missingConfigForGeocoding']);

		// Standardize the address to geocode
		$strAddress = '';
		if(is_object($varAddress)){
			if($varAddress->street)
				$strAddress .= "?street=".trim(preg_replace('/\s+/', ' ', strip_tags($varAddress->street)));
			if($varAddress->postal)
				$strAddress.= "&postalcode=".$varAddress->postal;
			if($varAddress->city)
				$strAddress.= "&city=".$varAddress->city;
			if($varAddress->region)
				$strAddress.= "&state=".$varAddress->region;
			if($varAddress->country)
				$strAddress.= "&countrycodes=".$varAddress->country;
		}
		else
			$strAddress = $varAddress;

		// Some String manips
		$strAddress = str_replace(' ', '+', $strAddress);

		// Then, cURL it baby.
		$ch = curl_init();
		$strUrl = sprintf(static::$strGeocodingUrl, $strAddress, $GLOBALS['TL_ADMIN_EMAIL']);
		curl_setopt($ch, CURLOPT_URL, $strUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$geoloc = json_decode(curl_exec($ch), true);

		// Catch Error
		if(!$geoloc)
			throw new \Exception("invalid request : ".$strUrl);

		// And return them
		if(1 === $intResults)
			return ["lat"=>$geoloc[0]['lat'], "lng"=>$geoloc[0]['lon']];
		else{
			foreach($geoloc as $result)
				$arrResults[] = ["lat"=>$result['lat'], "lng"=>$result['lng']];
			return $arrResults;
		}
	}
}