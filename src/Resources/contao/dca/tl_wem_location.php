<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018-2019 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

/**
 * Table tl_wem_location
 */
$GLOBALS['TL_DCA']['tl_wem_location'] = array(

    // Config
    'config' => array(
        'dataContainer'               => 'Table',
        'ptable'                      => 'tl_wem_map',
        'ctable'                      => array('tl_content'),
        'switchToEdit'                => true,
        'enableVersioning'            => true,
        'onload_callback'             => array(
            array('tl_wem_location', 'checkIfGeocodeExists'),
        ),
        'onsubmit_callback'           => array(
            //array('tl_wem_location', 'fetchCoordinates'),
        ),
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
            'fields'                  => array('country DESC'),
            'headerFields'            => array('title'),
            'panelLayout'             => 'filter;sort,search,limit',
            'child_record_callback'   => array('tl_wem_location', 'listItems'),
            'child_record_class'      => 'no_padding'
        ),
        'global_operations' => array(
            'geocodeAll' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['geocodeAll'],
                'href'                => 'key=geocodeAll',
                'class'               => 'header_geocodeAll',
                'attributes'          => 'onclick="Backend.getScrollOffset()" data-confirm="'.$GLOBALS['TL_LANG']['tl_wem_location']['geocodeAllConfirm'].'"'
            ),
            'import' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['import'],
                'href'                => 'key=import',
                'class'               => 'header_css_import',
                'attributes'          => 'onclick="Backend.getScrollOffset()"'
            ),
            'export' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['export'],
                'href'                => 'key=export',
                'class'               => 'header_css_import',
                'attributes'          => 'onclick="Backend.getScrollOffset()"'
            ),
            'all' => array(
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array(
            'edit' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['edit'],
                'href'                => 'table=tl_content',
                'icon'                => 'edit.svg'
            ),
            'editheader' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['editheader'],
                'href'                => 'act=edit',
                'icon'                => 'header.svg'
            ),
            'copy' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.gif'
            ),
            'delete' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            ),
            'toggle' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['toggle'],
                'icon'                => 'visible.gif',
                'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback'     => array('tl_wem_location', 'toggleIcon')
            ),
            'geocode' => array(
                'label'               => &$GLOBALS['TL_LANG']['tl_wem_location']['geocode'],
                'href'                => 'key=geocode',
                'icon'                => 'system/modules/wem-contao-locations/assets/backend/icon_geocode_16.png'
            ),
        )
    ),

    // Palettes
    'palettes' => array(
        'default'                     => '
			{location_legend},title,alias,category,published;
			{coords_legend},lat,lng;
			{street_legend},country,admin_lvl_1,admin_lvl_2,admin_lvl_3,city,postal,street;
			{data_legend},picture,teaser;
			{contact_legend},phone,email;
			{links_legend},website,facebook,twitter,instagram
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
        'createdAt' => array(
            'default'                 => time(),
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'pid' => array(
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),

        // {location_legend},title,alias,category,published;
        'title' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['title'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'alias' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['alias'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'alias', 'doNotCopy'=>true, 'unique'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'save_callback' => array(
                array('tl_wem_location', 'generateAlias')
            ),
            'sql'                     => "varchar(128) BINARY NOT NULL default ''"
        ),
        'category' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['category'],
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'flag'                    => 11,
            'inputType'               => 'select',
            'foreignKey'              => 'tl_wem_map_category.title',
            'options_callback'        => array('tl_wem_location', 'getMapCategories'),
            'eval'                    => array('chosen'=>true, 'includeBlankOption'=>true, 'tl_class'=>'w50'),
            'sql'                     => "int(10) unsigned NOT NULL default '0'",
            'relation'                => array('type'=>'hasOne', 'load'=>'lazy')
        ),
        'published' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['published'],
            'exclude'                 => true,
            'filter'                  => true,
            'flag'                    => 1,
            'inputType'               => 'checkbox',
            'eval'                    => array('doNotCopy'=>true, 'tl_class'=>'w50 m12'),
            'sql'                     => "char(1) NOT NULL default ''"
        ),

        // {coords_legend},lat,lng;
        'lat' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['lat'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'lng' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['lng'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        
        // {street_legend},street,postal,city,region,country;
        'street' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['street'],
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'eval'                    => array('tl_class'=>'w100 clr'),
            'sql'                     => "text NULL"
        ),
        'postal' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['postal'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'city' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['city'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'admin_lvl_1' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['admin_lvl_1'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'admin_lvl_2' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['admin_lvl_2'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'admin_lvl_3' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['admin_lvl_3'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'country' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['country'],
            'exclude'                 => true,
            'filter'                  => true,
            'sorting'                 => true,
            'inputType'               => 'select',
            'options'                 => System::getCountries(),
            'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
            'sql'                     => "varchar(2) NOT NULL default ''"
        ),

        // {data_legend},picture,teaser;
        'picture' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['picture'],
            'exclude'                 => true,
            'inputType'               => 'fileTree',
            'eval'                    => array('filesOnly'=>true, 'fieldType'=>'radio', 'tl_class'=>'clr'),
            'sql'                     => "binary(16) NULL"
        ),
        'teaser' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['teaser'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'textarea',
            'eval'                    => array('rte'=>'tinyMCE', 'tl_class'=>'clr'),
            'sql'                     => "text NULL"
        ),

        // {contact_legend},phone,email;
        'phone' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['phone'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>64, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) NOT NULL default ''"
        ),
        'email' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['email'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('maxlength'=>255, 'rgxp'=>'email', 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),

        // {links_legend},website,facebook,twitter,instagram
        'website' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['website'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'facebook' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['facebook'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'twitter' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['twitter'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'instagram' => array(
            'label'                   => &$GLOBALS['TL_LANG']['tl_wem_location']['instagram'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('rgxp'=>'url', 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        )
    )
);

// Load JS to handle backend events
$GLOBALS["TL_JAVASCRIPT"][] = 'https://code.jquery.com/jquery-3.3.1.min.js';
$GLOBALS["TL_JAVASCRIPT"][] = 'system/modules/wem-contao-locations/assets/backend/backend.js';

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

    /**
     * Get and return all the parent map categories
     * @param  [Datacontainer] $dc  [Datacontainer]
     * @return [Array]              [Categories]
     */
    public function getMapCategories(DataContainer $dc)
    {
        $arrData = [];
        
        if ($dc->activeRecord->pid) {
            $objCategories = $this->Database->prepare("SELECT id, title FROM tl_wem_map_category WHERE pid = ? ORDER BY createdAt ASC")->execute($dc->activeRecord->pid);

            if (!$objCategories) {
                return [];
            }
            
            while ($objCategories->next()) {
                $arrData[$objCategories->id] = $objCategories->title;
            }
        }

        return $arrData;
    }

    /**
     * Auto-generate the news alias if it has not been set yet
     *
     * @param mixed         $varValue
     * @param DataContainer $dc
     *
     * @return string
     *
     * @throws Exception
     */
    public function generateAlias($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ($varValue == '') {
            $autoAlias = true;
            $slugOptions = array();

            // Read the slug options from the associated page
            if (($objMap = \WEM\LocationsBundle\Model\Map::findByPk($dc->activeRecord->pid)) !== null && ($objPage = PageModel::findWithDetails($objMap->jumpTo)) !== null) {
                $slugOptions = $objPage->getSlugOptions();
            }

            $varValue = System::getContainer()->get('contao.slug.generator')->generate(StringUtil::prepareSlug($dc->activeRecord->title), $slugOptions);

            // Prefix numeric aliases (see #1598)
            if (is_numeric($varValue)) {
                $varValue = 'id-' . $varValue;
            }
        }

        $objAlias = $this->Database->prepare("SELECT id FROM tl_wem_location WHERE alias=? AND id!=?")
                                   ->execute($varValue, $dc->id);

        // Check whether the news alias exists
        if ($objAlias->numRows) {
            if (!$autoAlias) {
                throw new Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
            }

            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }

    /**
     * Adjust DCA if there is no Geocoder for the map
     */
    public function checkIfGeocodeExists()
    {
        $objMap = \WEM\LocationsBundle\Model\Map::findByPk(\Input::get('id'));

        if ('' == $objMap->geocodingProvider) {
            unset($GLOBALS['TL_DCA']['tl_wem_location']['list']['global_operations']['geocodeAll']);
            unset($GLOBALS['TL_DCA']['tl_wem_location']['list']['operations']['geocode']);
        }
    }

    /**
     * Design each row of the DCA
     * @param  Array  $arrRow
     * @return String
     */
    public function listItems($arrRow)
    {
        if (!$arrRow['lat'] || !$arrRow['lng']) {
            $strColor = '#ff0000';
        } else {
            $strColor = '#333';
        }

        $strRow = sprintf('<span style="color:%s">%s</span> <span style="color:#888">[%s - %s]</span>', $strColor, $arrRow['title'], $arrRow['city'], $GLOBALS['TL_LANG']['CNT'][$arrRow['country']]);
        $strRow .= '<div class="ajax-results"></div>';
        return $strRow;
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
        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            $this->redirect($this->getReferer());
        }

        // Check permissions AFTER checking the tid, so hacking attempts are logged
        if (!$this->User->hasAccess('tl_wem_location::published', 'alexf')) {
            return '';
        }

        $href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

        if (!$row['published']) {
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
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc = null)
    {
        // Check permissions to edit
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        // Check permissions to publish
        if (!$this->User->hasAccess('tl_wem_location::published', 'alexf')) {
            $this->log('Not enough permissions to publish/unpublish agence item ID "'.$intId.'"', __METHOD__, TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }

        $objVersions = new Versions('tl_wem_location', $intId);
        $objVersions->initialize();

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA']['tl_wem_location']['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA']['tl_wem_location']['fields']['published']['save_callback'] as $callback) {
                if (is_array($callback)) {
                    $this->import($callback[0]);
                    $blnVisible = $this->$callback[0]->$callback[1]($blnVisible, ($dc ?: $this));
                } elseif (is_callable($callback)) {
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
