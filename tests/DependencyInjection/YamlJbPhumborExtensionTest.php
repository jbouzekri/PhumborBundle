<?php

namespace Jb\Bundle\PhumborBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Test Extension with yaml loading
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class YamlJbPhumborExtensionTest extends JbPhumborExtensionTest
{
    protected function loadFromFile(ContainerBuilder $container, $file)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/Fixtures/yml'));
        $loader->load($file.'.yml');
    }
}
