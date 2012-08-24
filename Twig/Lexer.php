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
 * KryptonBEMExtension
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class Lexer implements \Twig_LexerInterface
{
    private $environment;
    private $lexer;

    /**
     * Constructor.
     *
     * @param BEM   $environment
     */
    public function __construct($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Implement twig lexer
     *
     * @param \Twig_LexerInterface $lexer
     */
    public function setLexer(\Twig_LexerInterface $lexer)
    {
        $this->lexer = $lexer;
    }

    /**
     * Compile Bem template 
     * and if it has Twig expressions tokenize in Twig.
     *
     * @param string    $code
     * @param string    $filename
     * @return string
     */
    public function tokenize($code, $filename = null)
    {
        if (null !== $filename && preg_match('/\.bem$/', $filename)) {
            $code = $this->environment->build($filename);
        }

        return $this->lexer->tokenize($code, $filename);
    }
}

