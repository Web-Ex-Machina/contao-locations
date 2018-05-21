<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['wem_locations']    = '{type_legend},type,wem_location_map;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['wem_location_map'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['wem_location_map'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_wem_map.title',
	'eval'                    => array('chosen'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);