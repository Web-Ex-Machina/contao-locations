<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018-2019 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

$GLOBALS['TL_DCA']['tl_module']['palettes']['wem_display_map']    = '{title_legend},name,type;{config_legend},wem_location_map;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

$GLOBALS['TL_DCA']['tl_module']['fields']['wem_location_map'] = array(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['wem_location_map'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'foreignKey'              => 'tl_wem_map.title',
    'eval'                    => array('chosen'=>true, 'mandatory'=>true, 'tl_class'=>'w50'),
    'sql'                     => "int(10) unsigned NOT NULL default '0'"
);
