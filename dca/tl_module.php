<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018-2019 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'wem_location_map_filters';
$GLOBALS['TL_DCA']['tl_module']['palettes']['wem_display_map']    = '
	{title_legend},name,type;
	{config_legend},wem_location_map,wem_location_map_list,wem_location_map_filters;
	{template_legend:hide},customTpl;
	{protected_legend:hide},protected;
	{expert_legend:hide},guests,cssID
';
$GLOBALS['TL_DCA']['tl_module']['palettes']['wem_location_reader']    = '
	{title_legend},name,type;
	{template_legend:hide},customTpl;
	{protected_legend:hide},protected;
	{expert_legend:hide},guests,cssID
';

$GLOBALS['TL_DCA']['tl_module']['subpalettes']['wem_location_map_filters_inmap'] = 'wem_location_map_filters_fields';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['wem_location_map_filters_inlist'] = 'wem_location_map_filters_fields';

$GLOBALS['TL_DCA']['tl_module']['fields']['wem_location_map'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['wem_location_map'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_wem_map.title',
    'eval'                    => array('chosen'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['wem_location_map_list'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['wem_location_map_list'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => ['nolist', 'rightpanel', 'below'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_module']['wem_location_map_list'],
    'eval'                    => array('chosen'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(32) NOT NULL default 'nolist'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['wem_location_map_filters'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['wem_location_map_filters'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => ['nofilters', 'inmap', 'inlist'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_module']['wem_location_map_filters'],
    'eval'                    => array('submitOnChange'=>true, 'chosen'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(32) NOT NULL default 'nofilters'"
);
$GLOBALS['TL_DCA']['tl_module']['fields']['wem_location_map_filters_fields'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['wem_location_map_filters_fields'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => ['search', 'category', 'country', 'admin_lvl_1', 'admin_lvl_2', 'city'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_module']['wem_location_map_filters_fields'],
    'eval'                    => array('chosen'=>true, 'mandatory'=>true, 'multiple'=>true, 'tl_class'=>'w50'),
    'sql'                     => "blob NULL'"
);