<?php

/**
 * This file is part of the Krypton package bundles.
 *
 * (c) Krypton krypton.whysofast.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krypton\BEMBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Bundle\AsseticBundle\DependencyInjection\DirectoryResourceDefinition;
use Symfony\Component\Finder\Finder;

/**
 * KryptonBEMExtension
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class KryptonBEMExtension extends Extension
{
    /**
     * Responds to the bem configuration parameter.
     *
     * @param array             $configs
     * @param ContainerBuilder  $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('bem.yml');
        $loader->load('filters.yml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (isset($config['filters']) && isset($config['filters']['bem'])) {
            $container->setParameter('bem.assetic.filter.bin', $config['filters']['bem']['bin']);
        }

        $container->setParameter('bem.levels', $config['levels']);
        $container->setParameter('bem.node_modules', $config['node_modules']);
        $container->setParameter('bem.bem_bl', $config['bem_bl']);
    }
}
