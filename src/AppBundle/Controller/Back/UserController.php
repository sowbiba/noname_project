<?php
/**
 * Created by PhpStorm.
 * User: isow
 * Date: 27/11/17
 * Time: 18:49
 */

namespace AppBundle\Controller\Back;


use AppBundle\Connector\ApiConnector;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends BackController
{
    /**
     * @Route("/list", name="back_users_list")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $limit = 20;
        $query = [
            'orderBy' => 'position asc',
            'limit' => $limit,
            'page' => $page,
        ];
        try {
            // Gets the Broadcast4 API response body.
            $data = $this->requestApi(ApiConnector::HTTP_METHOD_GET, '/users', ['query' => $query]);
        } catch (RequestException $e) {
            var_dump($e->getMessage());die();
            throw $this->createApiRequestException($e);
        }
        // Paginate contract sets.
        $users = $this->get('knp_paginator')->paginate(
            $this->getPaginateData($data['_embedded']['contract_sets'], $data['total'], $limit * ($page - 1)),
            $page,
            $limit
        );
        // Renders the contract set list view.
        return $this->render(
            'AppBundle:User:list.html.twig',
            [
                'users' => $users,
            ]
        );
    }
}