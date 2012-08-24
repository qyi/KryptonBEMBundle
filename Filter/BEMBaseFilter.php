<?php

/**
 * This file is part of the Krypton package bundles.
 *
 * (c) Krypton krypton.whysofast.ru
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Krypton\BEMBundle\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Exception\FilterException;
use Assetic\Filter\FilterInterface;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Base Bem filter for build.
 * Initialize Bem environment, folder and system files.
 * Build decl and deps files.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
abstract class BEMBaseFilter implements FilterInterface
{
    protected $bemPath;
    protected $nodePath;

    protected $levels;

    public function __construct($bemPath = '/usr/bin/bem', $nodePath = '/usr/bin/node', $levels = array())
    {
        $this->bemPath = $bemPath;
        $this->nodePath = $nodePath;
        $this->levels = $levels;
    }

    /**
     * Set the Bem levels
     *
     * @param array $levels 
     */
    public function setLevels(array $levels)
    {
        $this->levels = $levels;
    }

    function filterLoad(AssetInterface $asset)
    {
    }

    /**
     * Init working folders.
     * And fill bem system files for compilation.
     *
     * @param string $baseLocation Base working folder
     */
    public function initFolders($baseLocation)
    {
        if (!file_exists($baseLocation)) {
            mkdir($baseLocation);
        }

        $bemSystemFolder = $baseLocation . '/.bem';
        if (!file_exists($bemSystemFolder)) {
            mkdir($bemSystemFolder);
        }

        $levelLocation = $bemSystemFolder . '/level.js';
        if (!file_exists($levelLocation)) {
            file_put_contents($levelLocation, '');
        }
    }

    /**
     * Init folders for any bem template.
     * In this folder will compiled bemdecl.js deps.js
     *
     * @param string $name Asset name for init folders
     * @return string
     */
    public function initAssetDir($name)
    {
        $baseLocation = sys_get_temp_dir() . '/bem';
        $this->initFolders($baseLocation);
        
        $inputDir = $baseLocation . '/' . $name;
        if (!file_exists($inputDir)) {
            mkdir($inputDir);
        }
        
        return $inputDir;
    }

    /**
     * Build bemdecl.js file from bemjson content.
     *
     * @param   \SplFileInfo $bemjson Default template file.
     * @return  \SplFileInfo $bemdecl Builded bemdecl file.
     */
    public function buildBemDecl(\SplFileInfo $bemjson)
    {
        $bemdecl = new \SplFileInfo(str_replace('bemjson.js', 'bemdecl.js', $bemjson->getPathname()));

        if (file_exists($bemdecl)) {
            unlink($bemdecl);
        }

        $pb = new ProcessBuilder(array($this->nodePath, $this->bemPath));
        $pb->add('create')->add('block')
            ->add('-t')->add('bemdecl.js')
            ->add($bemjson->getBasename('.bemjson.js'));

        $pb->setWorkingDirectory($bemjson->getPath());

        $proc = $pb->getProcess();
        $code = $proc->run();

        if (0 < $code && !$bemdecl->isFile()) {
            throw FilterException::fromProcess($proc);
        }

        return $bemdecl;
    }

    /**
     * Build deps.js file from bemdecl content.
     *
     * @param   \SplFileInfo $bemjson Declaration file
     * @return  \SplFileInfo $bemdecl Builded deps file.
     */
    public function buildDeps(\SplFileInfo $bemdecl)
    {
        $deps = new \SplFileInfo(str_replace('bemdecl.js', 'deps.js', $bemdecl->getPathname()));

        if (file_exists($deps)) {
            unlink($deps);
        }

        $pb = new ProcessBuilder(array($this->nodePath, $this->bemPath));
        $pb->add('build')
            ->add('-d')->add($bemdecl->getBasename())
            ->add('-t')->add('deps.js')
            ->add('-n')->add($bemdecl->getBasename('.bemdecl.js'));

        foreach ($this->levels as $level) {
            $pb->add('-l')->add($level);
        }

        $pb->setWorkingDirectory($bemdecl->getPath());

        $proc = $pb->getProcess();
        $code = $proc->run();

        if (0 < $code && !$deps->isFile()) {
            throw FilterException::fromProcess($proc);; ///->setInput($asset->getContent());
        }

        return $deps;
    }
}