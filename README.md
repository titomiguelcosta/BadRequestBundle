Bad Request Bundle
==================

This bundles adds a BadRequest annotation to be used in a controller action to validate the request.

Instalation
-----------

* In composer.json add the package to the require section:

"tmc/bad-request-bundle": "dev-master"

* Enable the bundle in AppKernel.php

```php
public function registerBundles()
{
    $bundles = array(
        ...,
        new Tmc\BadRequestBundle\TmcBadRequestBundle(),
    );
}
```

Description
-----------

The annotation accepts one mandatory argument, the full class name that extends the form AbstractType and has the definitions of the fields to validate against the Request.

The value returned by getData from the Type is automatically available as an argument in the Action as long the types hints match.

In the following example, the options in ModelType have a data_class that points to Model. The name of the variable, in this case $model, can be anything allowed by PHP.

Example
-------

```php
use Tmc\BadRequestBundle\Annotation\BadRequest;

class DefaultController extends Controller
{

    /**
     * @BadRequest("Your\Namespace\Type\ModelType")
     * @return Response
     */
    public function indexAction(Model $model)
    {
        return $this->render('TmcDemoBundle:Default:index.html.twig', array('model' => $model));
    }

}
```

Bad Request Types
-----------------

Check the Symfony Form component. The input types are classes that extend AbstractType.

If you want to bind the request to a method different than POST, you must specify the option method in the setDefaultOption method.

Almost for sure you will want to disable the csrf protection, if so, add the option csrf_protection to false.

An example:

```php
namespace Tmc\DemoBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tmc\DemoBundle\Model\Person',
            'csrf_protection' => false,
            'method' => 'GET'
        ));
    }

    public function getName()
    {
        return '';
    }

}
```