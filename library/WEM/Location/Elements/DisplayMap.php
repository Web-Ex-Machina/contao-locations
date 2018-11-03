<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Elements;

use Contao\ModuleModel;

use WEM\Location\Model\Map;
use WEM\Location\Module\DisplayMap as ModuleDisplayMap;

/**
 * Content Element "locations map"
 * This content is basically an alias to the module "wem_display_map"
 */
class DisplayMap extends \ContentElement
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_wem_locations_map';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate(){
		if (TL_MODE == 'BE'){
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objMap = Map::findByPk($this->wem_location_map);
			\System::loadLanguageFile('tl_wem_map');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['wem_display_map'][0]) . ' ###';
			$objTemplate->wildcard .= '<br />'.$objMap->title.' | '.$GLOBALS['TL_LANG']['tl_wem_map']['mapProvider'][$objMap->mapProvider].' (ID: '.$objMap->id.')';

			return $objTemplate->parse();
		}

		return parent::generate();
	}

	/**
	 * Generate the content element
	 */
	protected function compile()
	{
		$objModel = new ModuleModel();
		$objModel->tstamp = time();
		$objModel->type = "wem_display_map";
		$objModel->wem_location_map = $this->wem_location_map;
		$objModel->customTpl = $this->customTpl;
		$objModel->protected = $this->protected;
		$objModel->guests = $this->guests;
		$objModel->cssID = $this->cssID;

		$objModule = new ModuleDisplayMap($objModel);
		$this->Template->buffer = $objModule->generate();
	}
}