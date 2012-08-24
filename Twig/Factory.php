<?php

/**
 * This file is part of the Krypton package bundles.
 *
 * (c) Krypton krypton.whysofast.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krypton\BEMBundle\Twig;

/**
 * Factory for set lexers between Bem and Twig.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class Factory
{
    static public function factory(\Twig_Environment $twig, \Twig_LexerInterface $lexer)
    {
        $lexer->setLexer($twig->getLexer());
        $twig->setLexer($lexer);

        return $twig;
    }
}

