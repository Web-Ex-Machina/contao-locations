<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018-2019 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

/**
 * Backend modules
 */
array_insert(
    $GLOBALS['BE_MOD'],
    array_search('content', array_keys($GLOBALS['BE_MOD'])) + 1,
    array(
        'wem-locations' => array(
            'wem-maps' => array(
                'tables'      => array('tl_wem_map', 'tl_wem_map_category', 'tl_wem_location', 'tl_content'),
                'import'      => array('WEM\LocationsBundle\Backend\Callback', "importLocations"),
                'export'      => array('WEM\LocationsBundle\Backend\Callback', "exportLocations"),
                'geocode'     => array('WEM\LocationsBundle\Backend\Callback', "geocode"),
                'icon'        => 'system/modules/wem-contao-locations/assets/icon_map_16_c3.png',
            )
        )
    )
);

/**
 * Load icon in Contao 4.2 backend
 */
if ('BE' === TL_MODE) {
    if (version_compare(VERSION, '4.4', '<')) {
        $GLOBALS['TL_CSS'][] = 'system/modules/wem-contao-locations/assets/backend/backend.css';
    } else {
        $GLOBALS['TL_CSS'][] = 'system/modules/wem-contao-locations/assets/backend/backend_svg.css';
    }
}

/**
 * Frontend modules
 */
array_insert(
    $GLOBALS['FE_MOD'],
    2,
    array(
        'wem_locations' => array(
            'wem_display_map'       => 'WEM\LocationsBundle\Module\DisplayMap',
            'wem_location_reader'   => 'WEM\LocationsBundle\Module\LocationsReader',
        )
    )
);

/**
 * Models
 */
$GLOBALS['TL_MODELS'][\WEM\LocationsBundle\Model\Map::getTable()] = 'WEM\LocationsBundle\Model\Map';
$GLOBALS['TL_MODELS'][\WEM\LocationsBundle\Model\Location::getTable()] = 'WEM\LocationsBundle\Model\Location';
$GLOBALS['TL_MODELS'][\WEM\LocationsBundle\Model\Category::getTable()] = 'WEM\LocationsBundle\Model\Category';

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('WEM\LocationsBundle\Controller\Util', 'replaceInsertTags');