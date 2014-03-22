Bad Request Bundle
==================

This bundles adds a BadRequest annotation to be used in a controller action to validate the request.

The annotation accepts one mandatory argument, the full class name that extends the form AbstractType and has the definitions of the fields to validate against the Request.

The value returned by getData from the Type is automatically available as an argument in the Action as long the types hints match.

In the following example, the options in ModelType have a data_class that points to Model. The name of the variable, in this case $model, can be anything allowed by PHP.

Example
-------
use Tmc\BadRequestBundle\Annotation\BadRequest;

class DefaultController extends Controller
{

    /**
     * @BadRequest("Your\OwnBundle\Type\ModelType")
     * @return Response
     */
    public function indexAction(Model $model)
    {
        return $this->render('TmcBadRequestBundle:Default:index.html.twig', array('model' => $model));
    }

}

Dependencies
------------

Check the Symfony Form component. 

If you want to bind the request to the type in a request method different than POST, you must specify the option method in the setDefaultOption method.