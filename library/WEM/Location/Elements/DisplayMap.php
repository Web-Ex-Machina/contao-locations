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