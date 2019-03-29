<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018-2019 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

/**
 * Table tl_wem_map_category
 */
$GLOBALS['TL_DCA']['tl_wem_map_category'] = array(
    // Config
    'config' => array(
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_wem_map',
        'switchToEdit'                => true,
        'enableVersioning'            => true,
        'sql' => array(
            'keys' => array(
                'id' => 'primary',
                'pid' => 'index',
            )
        )
    ),

    // List
    'list' => array(
        'sorting' => array(
            'mode'                    => 4,
            'fields'                  => array('createdAt DESC'),
            'headerFields'            => array('title'),
            'panelLayout'             => 'filter;sort,search,limit',
            'child_record_callback'   => array('tl_wem_map_category', 'listItems'),
            'child_record_class'      => 'no_padding'
        ),
        'global_operations' => array(
            'all' => array(
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array(
            'edit' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_map_category']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'delete' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_map_category']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_map_category']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            ),
        )
    ),

    // Palettes
    'palettes' => array(
        'default'                     => '
			{general_legend},title;
			{marker_legend},marker,markerConfig
		'
    ),

    // Fields
    'fields' => array(
        'id' => array(
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array(
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'pid' => array(
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'createdAt' => array(
            'flag'                    => 8,
            'default'                 => time(),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),

        // {general_legend},title
        'title' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map_category']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        
        // {marker_legend},marker,markerConfig
        'marker' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map_category']['marker'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'),
            'sql'                     => "binary(16) NULL"
        ),
        'markerConfig' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_map_category']['markerConfig'],
            'exclude'                 => true,
            'inputType'               => 'keyValueWizard',
            'load_callback'           => array(
                array('tl_wem_map', 'getDefaultMapConfig'),
            ),
            'sql'                     => "blob NULL"
        ),
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */
class tl_wem_map_category extends Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Design each row of the DCA
     * @param  Array  $arrRow
     * @return String
     */
    public function listItems($row)
    {
        return $row['title'];
    }
}
