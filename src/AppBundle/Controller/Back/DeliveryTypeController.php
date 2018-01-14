<?php

namespace AppBundle\Controller\Back;

use AppBundle\Connector\ApiConnector;
use AppBundle\Form\Type\DeliveryTypeFilterType;
use AppBundle\Form\Type\DeliveryTypeType;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * DeliveryTypeController is a controller managing delivery_types CRUD and listing.
 *
 * @Route("/delivery-types")
 */
class DeliveryTypeController extends BackController
{
    /**
     * @Route("/list", name="back_delivery_types_list")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $page = $request->query->get('page', 1);
        $limit = 5;
        $query = [
//            'orderBy' => 'id desc',
            'limit' => $limit,
            'page' => $page,
        ];

        $filterForm = $this->createForm(
            DeliveryTypeFilterType::class,
            null,
            ['translation_domain' => 'back_delivery_types', 'method' => 'GET']
        );
        $filterForm->handleRequest($request);
        $query = array_merge((array) $filterForm->getData(), $query);

        try {
            $data = $this->requestApi(ApiConnector::HTTP_METHOD_GET, '/delivery-types', ['query' => $query]);
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        // Paginate delivery_types.
        $delivery_types = $this->get('knp_paginator')->paginate(
            $this->getPaginateData($data['_embedded']['deliveryTypes'], $data['total'], $limit * ($page - 1)),
            $page,
            $limit
        );

        return $this->render(
            'AppBundle:Back/DeliveryType:list.html.twig',
            [
                'delivery_types' => $delivery_types,
                'filter_form' => $filterForm->createView(),
            ]
        );
    }

    /**
     * @Route("/{deliveryTypeId}/update", name="back_delivery_type_update", requirements={"deliveryTypeId"="\d+"})
     *
     * @param Request $request
     * @param int     $deliveryTypeId
     *
     * @return Response
     */
    public function updateAction(Request $request, $deliveryTypeId)
    {
        try {
            $user = $this->requestApi(ApiConnector::HTTP_METHOD_GET, "/delivery-types/$deliveryTypeId");
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        $form = $this->createForm(DeliveryTypeType::class, $user, ['translation_domain' => 'back_delivery_types']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();

                $body = [
                    'name' => $formData['name'],
                    'delay' => $formData['delay'],
                    'price' => $formData['price'],
                ];

                $this->requestApi(ApiConnector::HTTP_METHOD_PUT, "/delivery-types/$deliveryTypeId", ['body' => $body]);

                $this->addFlash('success', $this->translate('message.success.update', [], 'back_delivery_types'));

                $url = $this->generateUrl('back_delivery_types_list');

                return $this->redirect($url);
            } catch (RequestException $e) {
                $this->handleFormErrors($e, $form, 'back_delivery_types');
            }
        }

        // Renders the user update view.
        return $this->render(
            'AppBundle:Back/DeliveryType:update.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/create", name="back_delivery_type_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(DeliveryTypeType::class, null, ['translation_domain' => 'back_delivery_types']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();

                $body = [
                    'name' => $formData['name'],
                    'delay' => $formData['delay'],
                    'price' => $formData['price'],
                ];

                $this->requestApi(ApiConnector::HTTP_METHOD_POST, "/delivery-types", ['body' => $body]);

                $this->addFlash('success', $this->translate('message.success.create', [], 'back_delivery_types'));

                $url = $this->generateUrl('back_delivery_types_list');

                return $this->redirect($url);
            } catch (RequestException $e) {
                $this->handleFormErrors($e, $form, 'back_delivery_types');
            }
        }

        // Renders the user update view.
        return $this->render(
            'AppBundle:Back/DeliveryType:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{deliveryTypeId}/delete", name="back_delivery_type_delete", requirements={"deliveryTypeId"="\d+"})
     *
     * @param Request $request
     * @param int     $deliveryTypeId
     *
     * @return Response
     */
    public function deleteAction(Request $request, $deliveryTypeId)
    {
        try {

            $this->requestApi(ApiConnector::HTTP_METHOD_DELETE, "/delivery-types/$deliveryTypeId");

            $this->addFlash('success', $this->translate('message.success.delete', [], 'back_delivery_types'));
        } catch (RequestException $e) {
            $this->addFlash('error', $this->translate('message.error.delete', [], 'back_delivery_types'));
            $this->createApiRequestException($e);
        }


        $url = $this->generateUrl('back_delivery_types_list');
        return $this->redirect($url);
    }
}