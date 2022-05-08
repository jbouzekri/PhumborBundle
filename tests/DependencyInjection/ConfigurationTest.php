<?php

namespace Jb\Bundle\PhumborBundle\Tests\DependencyInjection;

use Jb\Bundle\PhumborBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

/**
 * Test configuration
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class ConfigurationTest extends TestCase
{
    /**
     * Test the default configuration
     */
    public function testDefaultConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array());

        $this->assertEquals(
            self::getBundleDefaultConfig(),
            $config
        );
    }

    /**
     * Test the server configuration
     */
    public function testServerConfiguration()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array(
            array(
                'server' => array(
                    'url' => 'http://jb.phumbor.fr:8888',
                    'secret' => '123456789'
                )
            )
        ));

        $this->assertEquals($config['server']['url'], 'http://jb.phumbor.fr:8888');
        $this->assertEquals($config['server']['secret'], '123456789');
    }

    public function testTransformationMerging()
    {
        // When a transformation is re-defined (overridden) in a later config file,
        // the newer definition entirely replaces the older one.

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array(
            // Values from first config file
            array(
                'transformations' => array(
                    'test_not_overridden' => array(
                        'resize' => array('width' => 100, 'height' => 100),
                    ),
                    'test_overridden' => array(
                        'filters' => array(array('name' => 'quality', 'arguments' => array(60))),
                        'resize' => array('width' => 100, 'height' => 100),
                    ),
                )
            ),
            // Values from second config file, should win
            array(
                'transformations' => array(
                    'test_overridden' => array(
                        'resize' => array('width' => 200, 'height' => 200),
                    ),
                )
            ),
        ));

        $this->assertEquals(array(
            'test_overridden' => array('resize' => array('width'=>200, 'height'=>200)),
            'test_not_overridden' => array('resize' => array('width'=>100, 'height'=>100)),
        ), $config['transformations']);
    }

    /**
     * @dataProvider getTransformationData
     */
    public function testTransformationConfiguration($transformationConfig, $processedTransformation)
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array(
            array(
                'transformations' => array(
                    'key' => $transformationConfig
                )
            )
        ));

        $this->assertEquals($config['transformations']['key'], $processedTransformation);
    }

    public function getTransformationData()
    {
        return array(
            array(array('fit_in'=>array('width'=>10,'height'=>20)), array('fit_in'=>array('width'=>10,'height'=>20))),
            array(
                array('full_fit_in'=>array('width'=>10,'height'=>20)),
                array('full_fit_in'=>array('width'=>10,'height'=>20))
            ),
            array(array('trim'=>true), array('trim'=>true)),
            array(array('trim'=>'string'), array('trim'=>'string')),
            array(
                array('crop'=>array('top_left_x'=>10,'top_left_y'=>10,'bottom_right_x'=>10,'bottom_right_y'=>10)),
                array('crop'=>array('top_left_x'=>10,'top_left_y'=>10,'bottom_right_x'=>10,'bottom_right_y'=>10))
            ),
            array(
                array('resize'=>array('width'=>'orig','height'=>'orig')),
                array('resize'=>array('width'=>'orig','height'=>'orig'))
            ),
            array(
                array('resize'=>array('width'=>10,'height'=>10)),
                array('resize'=>array('width'=>10,'height'=>10))
            ),
            array(array('halign'=>'left'), array('halign'=>'left')),
            array(array('halign'=>'center'), array('halign'=>'center')),
            array(array('halign'=>'right'), array('halign'=>'right')),
            array(array('valign'=>'top'), array('valign'=>'top')),
            array(array('valign'=>'middle'), array('valign'=>'middle')),
            array(array('valign'=>'bottom'), array('valign'=>'bottom')),
            array(array('smart_crop'=>true), array('smart_crop'=>true)),
            array(array('metadata_only'=>true), array('metadata_only'=>true)),
            array(
                array('filters'=>array( array('name'=>'brightness', 'arguments'=>array('82')) )),
                array('filters'=>array( array('name'=>'brightness', 'arguments'=>array('82')) )),
            ),
        );
    }

    /**
     * @dataProvider getInvalidTypeData
     */
    public function testInvalidType($transformationData)
    {
        self::expectException(InvalidConfigurationException::class);

        $processor = new Processor();
        $configuration = new Configuration();
        $processor->processConfiguration($configuration, array(
            array(
                'transformations' => array(
                    'key' => $transformationData
                )
            )
        ));
    }

    public function getInvalidTypeData($transformationData)
    {
        return array(
            array( array('resize'=>array('width'=>'toto','height'=>10)) ),
            array( array('resize'=>array('width'=>10,'height'=>'toto')) ),
            array( array('resize'=>array('width'=>null,'height'=>'toto')) ),
            array( array('resize'=>array('width'=>10,'height'=>null)) ),
            array( array('valign'=>10) ),
            array( array('valign'=>null) ),
            array( array('halign'=>10) ),
            array( array('halign'=>null) ),
        );
    }

    /**
     * Get bundle default config
     *
     * @return array
     */
    protected static function getBundleDefaultConfig()
    {
        return array(
            'server' => array(
                'url' => '%env(THUMBOR_URL)%',
                'secret' => '%env(THUMBOR_SECURITY_KEY)%'
            ),
            'transformations' => array()
        );
    }
}
