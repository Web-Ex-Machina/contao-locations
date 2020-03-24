<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Controller\Provider;

use Contao\Controller;

/**
 * Provide JVector utilities functions to Locations Extension
 */
class JVector extends Controller
{
	/**
	 * Default JVector Map Config
	 * @return [Array]
	 */
	public static function getDefaultConfig(){
		return [
			"provider" => "jvector"
			,"zoomOnScroll" => "false"
			,"panOnDrag" => "false"
			,"regionsSelectable" => "true"
			,"regionsSelectableOne" => "true"
			,"markersSelectable" => "true"
			,"markersSelectableOne" => "true"
			,"mapBackground" => "ffffff"
			,"regionBackground" => "dddddd"
			,"regionBackgroundActive" => "999999"
			,"regionBackgroundHover" => "999999"
			,"regionBackgroundSelected" => "666666"
			,"regionBackgroundSelectedHover" => "666666"
			,"regionLock" => "true"
			,"markerBackground" => "666666"
			,"markerBackgroundHover" => "999999"
			,"markerBackgroundSelected" => "999999"
		];
	}
}