<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Controller;

use Contao\Combiner;
use Contao\Controller;
use Contao\Encryption;

/**
 * Provide utilities function to Locations Extension
 */
class ClassLoader extends Controller
{
	/**
	 * Load the Map Provider Libraries
	 * @param  [Object]  $objMap      [Map model]
	 * @param  [Integer] $strVersion  [File Versions]
	 */
	public static function loadLibraries($objMap, $strVersion = 1){
		switch($objMap->mapProvider){
			case 'jvector':
				$objCombiner = new Combiner();
				$objCombiner->addMultiple([
					"system/modules/wem-contao-locations/assets/vendor/jquery-jvectormap/jquery-jvectormap-2.0.3.css"
					,"system/modules/wem-contao-locations/assets/css/jvector.css"
				], $strVersion);
				$GLOBALS["TL_HEAD"][] = sprintf('<link rel="stylesheet" href="%s">', $objCombiner->getCombinedFile());

				$objCombiner = new Combiner();
				$objCombiner->addMultiple([
					"system/modules/wem-contao-locations/assets/vendor/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js"
					,"system/modules/wem-contao-locations/assets/vendor/jquery-jvectormap/maps/jquery-jvectormap-world-mill.js"
					,"system/modules/wem-contao-locations/assets/js/jvector.js"
				], $strVersion);
				$GLOBALS["TL_JQUERY"][] = sprintf('<script src="%s"></script>', $objCombiner->getCombinedFile());
			break;
			case 'gmaps':
				if(!$objMap->mapProviderGmapKey)
					throw new \Exception("Google Maps needs an API Key !");

				$objCombiner = new Combiner();
				$objCombiner->add("system/modules/wem-contao-locations/assets/css/gmaps.css", $strVersion);
				$GLOBALS["TL_HEAD"][] = sprintf('<link rel="stylesheet" href="%s">', $objCombiner->getCombinedFile());

				$objCombiner = new Combiner();
				$objCombiner->add("system/modules/wem-contao-locations/assets/js/gmaps.js", $strVersion);
				$GLOBALS["TL_JQUERY"][] = sprintf('<script src="https://maps.googleapis.com/maps/api/js?key=%s"></script>', $objMap->mapProviderGmapKey);
				$GLOBALS['TL_JQUERY'][] = sprintf('<script src="%s"></script>', $objCombiner->getCombinedFile());
			break;
			case 'leaflet':
				$objCombiner = new Combiner();
				$objCombiner->addMultiple([
					"system/modules/wem-contao-locations/assets/vendor/leaflet/leaflet.css"
					,"system/modules/wem-contao-locations/assets/css/leaflet.css"
				], $strVersion);
				$GLOBALS["TL_HEAD"][] = sprintf('<link rel="stylesheet" href="%s">', $objCombiner->getCombinedFile());
				$GLOBALS["TL_JQUERY"][] = sprintf('<script src="%s"></script>', "system/modules/wem-contao-locations/assets/vendor/leaflet/leaflet.js");
				$GLOBALS["TL_JQUERY"][] = sprintf('<script src="%s"></script>', "system/modules/wem-contao-locations/assets/js/leaflet.js");
			break;
			default:
				throw new \Exception("This provider is unknown");
		}
	}
}
