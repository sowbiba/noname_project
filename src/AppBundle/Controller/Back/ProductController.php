<?php

namespace AppBundle\Controller\Back;

use AppBundle\Connector\ApiConnector;
use AppBundle\Form\Type\ProductFilterType;
use AppBundle\Form\Type\ProductType;
use AppBundle\Form\Type\StockType;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * ProductController is a controller managing products CRUD and listing.
 *
 * @Route("/products")
 */
class ProductController extends BackController
{
    /**
     * @Route("/list", name="back_products_list")
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
            ProductFilterType::class,
            null,
            ['translation_domain' => 'back_products', 'method' => 'GET']
        );
        $filterForm->handleRequest($request);
        $query = array_filter(array_merge((array) $filterForm->getData(), $query));

        try {
            $data = $this->requestApi(ApiConnector::HTTP_METHOD_GET, '/products', ['query' => $query]);
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        // Paginate products.
        $products = $this->get('knp_paginator')->paginate(
            $this->getPaginateData($data['_embedded']['products'], $data['total'], $limit * ($page - 1)),
            $page,
            $limit
        );

        return $this->render(
            'AppBundle:Back/Product:list.html.twig',
            [
                'products' => $products,
                'filter_form' => $filterForm->createView(),
            ]
        );
    }

    /**
     * @Route("/{productId}/update", name="back_product_update", requirements={"productId"="\d+"})
     *
     * @param Request $request
     * @param int     $productId
     *
     * @return Response
     */
    public function updateAction(Request $request, $productId)
    {
        try {
            $product = $this->requestApi(ApiConnector::HTTP_METHOD_GET, "/products/$productId");
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        $form = $this->createForm(ProductType::class, $product, ['translation_domain' => 'back_products']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();

                if ($formData['photoFile'] instanceof UploadedFile) {
                    $photoFileName = $this->get('app.file_uploader.products')->upload($formData['photoFile']);
                } else {
                    $photoFileName = $formData['photo_file'];
                }

                $body = [
                    'name'          => $formData['name'],
                    'description'   => $formData['description'],
                    'price'         => $formData['price'],
                    'photoFile'     => $photoFileName,
                    'productType'   => $formData['productType'],
                ];

                $this->requestApi(ApiConnector::HTTP_METHOD_PUT, "/products/$productId", ['body' => $body]);

                $this->addFlash('success', $this->translate('message.success.update', [], 'back_products'));

                $url = $this->generateUrl('back_products_list');

                return $this->redirect($url);
            } catch (RequestException $e) {
                $this->handleFormErrors($e, $form, 'back_products');
            }
        }

        // Renders the user update view.
        return $this->render(
            'AppBundle:Back/Product:update.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }

    /**
     * @Route("/create", name="back_product_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(ProductType::class, null, ['translation_domain' => 'back_products']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();

                $photoFileName = $this->get('app.file_uploader.products')->upload($formData['photo_file']);

                $body = [
                    'name'          => $formData['name'],
                    'description'   => $formData['description'],
                    'price'         => $formData['price'],
                    'photoFile'     => $photoFileName,
                    'productType'   => $formData['productType'],
                ];

                $this->requestApi(ApiConnector::HTTP_METHOD_POST, "/products", ['body' => $body]);

                $this->addFlash('success', $this->translate('message.success.create', [], 'back_products'));

                $url = $this->generateUrl('back_products_list');

                return $this->redirect($url);
            } catch (RequestException $e) {
                $this->handleFormErrors($e, $form, 'back_products');
            }
        }

        // Renders the user update view.
        return $this->render(
            'AppBundle:Back/Product:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/{productId}/stock", name="back_product_stock_manage", requirements={"productId"="\d+"})
     *
     * @param Request $request
     * @param int     $productId
     *
     * @return Response
     */
    public function stockManageAction(Request $request, $productId)
    {
        try {
            $productStock = $this->requestApi(ApiConnector::HTTP_METHOD_GET, "/product/$productId/stock");
        } catch (RequestException $e) {
            throw $this->createApiRequestException($e);
        }

        $form = $this->createForm(StockType::class, $productStock, ['translation_domain' => 'back_stocks']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $formData = $form->getData();

                $body = [
                    'quantity'          => $formData['quantity'],
                ];

                $this->requestApi(ApiConnector::HTTP_METHOD_PUT, "/product/$productId/stock", ['body' => $body]);

                $this->addFlash('success', $this->translate('message.success.update', [], 'back_stocks'));

                $url = $this->generateUrl('back_products_list');

                return $this->redirect($url);
            } catch (RequestException $e) {
                $this->handleFormErrors($e, $form, 'back_products');
            }
        }

        // Renders the user update view.
        return $this->render(
            'AppBundle:Back/Product:stock.html.twig',
            [
                'form' => $form->createView(),
                'stock' => $productStock,
            ]
        );
    }

    /**
     * @Route("/{productId}/delete", name="back_product_delete", requirements={"productId"="\d+"})
     *
     * @param Request $request
     * @param int     $productId
     *
     * @return Response
     */
    public function deleteAction(Request $request, $productId)
    {
        try {

            $this->requestApi(ApiConnector::HTTP_METHOD_DELETE, "/products/$productId");

            $this->addFlash('success', $this->translate('message.success.delete', [], 'back_products'));
        } catch (RequestException $e) {
            $this->addFlash('error', $this->translate('message.error.delete', [], 'back_products'));
            $this->createApiRequestException($e);
        }

        $url = $this->generateUrl('back_products_list');
        return $this->redirect($url);
    }
}