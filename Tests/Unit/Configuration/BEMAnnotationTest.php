<?php

namespace Krypton\BEMBundle\Tests\Unit\Configuration;

class BEMAnnotationTest extends \PHPUnit_Framework_TestCase
{
    public function testAnnotation()
    {
        $parser = new \Doctrine\Common\Annotations\DocParser();
        $parser->setImports(array('bem' => 'Krypton\\BEMBundle\\Controller\\Annotations\\BEM'));
        $annotations = $parser->parse("/**\n* @BEM()\n*/");
        $this->assertEquals(1, count($annotations));
        $this->_assertBEMAnnotation($annotations[0]);
    }

    public function testControllerAnnotation()
    {
        $controller = new \Krypton\BEMBundle\Tests\Unit\Fixtures\Controller\AnnotatedBEMController();
        /** @see \Sensio\Bundle\FrameworkExtraBundle\EventListener\ControllerListener::onKernelController() */
        $reflectionController = new \ReflectionObject($controller);
        $method = $reflectionController->getMethod('getSomethingAction');
        $request = new \Symfony\Component\HttpFoundation\Request();
        $reader = new \Doctrine\Common\Annotations\AnnotationReader();
        foreach ($reader->getMethodAnnotations($method) as $configuration) {
            if ($configuration instanceof \Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface) {
                $request->attributes->set('_' . $configuration->getAliasName(), $configuration);
            }
        }
        $this->_assertBEMAnnotation($request->attributes->get('_template'));
    }

    protected function _assertBEMAnnotation($bemTemplateConfiguration)
    {
        $this->assertInstanceOf('\\Krypton\BEMBundle\\Controller\\Annotations\\BEM', $bemTemplateConfiguration);
        $this->assertEquals('template', $bemTemplateConfiguration->getAliasName());
        $this->assertEquals('bem', $bemTemplateConfiguration->getEngine());
    }
}
