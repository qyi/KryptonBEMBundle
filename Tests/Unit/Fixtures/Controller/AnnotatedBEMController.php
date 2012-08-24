<?php
namespace Krypton\BEMBundle\Tests\Unit\Fixtures\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Krypton\BEMBundle\Controller\Annotations\BEM;

class AnnotatedBEMController extends Controller
{
    /**
     * @BEM()
     */
    public function getSomethingAction()
    {}
}
