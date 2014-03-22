<?php

namespace Tmc\BadRequestBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadRequestException extends BadRequestHttpException
{

    protected $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;

        parent::__construct('Invalid request.');
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
