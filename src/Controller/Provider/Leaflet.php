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

namespace WEM\LocationsBundle\Controller\Provider;

use Contao\Controller;

/**
 * Provide Leaflet utilities functions to Locations Extension.
 */
class Leaflet extends Controller
{
    /**
     * Default Leaflet Map Config.
     *
     * @return [Array]
     */
    public static function getDefaultConfig()
    {
        return [
            'provider' => 'leaflet', 'zoom' => 13, 'tileLayer_url' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 'tileLayer_attribution' => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors', 'tileLayer_minZoom' => 0, 'tileLayer_maxZoom' => 18, 'tileLayer_id' => '', 'tileLayer_accessToken' => '',
        ];
    }
}
