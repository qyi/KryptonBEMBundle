<?php

/**
 * This file is part of the Krypton package bundles.
 *
 * (c) Krypton krypton.whysofast.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krypton\BEMBundle\Composer;

use Composer\Script\Event;
use Krypton\Bridge\Composer\ScriptHandler\InstallationScriptHandler;
use Krypton\Bridge\Composer\Util\ComposerPathFinder;

/**
 * Composer bem-bl script handler. Install bem-bl to resources.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class ScriptHandler extends InstallationScriptHandler
{
    /**
     * Create symlink from bem-bl to Resources directory
     * for using bem-bl files as resource.
     *
     * @param Composer\Script\Event $event
     */
    public static function installBEMBl(Event $event)
    {
        self::install($event, 'krypton/bembundle', 'bem/bem-bl', array('targetSuffix' => '/Resources/bem-bl'));
    }
}