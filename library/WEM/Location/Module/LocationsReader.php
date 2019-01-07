<?php

/**
 * Module Locations for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\Location\Module;

use Contao\CoreBundle\Exception\InternalServerErrorException;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Patchwork\Utf8;

use Contao\Combiner;

use Haste\Input\Input;

use WEM\Location\Model\Location;

/**
 * Front end module "locations reader".
 */
class LocationsReader extends Core
{
	/**
	 * Map Template
	 * @var string
	 */
	protected $strTemplate = 'mod_wem_locations_reader';

	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate(){
		if (TL_MODE == 'BE'){
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['wem_display_map'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		// Set the item from the auto_item parameter
		if (!isset($_GET['items']) && \Config::get('useAutoItem') && isset($_GET['auto_item']))
			\Input::setGet('items', \Input::get('auto_item'));
		
		// Return an empty string if "items" is not set (to combine list and reader on same page)
		if (!\Input::get('items'))
			return '';
		
		return parent::generate();
	}

	/**
	 * Generate the module
	 */
	protected function compile(){
		try{
			/** @var PageModel $objPage */
			global $objPage;

			$this->Template->articles = '';
			$this->Template->referer = 'javascript:history.go(-1)';
			$this->Template->back = $GLOBALS['TL_LANG']['MSC']['goBack'];

			// Get the location item
			$objItem = Location::findByIdOrAlias(\Input::get('items'));

			// The location item does not exist or has an external target (see #33)
			if (null === $objItem)
				throw new PageNotFoundException('Page not found: ' . \Environment::get('uri'));

			$arrItem = $this->getLocation($objItem);
			$this->Template->item = $arrItem;
		}
		catch(\Exception $e){
			$this->Template->error = true;
			$this->Template->msg = $e->getMessage();
		}
	}
}