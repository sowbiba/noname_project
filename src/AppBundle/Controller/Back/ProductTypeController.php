<?php

namespace AppBundle\Controller\Back;

use AppBundle\Connector\ApiConnector;
use AppBundle\Form\Type\ProductTypeFilterType;
use AppBundle\Form\Type\ProductTypeType;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * ProductTypeController is a controller managing product_types CRUD and listing.
 *
 * @Route("/product-types")
 */
class ProductTypeController extends BackController
{
    /**
     * @Route("/list", name="back_product_types_list")
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
            ProductTypeFilterType::class,
            null,
            ['translation_domain' => 'back_product_types', 'method' => 'GET']
        );
        $filterForm->handleRequest($request);
        $query = array_merge((array) $filterForm->getData(), $query);

        try {
            $data = $this->requestApi(ApiConnector::HTTP_METHOD_GET, '/product-types', ['query' => $query]);
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        // Paginate product_types.
        $product_types = $this->get('knp_paginator')->paginate(
            $this->getPaginateData($data['_embedded']['productTypes'], $data['total'], $limit * ($page - 1)),
            $page,
            $limit
        );

        return $this->render(
            'AppBundle:Back/ProductType:list.html.twig',
            [
                'product_types' => $product_types,
                'filter_form' => $filterForm->createView(),
            ]
        );
    }

    /**
     * @Route("/{productTypeId}/update", name="back_product_type_update", requirements={"productTypeId"="\d+"})
     *
     * @param Request $request
     * @param int     $productTypeId
     *
     * @return Response
     */
    public function updateAction(Request $request, $productTypeId)
    {
        try {
            $user = $this->requestApi(ApiConnector::HTTP_METHOD_GET, "/product-types/$productTypeId");
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        $form = $this->createForm(ProductTypeType::class, $user, ['translation_domain' => 'back_product_types']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();

                $body = [
                    'name' => $formData['name'],
                ];

                $this->requestApi(ApiConnector::HTTP_METHOD_PUT, "/product-types/$productTypeId", ['body' => $body]);

                $this->addFlash('success', $this->translate('message.success.update', [], 'back_product_types'));

                $url = $this->generateUrl('back_product_types_list');

                return $this->redirect($url);
            } catch (RequestException $e) {
                $this->handleFormErrors($e, $form, 'back_product_types');
            }
        }

        // Renders the user update view.
        return $this->render(
            'AppBundle:Back/ProductType:update.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * @Route("/create", name="back_product_type_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(ProductTypeType::class, null, ['translation_domain' => 'back_product_types']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();

                $body = [
                    'name' => $formData['name'],
                ];

                $this->requestApi(ApiConnector::HTTP_METHOD_POST, "/product-types", ['body' => $body]);

                $this->addFlash('success', $this->translate('message.success.create', [], 'back_product_types'));

                $url = $this->generateUrl('back_product_types_list');

                return $this->redirect($url);
            } catch (RequestException $e) {
                $this->handleFormErrors($e, $form, 'back_product_types');
            }
        }

        // Renders the user update view.
        return $this->render(
            'AppBundle:Back/ProductType:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{productTypeId}/delete", name="back_product_type_delete", requirements={"productTypeId"="\d+"})
     *
     * @param Request $request
     * @param int     $productTypeId
     *
     * @return Response
     */
    public function deleteAction(Request $request, $productTypeId)
    {
        try {

            $this->requestApi(ApiConnector::HTTP_METHOD_DELETE, "/product-types/$productTypeId");

            $this->addFlash('success', $this->translate('message.success.delete', [], 'back_product_types'));
        } catch (RequestException $e) {
            $this->addFlash('error', $this->translate('message.error.delete', [], 'back_product_types'));
            $this->createApiRequestException($e);
        }


        $url = $this->generateUrl('back_product_types_list');
        return $this->redirect($url);
    }
}