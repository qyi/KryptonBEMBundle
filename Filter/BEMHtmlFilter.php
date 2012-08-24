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
 * Build bemhtml and html from deps.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class BEMHtmlFilter extends BEMBaseFilter
{
    protected $bemBlPath;
    protected $nodeModulesPath;

    /**
     * Set path for bem-bl block library.
     *
     * @param string $bemBlPath
     */
    public function setBemBlPath($bemBlPath)
    {
        $this->bemBlPath = $bemBlPath;
    }
    
    /**
     * Get path for bem-bl block library.
     *
     * @return string
     */
    public function getBemBlPath()
    {
        return $this->bemBlPath;
    }
    
    /**
     * For run in one environment use node modules path
     * show by command `npm -g root`
     *
     * @param string $bemBlPath
     */
    public function setNodeModulesPath($nodeModulesPath)
    {
        $this->nodeModulesPath = $nodeModulesPath;
    }
    
    public function getNodeModulesPath()
    {
        return $this->nodeModulesPath;
    }

    public function filterDump(AssetInterface $asset)
    {
        $name = substr(sha1(serialize($asset)), 0, 7);
        $inputDir = $this->initAssetDir($name);

        $bemjson = new \SplFileInfo($inputDir . '/' . $name . '.bemjson.js');
        file_put_contents($bemjson, $asset->getContent());

        $bemdecl = $this->buildBemDecl($bemjson);
        $deps = $this->buildDeps($bemdecl);
        $bemhtml = $this->buildBemhtml($deps);

        $output = new \SplFileInfo(str_replace('bemhtml.js', 'html', $bemhtml->getPathname()));

        $pb = new ProcessBuilder(array($this->nodePath, $this->bemPath));
        $pb->add('create')->add('block')
            ->add('-T')->add($this->bemBlPath . '/blocks-common/i-bem/bem/techs/html.js')
            ->add('-f')->add($output->getBasename('.html'));
        
        $pb->setWorkingDirectory($output->getPath());
        
        $proc = $pb->getProcess();
        $code = $proc->run();

        if (0 < $code && !$output->isFile()) {
            throw FilterException::fromProcess($proc)->setInput($asset->getContent());
        }

        $asset->setContent(file_get_contents($output));
        
        foreach (array($bemjson, $bemdecl, $deps, $bemhtml, $output) as $file) {
            unlink($file);
        }
    }

    /**
     * Build bemhtml from deps file.
     *
     * @param \SplFileInfo $deps The *.deps file builded page.
     */
    public function buildBemhtml(\SplFileInfo $deps)
    {
        $bemhtml = new \SplFileInfo(str_replace('deps.js', 'bemhtml.js', $deps->getPathname()));
     
        if (file_exists($bemhtml)) {
            unlink($bemhtml);
        }
     
        $pb = new ProcessBuilder(array($this->nodePath, $this->bemPath));
        $pb->add('build')
            ->add('-d')->add($deps->getBasename())
            ->add('-t')->add($this->bemBlPath . '/blocks-common/i-bem/bem/techs/bemhtml.js')
            ->add('-n')->add($deps->getBasename('.deps.js'));

        if ($this->nodeModulesPath) {
            $pb->setEnv('NODE_PATH', '$NODE_PATH:' . $this->nodeModulesPath);
        }
        
        $pb->setWorkingDirectory($deps->getPath());

        foreach ($this->levels as $level) {
            $pb->add('-l')->add($level);
        }

        $proc = $pb->getProcess();
        $code = $proc->run();
        
        if (0 < $code && !$bemhtml->isFile()) {
            throw FilterException::fromProcess($proc);; ///->setInput($asset->getContent());
        }
        
        return $bemhtml;
    }
}