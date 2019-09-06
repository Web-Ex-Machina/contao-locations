<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018-2019 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Module;

use Contao\Combiner;

use Haste\Http\Response\HtmlResponse;
use Haste\Http\Response\JsonResponse;
use Haste\Input\Input;

use WEM\Location\Controller\ClassLoader;
use WEM\Location\Controller\Util;
use WEM\Location\Model\Map;
use WEM\Location\Model\Location;

/**
 * Front end module "locations map".
 */
class DisplayMap extends Core
{
    /**
     * Map Template
     *
     * @var string
     */
    protected $strTemplate = 'mod_wem_locations_map';

    /**
     * List Template
     *
     * @var string
     */
    protected $strListTemplate = 'mod_wem_locations_list';

    /**
     * Filters
     *
     * @var Array [Available filters]
     */
    protected $filters;

    /**
     * Display a wildcard in the back end
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['wem_display_map'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    /**
     * Generate the module
     *
     * @return void
     */
    protected function compile()
    {
        try {
            // Load the map
            $this->objMap = Map::findByPk($this->wem_location_map);

            if (!$this->objMap) {
                throw new \Exception("No map found.");
            }

            // Load the libraries
            ClassLoader::loadLibraries($this->objMap, 8);
            \System::getCountries();

            // Build the config
            $arrConfig = [];
            if ($this->objMap->mapConfig) {
                foreach (deserialize($this->objMap->mapConfig) as $arrRow) {
                    if ($arrRow["value"] === 'true') {
                        $varValue = true;
                    } elseif ($arrRow["value"] === 'false') {
                        $varValue = false;
                    } else {
                        $varValue = html_entity_decode($arrRow["value"]);
                    }

                    if (strpos($arrRow["key"], "_") !== false) {
                        $arrOption = explode("_", $arrRow["key"]);
                        $arrConfig[$arrOption[0]][$arrOption[1]] = $varValue;
                    } else {
                        $arrConfig["map"][$arrRow["key"]] = $varValue;
                    }
                }
            }

            // Get the jumpTo page
            $this->objJumpTo = \PageModel::findByPk($this->objMap->jumpTo);

            // Get locations
            $arrLocations = $this->getLocations();

            // Get categories
            $arrCategories = $this->getCategories();

            // Send the data to Map template
            $this->Template->locations = $arrLocations;
            $this->Template->categories = $arrCategories;
            $this->Template->config = $arrConfig;

            // Gather filters
            if ("nofilters" != $this->wem_location_map_filters) {
                \System::loadLanguageFile('tl_wem_location');
                $arrFilterFields = unserialize($this->wem_location_map_filters_fields);
                $this->filters = [];

                foreach ($arrFilterFields as $f) {
                    if ("search" == $f) {
                        $this->filters[$f] = [
                            "label" => "Recherche :",
                            "placeholder" => "Que recherchez-vous ?",
                            "name" => "search",
                            "type" => "text",
                            "value" => ""
                        ];
                    } else {
                        $this->filters[$f] = [
                            "label" => sprintf('%s :', $GLOBALS['TL_LANG']['tl_wem_location'][$f][0]),
                            "placeholder" => $GLOBALS['TL_LANG']['tl_wem_location'][$f][1],
                            "name" => $f,
                            "type" => "select",
                            "options" => []
                        ];

                        foreach ($arrLocations as $l) {
                            if (!$l[$f]) {
                                continue;
                            }

                            if (!in_array($l[$f], $this->filters[$f]['options'])) {
                                $this->filters[$f]['options'][] = $l[$f];
                            }
                        }
                    }
                }

                $this->Template->filters = $this->filters;
                $this->Template->filters_position = $this->wem_location_map_filters;
            }

            // Send the fileMap
            if ("jvector" == $this->objMap->mapProvider
                && "" != $this->objMap->mapFile
            ) {
                $this->Template->mapFile = $this->objMap->mapFile;
            }

            // If the config says so, we will generate a template with a list of the locations
            if ("nolist" != $this->wem_location_map_list) {
                $objTemplate = new \FrontendTemplate($this->strListTemplate);
                $objTemplate->locations = $arrLocations;
                $objTemplate->list_position = $this->wem_location_map_list;

                if ($this->filters) {
                    $objTemplate->filters = $this->filters;
                    $objTemplate->filters_position = $this->wem_location_map_filters;
                }

                $this->Template->list = $objTemplate->parse();
                $this->Template->list_position = $this->wem_location_map_list;
            }
        } catch (\Exception $e) {
            $this->Template->error = true;
            $this->Template->msg = $e->getMessage();
            $this->Template->trace = $e->getTraceAsString();
        }
    }
}
