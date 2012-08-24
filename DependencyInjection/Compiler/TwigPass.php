<?php

/**
 * This file is part of the Krypton package bundles.
 *
 * (c) Krypton krypton.whysofast.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krypton\BEMBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Pass Twig to Bem for tokenize templates.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class TwigPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $twig = $container->getDefinition('twig');
        $bemTwig = $container->getDefinition('bem.twig');

        $bemTwig->replaceArgument(0, $twig);
        $container->setDefinition('twig', $bemTwig);
    }
}