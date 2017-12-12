<?php

namespace AppBundle\Controller\Back;

use AppBundle\Exception\ApiRequestException;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;

abstract class BackController extends Controller
{
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->get('security.token_storage')->getToken()->getUser();
    }

    /**
     * Send a request to the API.
     *
     * @param string $httpMethod
     * @param string $url        URL or URI template
     * @param array  $options    Array of request options to apply
     *
     * @return array
     *
     * @throws \InvalidArgumentException when a wrong HTTP method is transmitted
     * @throws RequestException          When an error is encountered
     */
    public function requestApi($httpMethod, $url, array $options = [])
    {
        return $this->get('api_connector.api')->request($httpMethod, $url, $options);
    }


    /**
     * @param RequestException $exception
     *
     * @return ApiRequestException
     */
    public function createApiRequestException(RequestException $exception)
    {
        return new ApiRequestException($exception);
    }

    /**
     * Creates a fake array of {$total} entries, and add {$data} at offset {$offset}.
     * We use this system because of a performance issue during listing that cause the API to only return
     * a limited list and a field providing the total of entries in the database.
     *
     * @param array $data
     * @param $total
     * @param $offset
     *
     * @return array
     */
    public function getPaginateData(array $data, $total, $offset)
    {
        $array = new \SplFixedArray($total);
        $i = $offset;
        foreach ($data as $datum) {
            $array->offsetSet($i, $datum);
            ++$i;
        }
        return $array->toArray();
    }

    /**
     * Interpolates the given message.
     *
     * Parameters are replaced in the message in the same manner that
     * {@link strtr()} uses.
     *
     * Example usage:
     *
     *     $translator = new DefaultTranslator();
     *
     *     echo $translator->trans(
     *         'This is a {{ var }}.',
     *         array('{{ var }}' => 'donkey')
     *     );
     *
     *     // -> This is a donkey.
     *
     * @param string $id         The message id
     * @param array  $parameters An array of parameters for the message
     * @param string $domain     Ignored
     * @param string $locale     Ignored
     *
     * @return string The interpolated string
     */
    protected function translate($id, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param RequestException $exception
     * @param Form             $form
     * @param string           $translateDomain
     */
    protected function handleFormErrors(RequestException $exception, Form $form, $translateDomain)
    {
        $response = $exception->getResponse();

        if (Response::HTTP_UNPROCESSABLE_ENTITY === $response->getStatusCode()) {
            $this->addFlash('form-error', $this->renderFormErrors($response->json(), $translateDomain));
        } else {
            throw $this->createApiRequestException($exception);
        }
    }

    /**
     * @param array  $errors
     * @param string $translateDomain
     *
     * @return string
     *
     * @throws \Exception
     * @throws \Twig_Error
     */
    protected function renderFormErrors(array $errors, $translateDomain)
    {
        return $this->render('AppBundle:Templating:form-errors.html.twig',
            [
                'errors' => $errors,
                'translate_domain' => $translateDomain,
            ]
        );
    }
}
