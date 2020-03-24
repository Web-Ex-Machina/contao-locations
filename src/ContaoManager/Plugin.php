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

namespace WEM\LocationsBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use WEM\LocationsBundle\WEMLocationsBundle;

/**
 * Plugin for the Contao Manager.
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(WEMLocationsBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
                ->setReplace(['wem-locations']),
        ];
    }
}
