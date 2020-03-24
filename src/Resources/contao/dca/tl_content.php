<?php

declare(strict_types=1);

/**
 * Contao Locations for Contao Open Source CMS
 * Copyright (c) 2015-2020 Web ex Machina
 *
 * @category ContaoBundle
 * @package  Web-Ex-Machina/contao-locations
 * @author   Web ex Machina <contact@webexmachina.fr>
 * @link     https://github.com/Web-Ex-Machina/contao-locations/
 */

if ('wem-maps' === Contao\Input::get('do')) {
    $GLOBALS['TL_DCA']['tl_content']['config']['ptable'] = 'tl_wem_location';
}
