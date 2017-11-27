<?php

namespace AppBundle\Controller\Back;

use AppBundle\Exception\ApiRequestException;
use FOS\RestBundle\Controller\FOSRestController;
use GuzzleHttp\Exception\RequestException;

abstract class BackController extends FOSRestController
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
        var_dump('ici');die();
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
}
