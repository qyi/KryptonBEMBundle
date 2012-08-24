<?php

/**
 * This file is part of the Krypton package bundles.
 *
 * (c) Krypton krypton.whysofast.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krypton\BEMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Finder;
use Krypton\BEMBundle\Controller\Annotations\BEM;

/**
 * Example using bem template.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class ExampleController extends Controller
{
    /**
     * @BEM()
     *
     * @return Response
     */
    public function exampleAction()
    {
    }
}