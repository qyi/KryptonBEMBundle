<?php

/**
 * This file is part of the Krypton package bundles.
 *
 * (c) Krypton krypton.whysofast.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krypton\BEMBundle\Extension;

use Assetic\Factory\Resource\FileResource;
use Assetic\Factory\Resource\ResourceInterface;
use Assetic\Exception\FilterException;
use Assetic\Extension\Twig\TwigFormulaLoader;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Loads asset formulae from Bem templates.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class BEMFormulaLoader extends TwigFormulaLoader
{
    protected $assetFactory;
    protected $parser;

    /**
     * Constructor.
     *
     * @param AssetFactory                  $assetFactory   A asset factory
     * @param TemplateNameParserInterface   $parser         A TemplateNameParserInterface instance
     * @param Twig                          $twig           A Twig instance
     */
    public function __construct($assetFactory, $parser, $twig)
    {
        $this->assetFactory = $assetFactory;
        $this->parser = $parser;

        parent::__construct($twig);
    }

    /**
     * Load formulae for Bem templates
     * Each template create in tech html, css, js
     * and load twig formulae from template for 
     * can use twig expressions in bem content.
     *
     * @param ResourceInterface     $resource
     * @return array                $formulae
     */
    public function load(ResourceInterface $resource)
    {
        $formulae = array();
        foreach ($resource as $file) {
            $reference = $this->parser->parse((string) $file);
            $template = $reference->getPath();

            $options = array(
                'name' => $this->assetFactory
                            ->generateAssetName($template, array('bemhtml'), array())
            );

            $formulae[$options['name']] = array($template, array('bemhtml'), $options);

            foreach (array('css', 'js') as $tech) {
                $output = sprintf(
                    '/bundles/%s/%s/%s/%s.%s',
                    str_replace('Bundle', '', $reference->get('bundle')),
                    $reference->get('controller'),
                    $tech,
                    $reference->get('name'),
                    $tech
                );
                $output = strtolower($output);

                $options = array(
                    'output' => $output,
                    'name' => $this->assetFactory
                                ->generateAssetName($template, array('bem' . $tech), array('output' => $output))
                );

                $formulae[$options['name']] = array($template, array('bem' . $tech), $options);
            }
        }

        return $formulae + parent::load($resource);
    }
}