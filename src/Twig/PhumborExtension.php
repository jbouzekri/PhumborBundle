<?php

namespace Jb\Bundle\PhumborBundle\Twig;

use Jb\Bundle\PhumborBundle\Transformer\BaseTransformer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Description of PhumborExtension
 *
 * @author jobou
 */
class PhumborExtension extends AbstractExtension
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
    public function getFilters(): array
    {
        return array(
            new TwigFilter('thumbor', array($this, 'transform')),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('thumbor', array($this, 'transform')),
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
}
