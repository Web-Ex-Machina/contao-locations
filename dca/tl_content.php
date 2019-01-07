<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

\System::loadLanguageFile('tl_wem_map');

$GLOBALS['TL_DCA']['tl_content']['palettes']['wem_locations']    = '{type_legend},type,wem_location_map;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['wem_location_map'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wem_location_map'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_content_wem_locations', 'getMaps'),
	'foreignKey'              => 'tl_wem_map.title',
	'eval'                    => array('chosen'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

// Dynamically add the permission check and parent table
if (Input::get('do') == 'wem-maps'){
	$GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_wem_location';
}

/**
 * Provide functions to tl_content elements related to wem_locations module
 */
class tl_content_wem_locations extends tl_content
{
	/**
	 * Get and format available maps
	 * @return [Array] [Available Maps]
	 */
	public function getMaps(){
		$maps = WEM\Location\Model\Map::findAll();

		if(!$maps || 0 == $maps->count())
			return [];

		$data = [];
		while($maps->next())
			$data[$maps->id] = $maps->title.' | '.$GLOBALS['TL_LANG']['tl_wem_map']['mapProvider'][$maps->mapProvider];

		return $data;
	}
}