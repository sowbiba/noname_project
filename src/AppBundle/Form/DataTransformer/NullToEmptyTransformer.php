<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * @see https://github.com/symfony/symfony/issues/5906
 */
class NullToEmptyTransformer implements DataTransformerInterface
{
    /**
     * Does not transform anything.
     *
     * @param string|null $value
     *
     * @return string
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * Transforms a null to an empty string.
     *
     * @param string $value
     *
     * @return string
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return '';
        }

        return $value;
    }
}
