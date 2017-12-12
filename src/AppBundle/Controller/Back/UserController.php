<?php

namespace AppBundle\Controller\Back;

use AppBundle\Connector\ApiConnector;
use AppBundle\Form\Type\UserType;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * UserController is a controller managing users CRUD and listing.
 *
 * @Route("/users")
 */
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
//            'orderBy' => 'id desc',
            'limit' => $limit,
            'page' => $page,
        ];
        try {
            $data = $this->requestApi(ApiConnector::HTTP_METHOD_GET, '/users', ['query' => $query]);
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        // Paginate users.
        $users = $this->get('knp_paginator')->paginate(
            $this->getPaginateData($data['_embedded']['users'], $data['total'], $limit * ($page - 1)),
            $page,
            $limit
        );

        return $this->render(
            'AppBundle:Back/User:list.html.twig',
            [
                'users' => $users,
            ]
        );
    }



    /**
     * @Route("/{userId}/update", name="back_user_update", requirements={"userId"="\d+"})
     *
     * @param Request $request
     * @param int     $userId
     *
     * @return Response
     */
    public function updateAction(Request $request, $userId)
    {
        try {
            $user = $this->requestApi(ApiConnector::HTTP_METHOD_GET, "/users/$userId");
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        $form = $this->createForm(UserType::class, $user, ['translation_domain' => 'back_users']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();

                $body = [
                    'active' => $formData['active'],
                    'address' => $formData['address'],
                    'email' => $formData['email'],
                    'firstname' => $formData['firstname'],
                    'lastname' => $formData['lastname'],
                    'phone' => $formData['phone'],
                    'role' => $formData['role'],
                    'birthdate' => $formData['birthdate'],
                    'username' => $formData['username'],
                ];

                if (null !== $formData['password'] && '' !== $formData['password']) {
                    $body = array_merge($body, ['password' => $formData['password']]);
                }

                $this->requestApi(ApiConnector::HTTP_METHOD_PUT, "/users/$userId", ['body' => $body]);

                $this->addFlash('success', $this->translate('message.success.update', [], 'back_users'));

                $url = $this->generateUrl('back_users_list');

                return $this->redirect($url);
            } catch (RequestException $e) {
                $this->handleFormErrors($e, $form, 'back_users');
            }
        }

        // Renders the user update view.
        return $this->render(
            'AppBundle:Back/User:update.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}