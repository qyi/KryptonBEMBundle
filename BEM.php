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

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Bem template builder.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class BEM
{
    protected $container;
    protected $parser;
    protected $assetFactory;

    /**
     * Constructor.
     *
     * @param ContainerInterface            $container      A service container
     * @param TemplateNameParserInterface   $parser         A TemplateNameParserInterface instance
     * @param AssetFactory                  $assetFactory   A asset factory
     */
    public function __construct($container, $parser, $assetFactory)
    {
        $this->contianer = $container;
        $this->parser = $parser;
        $this->assetFactory = $assetFactory;
    }

    /**
     * Build template by name.
     *
     * @param string    $name A template name
     * @return string   Compiled template
     */
    public function build($name)
    {
        $reference = $this->parser->parse($name);
        $assetName = $this->assetFactory->generateAssetName($reference->getPath(), array('bemhtml'), array());
        $output = $this->contianer->get('assetic.asset_manager')
                ->get($assetName)
                ->dump();

        return $output;
    }
}