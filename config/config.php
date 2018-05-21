<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */


/**
 * Backend modules
 */
array_insert($GLOBALS['BE_MOD'], 1, array
(
	'wem-locations' => array
	(
		'wem-maps' => array
		(
			'tables'      => array('tl_wem_map', 'tl_wem_location'),
			'import'	  => array('WEM\Location\Backend\Callback', "importLocations"),
			'export'	  => array('WEM\Location\Backend\Callback', "exportLocations"),
			'geocode'	  => array('WEM\Location\Backend\Callback', "geocode"),
			'icon'        => 'system/modules/wem-contao-locations/assets/icon_map_16_c3.png',
		)
	)
));

/**
 * Load icon in Contao 4.2 backend
 */
if ('BE' === TL_MODE){
    if (version_compare(VERSION, '4.4', '<'))
        $GLOBALS['TL_CSS'][] = 'system/modules/wem-contao-locations/assets/backend.css';
    else
        $GLOBALS['TL_CSS'][] = 'system/modules/wem-contao-locations/assets/backend_svg.css';
}

/**
 * Frontend modules
 */
array_insert($GLOBALS['FE_MOD'], 2, array
(
	'wem_locations' => array
	(
		'wem_display_map' 		=> 'WEM\Location\Module\DisplayMap',
	)
));

/**
 * Content Elements
 */
array_insert($GLOBALS['TL_CTE']['includes'], 0, array
(
	'wem_locations' => 'WEM\Location\Elements\DisplayMap',
));

/**
 * Models
 */
$GLOBALS['TL_MODELS'][\WEM\Location\Model\Map::getTable()] = 'WEM\Location\Model\Map';
$GLOBALS['TL_MODELS'][\WEM\Location\Model\Location::getTable()] = 'WEM\Location\Model\Location';