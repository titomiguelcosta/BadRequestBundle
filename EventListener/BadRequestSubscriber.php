<?php

namespace Tmc\BadRequestBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Annotations\FileCacheReader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactory;
use Tmc\BadRequestBundle\Annotation\BadRequest as BadRequestAnnotation;
use Tmc\BadRequestBundle\Exception\BadRequestException;
use ReflectionMethod;
use ReflectionFunction;
use ReflectionClass;

class BadRequestSubscriber implements EventSubscriberInterface
{

    protected $annotation;
    protected $form;

    public function __construct(FileCacheReader $annotation, FormFactory $form)
    {
        $this->annotation = $annotation;
        $this->form = $form;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'kernel.controller' => array(
                array('onKernelController', 10)
            )
        );
    }

    /**
     * Modifies the controller action to inject the model
     *
     * @param FilterControllerEvent $event A FilterControllerEvent instance
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $request = $event->getRequest();

        if (is_array($controller)) {
            $reflectionMethod = new ReflectionMethod($controller[0], $controller[1]);
        } else {
            $reflectionMethod = new ReflectionFunction($controller);
        }

        $annotations = $this->annotation->getMethodAnnotations($reflectionMethod);

        foreach ($annotations as $annotation) {
            if ($annotation instanceof BadRequestAnnotation) {
                $inputTypeClass = $annotation->getModel();

                $type = new $inputTypeClass();
                $form = $this->form->create($type, null, array());
                $form->handleRequest($request);
                if (!$form->isValid()) {
                    throw new BadRequestException($form->getErrors());
                }
                $model = $form->getData();

                $parameters = $reflectionMethod->getParameters();
                foreach ($parameters as $parameter) {
                    $class = $parameter->getClass();
                    if ($class instanceof ReflectionClass && $class->name === get_class($model)) {
                        $request->attributes->set($parameter->name, $model);
                    }
                }
            }
        }
    }

}
