<?php

/**
 * This file is part of the Krypton package bundles.
 *
 * (c) Krypton krypton.whysofast.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krypton\BEMBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Krypton\BEMBundle\DependencyInjection\Compiler\TwigPass;
use Krypton\BEMBundle\DependencyInjection\Compiler\TemplatingLocationPass;

/**
 * KryptonBEMBundle
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class KryptonBEMBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TwigPass());
        $container->addCompilerPass(new TemplatingLocationPass());
    }

}

