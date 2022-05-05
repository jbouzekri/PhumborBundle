<?php

namespace Jb\Bundle\PhumborBundle\Tests\Twig;

use Jb\Bundle\PhumborBundle\Twig\PhumborExtension;
use Jb\Bundle\PhumborBundle\Transformer\BaseTransformer;
use PHPUnit\Framework\TestCase;
use Thumbor\Url\BuilderFactory;

/**
 * Description of PhumborExtensionTest
 *
 * @author jobou
 */
class PhumborExtensionTest extends TestCase
{
    /**
     * @var \Jb\Bundle\PhumborBundle\Twig\PhumborExtension
     */
    private $extension;

    /**
     * @var \Thumbor\Url\BuilderFactory
     */
    private $factory;

    /**
     * SetUp
     */
    public function setUp(): void
    {
        $this->factory = new BuilderFactory('http://localhost', '123456789');
        $transformer = new BaseTransformer(
            $this->factory,
            array(
                'width_50' => array(
                    'resize' => array('width'=>50, 'height'=>0)
                )
            )
        );
        $this->extension = new PhumborExtension($transformer);
    }

    /**
     * Test twig getFilters
     */
    public function testGetFilters()
    {
        $this->assertEquals(count($this->extension->getFilters()), 1);
    }

    /**
     * Test twig getFunctions
     */
    public function testGetFunctions()
    {
        $this->assertEquals(count($this->extension->getFunctions()), 1);
    }

    /**
     * Test twig get filters
     */
    public function testTransform()
    {
        $transformedUrl = $this->extension->transform('logo.png', 'width_50');
        $builtUrl = $this->factory->url('logo.png')->resize(50, 0);

        $this->assertEquals($builtUrl, $transformedUrl);
    }
}
