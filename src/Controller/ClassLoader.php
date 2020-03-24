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

namespace WEM\LocationsBundle\Controller;

use Contao\Combiner;
use Contao\Controller;

/**
 * Provide utilities function to Locations Extension.
 */
class ClassLoader extends Controller
{
    /**
     * Correctly load a generic Provider
     * Not used for now, but keep it for later !
     *
     * @param [String] $strProvider [Provider classname]
     *
     * @return [Object] [Provider class]
     */
    public static function loadProviderClass($strProvider)
    {
        try {
            // Parse the classname
            $strClass = sprintf("WEM\LocationsBundle\Controller\Provider\%s", ucfirst($strProvider));

            // Throw error if class doesn't exists
            if (!class_exists($strClass)) {
                throw new Exception(sprintf('Unknown class %s', $strClass));
            }

            // Create the object
            return new $strClass();

            // And return
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Load the Map Provider Libraries.
     *
     * @param [Object]  $objMap     [Map model]
     * @param [Integer] $strVersion [File Versions]
     */
    public static function loadLibraries($objMap, $strVersion = 1): void
    {
        // Generate the combiners
        $objCssCombiner = new Combiner();
        $objJsCombiner = new Combiner();

        // Load generic files
        $objCssCombiner->add('bundles/wemlocations/css/default.css', $strVersion);
        $objJsCombiner->add('bundles/wemlocations/js/default.js', $strVersion);

        // Depending on the provider, we will need more stuff
        switch ($objMap->mapProvider) {
            case 'jvector':
                $objCssCombiner->addMultiple([
                    'bundles/wemlocations/vendor/jquery-jvectormap/jquery-jvectormap-2.0.3.css', 'bundles/wemlocations/css/jvector.css',
                ], $strVersion);
                $objJsCombiner->addMultiple([
                    'bundles/wemlocations/vendor/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js', 'bundles/wemlocations/vendor/jquery-jvectormap/maps/jquery-jvectormap-'.$objMap->mapFile.'-mill.js', 'bundles/wemlocations/js/jvector.js',
                ], $strVersion);
                break;
            case 'gmaps':
                if (!$objMap->mapProviderGmapKey) {
                    throw new \Exception('Google Maps needs an API Key !');
                }

                $objCssCombiner->add('bundles/wemlocations/css/gmaps.css', $strVersion);
                $objJsCombiner->add('bundles/wemlocations/js/gmaps.js', $strVersion);
                $GLOBALS['TL_JQUERY'][] = sprintf('<script src="https://maps.googleapis.com/maps/api/js?key=%s"></script>', $objMap->mapProviderGmapKey);
                break;
            case 'leaflet':
                $objCssCombiner->addMultiple([
                    'bundles/wemlocations/vendor/leaflet/leaflet.css', 'bundles/wemlocations/css/leaflet.css',
                ], $strVersion);
                $objJsCombiner->addMultiple([
                    'bundles/wemlocations/vendor/leaflet/leaflet.js', 'bundles/wemlocations/js/leaflet.js',
                ], $strVersion);
                break;
            default:
                throw new \Exception('This provider is unknown');
        }

        // And add them to pages
        $GLOBALS['TL_HEAD'][] = sprintf('<link rel="stylesheet" href="%s">', $objCssCombiner->getCombinedFile());
        $GLOBALS['TL_JQUERY'][] = sprintf('<script src="%s"></script>', $objJsCombiner->getCombinedFile());
    }
}
