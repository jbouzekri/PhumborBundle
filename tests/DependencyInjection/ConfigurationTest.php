<?php

namespace Jb\Bundle\PhumborBundle\Tests\DependencyInjection;

use Jb\Bundle\PhumborBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\Attributes\DataProvider;
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
    public function testDefaultConfig(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), []);

        $this->assertEquals(
            self::getBundleDefaultConfig(),
            $config
        );
    }

    /**
     * Test the server configuration
     */
    public function testServerConfiguration(): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'server' => [
                    'url' => 'http://jb.phumbor.fr:8888',
                    'secret' => '123456789'
                ]
            ]
        ]);

        $this->assertEquals($config['server']['url'], 'http://jb.phumbor.fr:8888');
        $this->assertEquals($config['server']['secret'], '123456789');
    }

    public function testTransformationMerging(): void
    {
        // When a transformation is re-defined (overridden) in a later config file,
        // the newer definition entirely replaces the older one.

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [
            // Values from first config file
            [
                'transformations' => [
                    'test_not_overridden' => [
                        'resize' => ['width' => 100, 'height' => 100],
                    ],
                    'test_overridden' => [
                        'filters' => [['name' => 'quality', 'arguments' => [60]]],
                        'resize' => ['width' => 100, 'height' => 100],
                    ],
                ]
            ],
            // Values from second config file, should win
            [
                'transformations' => [
                    'test_overridden' => [
                        'resize' => ['width' => 200, 'height' => 200],
                    ],
                ]
            ],
        ]);

        $this->assertEquals([
            'test_overridden' => ['resize' => ['width'=>200, 'height'=>200]],
            'test_not_overridden' => ['resize' => ['width'=>100, 'height'=>100]],
        ], $config['transformations']);
    }

    #[DataProvider('getTransformationData')]
    public function testTransformationConfiguration($transformationConfig, $processedTransformation): void
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), [
            [
                'transformations' => [
                    'key' => $transformationConfig
                ]
            ]
        ]);

        $this->assertEquals($config['transformations']['key'], $processedTransformation);
    }

    public static function getTransformationData(): array
    {
        return [
            [['fit_in'=>['width'=>10,'height'=>20]], ['fit_in'=>['width'=>10,'height'=>20]]],
            [
                ['full_fit_in'=>['width'=>10,'height'=>20]],
                ['full_fit_in'=>['width'=>10,'height'=>20]]
            ],
            [['trim'=>true], ['trim'=>true]],
            [['trim'=>'string'], ['trim'=>'string']],
            [
                ['crop'=>['top_left_x'=>10,'top_left_y'=>10,'bottom_right_x'=>10,'bottom_right_y'=>10]],
                ['crop'=>['top_left_x'=>10,'top_left_y'=>10,'bottom_right_x'=>10,'bottom_right_y'=>10]]
            ],
            [
                ['resize'=>['width'=>'orig','height'=>'orig']],
                ['resize'=>['width'=>'orig','height'=>'orig']]
            ],
            [
                ['resize'=>['width'=>10,'height'=>10]],
                ['resize'=>['width'=>10,'height'=>10]]
            ],
            [['halign'=>'left'], ['halign'=>'left']],
            [['halign'=>'center'], ['halign'=>'center']],
            [['halign'=>'right'], ['halign'=>'right']],
            [['valign'=>'top'], ['valign'=>'top']],
            [['valign'=>'middle'], ['valign'=>'middle']],
            [['valign'=>'bottom'], ['valign'=>'bottom']],
            [['smart_crop'=>true], ['smart_crop'=>true]],
            [['metadata_only'=>true], ['metadata_only'=>true]],
            [
                ['filters'=>[ ['name'=>'brightness', 'arguments'=>['82']] ]],
                ['filters'=>[ ['name'=>'brightness', 'arguments'=>['82']] ]],
            ],
        ];
    }

    #[DataProvider('getInvalidTypeData')]
    public function testInvalidType($transformationData): void
    {
        self::expectException(InvalidConfigurationException::class);

        $processor = new Processor();
        $configuration = new Configuration();
        $processor->processConfiguration($configuration, [
            [
                'transformations' => [
                    'key' => $transformationData
                ]
            ]
        ]);
    }

    public static function getInvalidTypeData(): array
    {
        return [
            [ ['resize'=>['width'=>'toto','height'=>10]] ],
            [ ['resize'=>['width'=>10,'height'=>'toto']] ],
            [ ['resize'=>['width'=>null,'height'=>'toto']] ],
            [ ['resize'=>['width'=>10,'height'=>null]] ],
            [ ['valign'=>10] ],
            [ ['valign'=>null] ],
            [ ['halign'=>10] ],
            [ ['halign'=>null] ],
        ];
    }

    /**
     * Get bundle default config
     *
     * @return array
     */
    protected static function getBundleDefaultConfig(): array
    {
        return [
            'server' => [
                'url' => '%env(THUMBOR_URL)%',
                'secret' => '%env(THUMBOR_SECURITY_KEY)%'
            ],
            'transformations' => []
        ];
    }
}
