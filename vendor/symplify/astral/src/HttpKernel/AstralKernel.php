<?php

declare (strict_types=1);
namespace RectorPrefix20210824\Symplify\Astral\HttpKernel;

use RectorPrefix20210824\Symfony\Component\Config\Loader\LoaderInterface;
use RectorPrefix20210824\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;
final class AstralKernel extends \RectorPrefix20210824\Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel
{
    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     */
    public function registerContainerConfiguration($loader) : void
    {
        $loader->load(__DIR__ . '/../../config/config.php');
    }
}
