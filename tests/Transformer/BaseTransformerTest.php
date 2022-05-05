<?php

namespace Jb\Bundle\PhumborBundle\Tests\Transformer;

use Jb\Bundle\PhumborBundle\Transformer\BaseTransformer;
use Jb\Bundle\PhumborBundle\Transformer\Exception\UnknownTransformationException;
use PHPUnit\Framework\TestCase;
use Thumbor\Url\BuilderFactory;

/**
 * Description of BaseTransformerTest
 *
 * @author jobou
 */
class BaseTransformerTest extends TestCase
{
    /**
     * @var \Jb\Bundle\PhumborBundle\Transformer\BaseTransformer
     */
    private $transformer;

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
        $this->transformer = new BaseTransformer(
            $this->factory,
            array(
                'width_50' => array(
                    'resize' => array('width'=>50, 'height'=>0)
                )
            )
        );
    }

    /**
     * Test without transformation
     */
    public function testEmptyTransformation()
    {
        $transformedUrl = $this->transformer->transform('http://phumbor.jb.fr/logo.png', null);
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png');

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test normal transformation
     */
    public function testNormalTransformation()
    {
        $transformedUrl = $this->transformer->transform('http://phumbor.jb.fr/logo.png', 'width_50');
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->resize(50, 0);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    public function testUnknownTransformationException()
    {
        self::expectException(UnknownTransformationException::class);
        $transformedUrl = $this->transformer->transform('http://phumbor.jb.fr/logo.png', 'not_known');
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png');

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test override transformation
     */
    public function testOverrideTransformation()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            'width_50',
            array('resize' => array('width'=> 40, 'height'=>0))
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->resize(40, 0);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test multiple transformation
     */
    public function testMultipleTransformation()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            'width_50',
            array(
                'resize' => array('width'=> 40, 'height'=>0),
                'fit_in' => array('width'=> 40, 'height'=>0)
            )
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->resize(40, 0)->fitIn(40, 0);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test trim without color
     */
    public function testTrimWithoutColor()
    {
        $transformedUrl = $this->transformer->transform('http://phumbor.jb.fr/logo.png', null, array('trim'=>false));
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->trim(false);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test trim with color
     */
    public function testTrimWithColor()
    {
        $transformedUrl = $this->transformer->transform('http://phumbor.jb.fr/logo.png', null, array('trim'=>'color'));
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->trim('color');

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test crop
     */
    public function testCrop()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('crop'=> array('top_left_x'=>10, 'top_left_y'=>10, 'bottom_right_x'=>10, 'bottom_right_y'=>10))
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->crop(10, 10, 10, 10);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test crop
     */
    public function testResize()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('resize'=> array('width'=>10, 'height'=>10))
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->resize(10, 10);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test crop
     */
    public function testFitIn()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('fit_in'=> array('width'=>10, 'height'=>10))
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->fitIn(10, 10);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test fullFitIn
     */
    public function testFullFitIn()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('full_fit_in'=> array('width'=>10, 'height'=>10))
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->fullFitIn(10, 10);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test halign
     */
    public function testHalign()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('halign'=> 'center')
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->halign('center');

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test valign
     */
    public function testValign()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('valign' => 'middle')
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->valign('middle');

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test smartCrop
     */
    public function testSmartCrop()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('smart_crop' => true)
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->smartCrop(true);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test metadataOnly
     */
    public function testMetadataOnly()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('metadata_only' => true)
        );
        $buildedUrl = $this->factory->url('http://phumbor.jb.fr/logo.png')->metadataOnly(true);

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test filters
     */
    public function testFilters()
    {
        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array(
                'filters'=> array(
                    array('name'=>'brightness', 'arguments' => 56),
                    array('name'=>'color', 'arguments' => array('black', 'red')),
                )
            )
        );

        $buildedUrl = $this
            ->factory
            ->url('http://phumbor.jb.fr/logo.png')
            ->addFilter('brightness', 56)
            ->addFilter('color', 'black', 'red')
        ;

        $this->assertEquals($transformedUrl, $buildedUrl);
    }

    /**
     * Test setFactory
     */
    public function testSetFactory()
    {
        $overrideFactory = new BuilderFactory('http://mynewhostname', '123456799');
        $this->transformer->setFactory($overrideFactory);

        $transformedUrl = $this->transformer->transform(
            'http://phumbor.jb.fr/logo.png',
            null,
            array('metadata_only' => true)
        );
        $buildedUrl = $overrideFactory->url('http://phumbor.jb.fr/logo.png')->metadataOnly(true);
        $this->assertEquals($transformedUrl, $buildedUrl);
    }
}
