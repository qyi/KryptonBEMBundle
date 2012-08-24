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
 * Build css from deps.
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class BEMCssFilter extends BEMBaseFilter
{
    public function filterDump(AssetInterface $asset)
    {
        $name = substr(sha1(serialize($asset)), 0, 7);
        $inputDir = $this->initAssetDir($name);

        $bemjson = new \SplFileInfo($inputDir . '/' . $name . '.bemjson.js');
        file_put_contents($bemjson, $asset->getContent());

        $bemdecl = $this->buildBemDecl($bemjson);
        $deps = $this->buildDeps($bemdecl);
        
        $output = new \SplFileInfo(str_replace('deps.js', 'css', $deps->getPathname()));
        
        $pb = new ProcessBuilder(array($this->nodePath, $this->bemPath));
        $pb->add('build')
            ->add('-d')->add($deps)
            ->add('-t')->add('css')
            ->add('-o')->add($output->getPath())
            ->add('-n')->add($name);

        foreach ($this->levels as $level) {
            $pb->add('-l')->add($level);
        }
        
        $proc = $pb->getProcess();
        $code = $proc->run();

        if (0 < $code && !$output->isFile()) {
            throw FilterException::fromProcess($proc)->setInput($asset->getContent());
        }

        $asset->setContent($this->combine(file_get_contents($output)));
        
        foreach (array($bemjson, $bemdecl, $deps, $output) as $file) {
            unlink($file);
        }
    }

    /**
     * Combine css files, load and merge @imports
     * For full functionality use borschik.
     *
     * @param string $css Builded css
     */
    public function combine($css)
    {
        $content = "/**\n" . $css . "\n*/\n";
        foreach (explode("\n", $css) as $line) {
            $path = substr($line, 14, -2);
            if (file_exists($path)) {
	        	$content .= sprintf("/** start %s */\n", $path);
                $content .= file_get_contents($path);
		        $content .= sprintf("\n/** end %s */\n\n", $path);
            }
        }

        return $content;
    }
}