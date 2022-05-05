<?php

namespace Jb\Bundle\PhumborBundle\Transformer;

use Thumbor\Url\BuilderFactory;
use Thumbor\Url\Builder;

/**
 * Description of BaseTransformer
 *
 * @author jobou
 */
class BaseTransformer
{
    /**
     * Method name for each operation
     *
     * @var array
     */
    protected static $filterMethod = array(
        'trim' => 'trim',
        'crop' => 'crop',
        'fit_in' => 'fitIn',
        'full_fit_in' => 'fullFitIn',
        'resize' => 'resize',
        'halign' => 'halign',
        'valign' => 'valign',
        'smart_crop' => 'smartCrop',
        'metadata_only' => 'metadataOnly',
        'filters' => 'filters'
    );

    /**
     * Phumbor Builder Factory
     *
     * @var BuilderFactory
     */
    protected $factory;

    /**
     * The configured transformations
     *
     * @var array
     */
    protected $transformations;

    public function __construct(BuilderFactory $factory, array $transformations)
    {
        $this->factory = $factory;
        $this->transformations = $transformations;
    }

    /**
     * Transform an url or path for thumbor
     *
     * @param string $orig the original url or path
     * @param string $transformation the name of the transformation to apply to the original image
     * @param array $overrides an array of additionnal filters to override the ones from the transformation
     *
     * @return \Thumbor\Url\Builder
     *
     * @throws \Jb\Bundle\PhumborBundle\Transformer\Exception\UnknownTransformationException
     */
    public function transform($orig, $transformation = null, $overrides = array())
    {
        $url = $this->factory->url($orig);
        if (is_null($transformation) && count($overrides) == 0) {
            return $url;
        }

        // Check if a transformation is given without overrides
        if (!isset($this->transformations[$transformation]) && count($overrides) == 0) {
            throw new Exception\UnknownTransformationException(
                "Unknown transformation $transformation. Use on of "
                . "the following ".implode(', ', array_keys($this->transformations))
            );
        }

        // Override transformation configuration with custom values
        $configuration = array();
        if (isset($this->transformations[$transformation])) {
            $configuration = $this->transformations[$transformation];
        }
        $configuration = array_merge($configuration, $overrides);

        // Build url from transformation configuration
        foreach ($configuration as $filter => $arguments) {
            $method = self::$filterMethod[$filter];
            $this->$method($url, $arguments);
        }

        return $url;
    }

    /**
     * Apply trim filter
     *
     * @param \Thumbor\Url\Builder $url
     * @param bool|string $args
     *
     * @return void
     */
    protected function trim(Builder $url, $args)
    {
        $args = (is_string($args)) ? $args : null;
        $url->trim($args);
    }

    /**
     * Apply resize filter
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function resize(Builder $url, $args)
    {
        $url->resize($args['width'], $args['height']);
    }

    /**
     * Apply crop filter
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function crop(Builder $url, $args)
    {
        $url->crop($args['top_left_x'], $args['top_left_y'], $args['bottom_right_x'], $args['bottom_right_y']);
    }

    /**
     * Apply fitIn filter
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function fitIn(Builder $url, $args)
    {
        $url->fitIn($args['width'], $args['height']);
    }

    /**
     * Apply fullFitIn filter
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function fullFitIn(Builder $url, $args)
    {
        $url->fullFitIn($args['width'], $args['height']);
    }

    /**
     * Apply halign filter
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function halign(Builder $url, $args)
    {
        $url->halign($args);
    }

    /**
     * Apply valign filter
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function valign(Builder $url, $args)
    {
        $url->valign($args);
    }

    /**
     * Apply smartCrop filter
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function smartCrop(Builder $url, $args)
    {
        $url->smartCrop($args);
    }

    /**
     * Request metadata endpoint
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function metadataOnly(Builder $url, $args)
    {
        $url->metadataOnly($args);
    }

    /**
     * Apply filters
     *
     * @param \Thumbor\Url\Builder $url
     * @param array $args
     *
     * @return void
     */
    protected function filters(Builder $url, $args)
    {
        foreach ($args as $arg) {
            $arguments = (is_array($arg['arguments'])) ? $arg['arguments'] : array($arg['arguments']);
            array_unshift($arguments, $arg['name']);
            call_user_func_array(array($url, 'addFilter'), $arguments);
        }
    }

    /**
    * Setter allowing for factory override
    *
    * @param \Thumbor\Url\BuilderFactory $factory
    */
    public function setFactory(BuilderFactory $factory)
    {
        $this->factory = $factory;
    }
}
