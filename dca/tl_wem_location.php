<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

/**
 * Table tl_wem_location
 */
$GLOBALS['TL_DCA']['tl_wem_location'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'					  => 'tl_wem_map',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onsubmit_callback'			  => array
		(
			//array('tl_wem_location', 'fetchCoordinates'),
		),
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('country DESC'),
			'headerFields'            => array('title'),
			'panelLayout'             => 'filter;sort,search,limit',
			'child_record_callback'   => array('tl_wem_location', 'listItems'),
			'child_record_class'      => 'no_padding'
		),
		'global_operations' => array
		(
			'import' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['import'],
				'href'                => 'key=import',
				'class'               => 'header_css_import',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
			'export' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['export'],
				'href'                => 'key=export',
				'class'               => 'header_css_import',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_wem_location', 'toggleIcon')
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '
			{location_legend},title,published;
			{coords_legend},lat,lng;
			{street_legend},street,postal,city,region,country;
			{contact_legend},phone,email,website
		'
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
		'pid' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),

		// {location_legend},title,published;
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
			'sql'                     => "char(1) NOT NULL default ''"
		),

		// {coords_legend},lat,lng;
		'lat' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['lat'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'lng' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['lng'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		
		// {street_legend},street,postal,city,region,country;
		'street' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['street'],
			'exclude'                 => true,
			'inputType'               => 'textarea',
			'eval'                    => array('tl_class'=>'w100 clr'),
			'sql'                     => "text NULL"
		),
		'postal' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['postal'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'city' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['city'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'region' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['region'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'country' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['country'],
			'exclude'                 => true,
			'filter'                  => true,
			'sorting'                 => true,
			'inputType'               => 'select',
			'options'                 => System::getCountries(),
			'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
			'sql'                     => "varchar(2) NOT NULL default ''"
		),

		// {contact_legend},phone,email,website;
		'phone' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['phone'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>64, 'tl_class'=>'w50'),
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'email' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['email'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'rgxp'=>'email', 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'website' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['website'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		)
	)
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */
class tl_wem_location extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function listItems($arrRow)
	{
		if(!$arrRow['lat'] || !$arrRow['lng'])
			$strColor = '#ff0000';
		else
			$strColor = '#333';

		return sprintf('<span style="color:%s">%s</span> <span style="color:#888">[%s - %s]</style>', $strColor, $arrRow['title'], $arrRow['city'], $GLOBALS['TL_LANG']['CNT'][$arrRow['country']]);
	}

	/**
	 * Return the "toggle visibility" button
	 *
	 * @param array  $row
	 * @param string $href
	 * @param string $label
	 * @param string $title
	 * @param string $icon
	 * @param string $attributes
	 *
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->hasAccess('tl_wem_location::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a agence
	 *
	 * @param integer       $intId
	 * @param boolean       $blnVisible
	 * @param DataContainer $dc
	 */
	public function toggleVisibility($intId, $blnVisible, DataContainer $dc=null)
	{
		// Check permissions to edit
		Input::setGet('id', $intId);
		Input::setGet('act', 'toggle');

		// Check permissions to publish
		if (!$this->User->hasAccess('tl_wem_location::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish agence item ID "'.$intId.'"', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$objVersions = new Versions('tl_wem_location', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_wem_location']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_wem_location']['fields']['published']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, ($dc ?: $this));
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_wem_location SET tstamp=". time() .", published='" . ($blnVisible ? '1' : '') . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_wem_location.id='.$intId.'" has been created'.$this->getParentEntries('tl_wem_location', $intId), __METHOD__, TL_GENERAL);
	}
}