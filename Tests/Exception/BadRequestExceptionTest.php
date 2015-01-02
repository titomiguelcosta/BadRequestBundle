<?php

namespace Zorbus\BadRequestBundle\Tests\Exception;

use Zorbus\BadRequestBundle\Exception\BadRequestException;

class BadRequestExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testErrorsCanBeRetrieved()
    {
        $errors = [];
        $exception = new BadRequestException($errors);
        
        $this->assertSame($errors, $exception->getErrors());
    } 
}
