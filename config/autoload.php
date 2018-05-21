<?php

/**
 * Locations Extension for Contao Open Source CMS
 *
 * Copyright (c) 2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

/**
 * Register PSR-0 namespace
 */
if (class_exists('NamespaceClassLoader'))
{
    NamespaceClassLoader::add('WEM', 'system/modules/wem-contao-locations/library');
}

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	// Map Templates
	'mod_wem_locations_map'	=> 'system/modules/wem-contao-locations/templates/modules',
));