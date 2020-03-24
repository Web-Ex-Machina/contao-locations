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
 * Provide Leaflet utilities functions to Locations Extension
 */
class Leaflet extends Controller
{
    /**
     * Default Leaflet Map Config
     * @return [Array]
     */
    public static function getDefaultConfig()
    {
        return [
            "provider" => 'leaflet'
            ,"zoom" => 13
            ,"tileLayer_url" => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
            ,"tileLayer_attribution" => 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors'
            ,"tileLayer_minZoom" => 0
            ,"tileLayer_maxZoom" => 18
            ,"tileLayer_id" => ''
            ,"tileLayer_accessToken" => ''
        ];
    }
}
