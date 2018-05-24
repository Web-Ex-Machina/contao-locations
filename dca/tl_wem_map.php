<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

/**
 * Table tl_wem_map
 */
$GLOBALS['TL_DCA']['tl_wem_map'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_wem_location'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'filter;search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title', 'mapProvider'),
			'format'                  => '%s | %s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_map']['edit'],
				'href'                => 'table=tl_wem_location',
				'icon'                => 'edit.gif'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_map']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_map']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_map']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_map']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'	=> array('mapProvider', 'geocodingProvider'),
		'default'		=> '
			{title_legend},title;
			{import_legend},excelPattern;
			{map_legend},mapProvider;
			{geocoding_legend},geocodingProvider
		'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'mapProvider_jvector' =>'mapConfig',
		'mapProvider_gmaps' => 'mapProviderGmapKey,mapConfig',
		'geocodingProvider_gmaps' => 'geocodingProviderGmapKey',
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'excelPattern' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map']['excelPattern'],
			'exclude'                 => true,
			'inputType'               => 'keyValueWizard',
			'load_callback'			  => array
			(
				array('tl_wem_map', 'generateExcelPattern'),
			),
			'sql'                     => "blob NULL"
		),
		'mapProvider' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map']['mapProvider'],
			'default'				  => 'jvector',
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array('jvector', 'gmaps'),
			'reference'				  => &$GLOBALS['TL_LANG']['tl_wem_map']['mapProvider'],
			'eval'                    => array('helpwizard'=>true, 'mandatory'=>true, 'submitOnChange'=>true, 'chosen'=>true),
			'explanation'             => 'wem_locations_mapProvider',
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'mapConfig' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map']['mapConfig'],
			'exclude'                 => true,
			'inputType'               => 'keyValueWizard',
			'load_callback'			  => array
			(
				array('tl_wem_map', 'getDefaultMapConfig'),
			),
			'sql'                     => "blob NULL"
		),
		'mapProviderGmapKey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map']['mapProviderGmapKey'],
			'exclude'                 => true,
			'inputType'               => 'textStore',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'encrypt'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'geocodingProvider' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map']['geocodingProvider'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'				  => array('nominatim', 'gmaps'),
			'reference'				  => &$GLOBALS['TL_LANG']['tl_wem_map']['geocodingProvider'],
			'eval'                    => array('helpwizard'=>true, 'includeBlankOption'=>true, 'submitOnChange'=>true, 'chosen'=>true),
			'explanation'             => 'wem_locations_geocodingProvider',
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'geocodingProviderGmapKey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map']['geocodingProviderGmapKey'],
			'exclude'                 => true,
			'inputType'               => 'textStore',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'encrypt'=>true),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
	)
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */
class tl_wem_map extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct(){
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Generate the default map config array
	 * @param  [Array] $varValue
	 * @return [Array]
	 */
	public function getDefaultMapConfig($varValue, $objDc){
		if(!$varValue){
			switch($objDc->activeRecord->mapProvider){
				case 'jvector':
					$arrConfig = \WEM\Location\Controller\Provider\JVector::getDefaultConfig();
				break;

				default:
					$arrConfig = [];
			}

			foreach($arrConfig as $strKey => $strValue){
				$varValue[] = ["key"=>$strKey, "value"=>$strValue];
			}
		}
		return $varValue;
	}

	/**
	 * Generate the default Excel pattern
	 * @param  [Array] $varValue
	 * @return [Array]
	 */
	public function generateExcelPattern($varValue){
		if(!$varValue){
			$varValue = [
				["key"=>"title", "value"=>"A"]
				,["key"=>"lat", "value"=>"B"]
				,["key"=>"lng", "value"=>"C"]
				,["key"=>"street", "value"=>"D"]
				,["key"=>"postal", "value"=>"E"]
				,["key"=>"city", "value"=>"F"]
				,["key"=>"region", "value"=>"G"]
				,["key"=>"country", "value"=>"H"]
				,["key"=>"phone", "value"=>"I"]
				,["key"=>"email", "value"=>"J"]
				,["key"=>"website", "value"=>"K"]
			];
		}

		return $varValue;
	}
}