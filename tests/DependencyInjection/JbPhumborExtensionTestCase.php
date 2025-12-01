<?php

namespace Jb\Bundle\PhumborBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Jb\Bundle\PhumborBundle\DependencyInjection\JbPhumborExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Test Extension
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
abstract class JbPhumborExtensionTestCase extends TestCase
{
    /**
     * Defined in children class
     * One per type of loader : yaml, xml, php
     * Load the configuration file in the container
     *
     * @return void
     */
    abstract protected function loadFromFile(ContainerBuilder $container, $file);

    public function testLoading(): void
    {
        $container = $this->createContainerFromFile('transformers');

        $this->assertEquals($container->getParameter('phumbor.server.url'), 'http://localhost:8888');
        $this->assertEquals($container->getParameter('phumbor.secret'), '123456789');
        $this->assertEquals(
            $container->getParameter('phumbor.transformations'),
            ['fit_in_test' => ['fit_in' => ['width'=>100, 'height'=>80]]]
        );
    }

    /**
     * Create container with the current bundle enabled
     *
     * @param array $data
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function createContainer(array $data = []): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag(array_merge([
            'kernel.bundles'     => ['JbPhumborBundle' => 'Jb\\Bundle\\PhumborBundle\\JbPhumborBundle'],
            'kernel.cache_dir'   => __DIR__,
            'kernel.debug'       => false,
            'kernel.environment' => 'test',
            'kernel.name'        => 'kernel',
            'kernel.root_dir'    => __DIR__,
        ], $data)));
    }

    /**
     * Register a configuration file
     * @see \Jb\Bundle\PhumborBundle\Tests\DependencyInjection\YamlJbPhumborExtensionTest
     *
     * @param string $file
     * @param array $data
     *
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    protected function createContainerFromFile($file, $data = []): ContainerBuilder
    {
        $container = $this->createContainer($data);
        $container->registerExtension(new JbPhumborExtension());
        $this->loadFromFile($container, $file);

        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->compile();

        return $container;
    }
}
