<?php

namespace Jb\Bundle\PhumborBundle\Twig;

use Jb\Bundle\PhumborBundle\Transformer\BaseTransformer;

/**
 * Description of PhumborExtension
 *
 * @author jobou
 */
class PhumborExtension extends \Twig_Extension
{
    /**
     * @var \Jb\Bundle\PhumborBundle\Transformer\BaseTransformer
     */
    protected $transformer;

    /**
     * Constructor
     *
     * @param \Jb\Bundle\PhumborBundle\Transformer\BaseTransformer $transformer
     */
    public function __construct(BaseTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('thumbor', array($this, 'transform')),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('thumbor', array($this, 'transform')),
        );
    }

    /**
     * Twig thumbor filter
     *
     * @param string $orig
     * @param string $transformation
     * @param array $overrides
     *
     * @return string
     */
    public function transform($orig, $transformation = null, $overrides = array())
    {
        return $this->transformer->transform($orig, $transformation, $overrides);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'phumbor_extension';
    }
}
