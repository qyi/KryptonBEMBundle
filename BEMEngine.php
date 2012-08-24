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

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Krypton\BEMBundle\Filter\BEMFilter;
use Krypton\BEMBundle\Filter\BEMServerFilter;

/**
 * This engine knows how to render Bem templates.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class BEMEngine implements EngineInterface
{
    protected $twigEngine;
    protected $parser;

    /**
     * Constructor.
     *
     * @param TwigEngine                    $twigEngine A TwigEngine instance
     * @param TemplateNameParserInterface   $parser     A TemplateNameParserInterface instance
     */
    public function __construct($twigEngine, $parser)
    {
        $this->twigEngine = $twigEngine;
        $this->parser = $parser;
    }

    /**
     * Renders a template.
     *
     * @param mixed $name       A template name
     * @param array $parameters An array of parameters to pass to the template
     *
     * @return string The evaluated template as a string
     *
     * @throws \InvalidArgumentException if the template does not exist
     * @throws \RuntimeException         if the template cannot be rendered
     */
    public function render($name, array $parameters = array())
    {
        return $this->twigEngine->render($name, $parameters);
    }

    /**
     * Renders a view and returns a Response.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A Response instance
     *
     * @return Response A Response instance
     */
    public function renderResponse($view, array $parameters = array(), Response $response = null)
    {
        return $this->twigEngine->renderResponse($view, $parameters, $response);
    }

    /**
     * Returns true if this class is able to render the given template.
     *
     * @param string $name A template name
     *
     * @return Boolean True if this class supports the given resource, false otherwise
     */
    public function supports($name)
    {
        $template = $this->parser->parse($name);

        return 'bem' === $template->get('engine');
    }

    public function exists($name)
    {
    }

    public function load($name)
    {
    }
}