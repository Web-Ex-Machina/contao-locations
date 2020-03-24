<?php

declare(strict_types=1);

/**
 * Contao Locations for Contao Open Source CMS
 * Copyright (c) 2015-2020 Web ex Machina
 *
 * @category ContaoBundle
 * @package  Web-Ex-Machina/contao-locations
 * @author   Web ex Machina <contact@webexmachina.fr>
 * @link     https://github.com/Web-Ex-Machina/contao-locations/
 */

/**
 * Backend modules.
 */
array_insert(
    $GLOBALS['BE_MOD'],
    array_search('content', array_keys($GLOBALS['BE_MOD']), true) + 1,
    [
        'wem-locations' => [
            'wem-maps' => [
                'tables' => ['tl_wem_map', 'tl_wem_map_category', 'tl_wem_location', 'tl_content'],
                'import' => ['WEM\LocationsBundle\Backend\Callback', 'importLocations'],
                'export' => ['WEM\LocationsBundle\Backend\Callback', 'exportLocations'],
                'geocode' => ['WEM\LocationsBundle\Backend\Callback', 'geocode'],
                'icon' => 'system/modules/wem-contao-locations/assets/icon_map_16_c3.png',
            ],
        ],
    ]
);

/*
 * Load icon in Contao 4.2 backend
 */
if ('BE' === TL_MODE) {
    if (version_compare(VERSION, '4.4', '<')) {
        $GLOBALS['TL_CSS'][] = 'system/modules/wem-contao-locations/assets/backend/backend.css';
    } else {
        $GLOBALS['TL_CSS'][] = 'system/modules/wem-contao-locations/assets/backend/backend_svg.css';
    }
}

/*
 * Frontend modules
 */
array_insert(
    $GLOBALS['FE_MOD'],
    2,
    [
        'wem_locations' => [
            'wem_display_map' => 'WEM\LocationsBundle\Module\DisplayMap',
            'wem_location_reader' => 'WEM\LocationsBundle\Module\LocationsReader',
        ],
    ]
);

/*
 * Models
 */
$GLOBALS['TL_MODELS'][\WEM\LocationsBundle\Model\Map::getTable()] = 'WEM\LocationsBundle\Model\Map';
$GLOBALS['TL_MODELS'][\WEM\LocationsBundle\Model\Location::getTable()] = 'WEM\LocationsBundle\Model\Location';
$GLOBALS['TL_MODELS'][\WEM\LocationsBundle\Model\Category::getTable()] = 'WEM\LocationsBundle\Model\Category';

/*
 * Hooks
 */
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = ['WEM\LocationsBundle\Controller\Util', 'replaceInsertTags'];
