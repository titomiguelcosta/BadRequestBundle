<?php

namespace Tmc\BadRequestBundle\Annotation;

use Symfony\Component\Form\AbstractType;
use Tmc\BadRequestBundle\Exception\BadModelException;

/**
 * @Annotation
 */
class BadRequest
{

    const BASE_CLASS = 'Symfony\Component\Form\AbstractType';

    protected $model;

    public function __construct(array $values)
    {
        $this->validate($values);

        $this->model = $values['value'];
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Validation of the input sent to the annotation constructor
     *
     * @param array $values
     * @throws BadModelException
     */
    protected function validate(array $values)
    {
        if (!array_key_exists('value', $values)) {
            throw new BadModelException('This annotation requires the full qualified class name as argument.');
        } else if (!class_exists($values['value'])) {
            throw new BadModelException(sprintf('The class "%s" does not exist or is not loaded.', $values['value']));
        } else if (!is_subclass_of($values['value'], self::BASE_CLASS)) {
            throw new BadModelException(sprintf('The class "%s" does not extend "%s".', $values['value'], self::BASE_CLASS));
        }
    }

}
