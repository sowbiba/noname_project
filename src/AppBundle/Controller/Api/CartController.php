<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Serializer\Exclusion\FieldsListExclusionStrategy;
use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * CartController is a RESTful controller managing carts CRUD and listing.
 *
 * @Rest\NamePrefix("cart_")
 */
class CartController extends ApiController
{
    /**
     * Gets the list of carts.
     *
     * <hr>
     *
     * After getting the initial list, use the <strong>first, last, next, prev</strong> link relations in the
     * <strong>_links</strong> property to get more carts in the list. Note that <strong>next</strong> will not be
     * available at the end of the list and <strong>prev</strong> will not be available at the start of the list. If
     * the results are exactly one page neither <strong>prev</strong> nor <strong>next</strong> will be available.
     *
     * The <strong>_embedded</strong> embedded cart resources key'ed by relation name.
     *
     * <hr>
     *
     * The filters allows you to use the percent sign and underscore wildcards (e.g. name, %name, name%, %name%,
     * na_e, n%e).
     *
     * @ApiDoc(
     *     section="Cart",
     *     description="List carts",
     *     statusCodes={
     *         200="OK",
     *         400="Bad request",
     *         403="Forbidden",
     *     },
     *     filters={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "description"="ID for the cart. Up to 11 characters.",
     *             "required"=false,
     *         },
     *     },
     *     parameters={
     *         {
     *             "name"="fields",
     *             "dataType"="string",
     *             "description"="
    Specify the fields that will be returned using the format FIELD_NAME[, FIELD_NAME ...]. Valid fields are id and
    name. e.g. If you want the result with the name field only, the fields string would be name.
    Default is: all the fields.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="orderBy",
     *             "dataType"="string",
     *             "description"="
    Specify the order criteria of the result using the format COLUMN_NAME ORDER[, COLUMN_NAME ORDER ...]. Valid column
    names are id and name. Valid orders are asc and desc. e.g. If you want the carts ordered by name in descending
    order and then order by id in ascending order, the order string would be name=desc, id=asc.
    Default is: id asc.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="page",
     *             "dataType"="integer",
     *             "description"="
    Current page to returned.
    Default is: 1.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="limit",
     *             "dataType"="integer",
     *             "description"="
    Maximum number of items requested (-1 for no limit).
    Default is: 20.",
     *             "required"=false,
     *         },
     *     },
     * )
     *
     * @Rest\Get("/carts")
     *
     * @param Request    $request
     *
     * @Security("is_granted('view')")
     *
     * @return View
     */
    public function listAction(Request $request)
    {
        if ('' !== $fields = $request->query->get('fields', '')) {
            $fields = array_merge(explode(',', $fields), ['carts']);
        }

        $carts = $this->get('app.manager.cart')->findAll();

        $context = new Context();
        $context->setGroups(['Default', 'carts_list'])
            ->addExclusionStrategy(
                new FieldsListExclusionStrategy('AppBundle\Entity\Cart', $fields)
            );

        return $this
            ->view($carts, Response::HTTP_OK)
            ->setContext($context);
    }

    /**
     * @ApiDoc(
     *     section="Cart",
     *     description="Create new cart",
     *     statusCodes={
     *         201="Created",
     *         400="Bad request",
     *         403="Forbidden",
     *         422="Validation failed",
     *     },
     *     parameters={
     *         {
     *             "name"="user",
     *             "dataType"="integer",
     *             "description"="The cart user ID. Up to 11 digits.",
     *             "required"=false,
     *         },
     *     },
     * )
     *
     * @Rest\Post("/carts")
     *
     * @param Request $request
     *
     * @Security("is_granted('create')")
     *
     * @return View
     */
    public function createAction(Request $request)
    {
        $cart = $this->get('app.manager.cart')->createAndSave($request->request->all());

        $context = new Context();
        $context->setGroups(['Default', 'carts_create']);

        return $this
            ->view($cart, Response::HTTP_CREATED)
            ->setContext($context);
    }


    /**
     * @ApiDoc(
     *     section="Cart",
     *     description="Retrieve a cart",
     *     statusCodes={
     *         200="OK",
     *         403="Forbidden",
     *         404="Not found",
     *     },
     *     requirements={
     *         {
     *             "name"="cart_id",
     *             "dataType"="integer",
     *             "description"="the cart id",
     *         }
     *     },
     * )
     *
     * @Rest\Get("/carts/{cart_id}")
     * @ParamConverter("cart", class="AppBundle:Cart", options={"id"="cart_id"})
     *
     * @param Cart $cart
     *
     * @return View
     *
     * @Security("is_granted('view')")
     *
     * @throws EntityNotFoundException
     */
    public function readAction(Cart $cart = null)
    {
        if (null === $cart) {
            throw new EntityNotFoundException();
        }

        $context = new Context();
        $context->setGroups(['Default', 'carts_read']);

        return $this
            ->view($cart, Response::HTTP_OK)
            ->setContext($context);
    }

    /**
     * @ApiDoc(
     *     section="Cart",
     *     description="Update a cart",
     *     statusCodes={
     *         200="OK",
     *         403="Forbidden",
     *         404="Not found",
     *         422="Validation failed",
     *     },
     *     requirements={
     *         {
     *             "name"="cart_id",
     *             "dataType"="integer",
     *             "description"="the cart id",
     *             "required"=true,
     *         }
     *     },
     *     parameters={
     *         {
     *             "name"="user",
     *             "dataType"="integer",
     *             "description"="The cart user ID. Up to 11 digits.",
     *             "required"=false,
     *         },
     *     },
     * )
     *
     * @Rest\Put("/carts/{cart_id}")
     * @ParamConverter("cart", class="AppBundle:Cart", options={"id"="cart_id"})
     *
     * @param Request $request
     * @param Cart   $cart
     *
     * @Security("is_granted('edit')")
     *
     * @return View
     *
     * @throws EntityNotFoundException
     */
    public function updateAction(Request $request, Cart $cart = null)
    {
        if (null === $cart) {
            throw new EntityNotFoundException();
        }

        $cart = $this->get('app.manager.cart')->updateAndSave($cart, $request->request->all());

        $context = new Context();
        $context->setGroups(['Default', 'carts_update']);

        return $this
            ->view($cart, Response::HTTP_OK)
            ->setContext($context);
    }

    /**
     * @ApiDoc(
     *     section="Cart",
     *     description="Add a product to cart",
     *     statusCodes={
     *         200="OK",
     *         403="Forbidden",
     *         404="Not found",
     *         422="Validation failed",
     *     },
     *     requirements={
     *         {
     *             "name"="cart_id",
     *             "dataType"="integer",
     *             "description"="the cart id",
     *             "required"=true,
     *         }
     *     },
     *     parameters={
     *         {
     *             "name"="product",
     *             "dataType"="integer",
     *             "description"="the product id",
     *             "required"=true,
     *         },
     *         {
     *             "name"="quantity",
     *             "dataType"="integer",
     *             "description"="Quantity of products to add to cart. Up to 11 digits.",
     *             "required"=false,
     *         },
     *     },
     * )
     *
     * @Rest\Put("/carts/{cart_id}/add-product")
     * @ParamConverter("cart", class="AppBundle:Cart", options={"id"="cart_id"})
     *
     * @param Request $request
     * @param Cart   $cart
     *
     * @Security("is_granted('edit')")
     *
     * @return View
     *
     * @throws EntityNotFoundException
     */
    public function addProductToCartAction(Request $request, Cart $cart = null)
    {
        if (null === $cart) {
            throw new EntityNotFoundException();
        }

        $data = $request->request->all();

        var_dump($data['product'])

        $cart = $this->get('app.manager.cart_detail')->createAndSave(
            array_merge(array('cart' => $cart->getId()), $request->request->all())
        );

        $context = new Context();
        $context->setGroups(['Default', 'carts_update']);

        return $this
            ->view($cart, Response::HTTP_OK)
            ->setContext($context);
    }


    /**
     * @ApiDoc(
     *     section="Cart",
     *     description="Update product quantity into cart",
     *     statusCodes={
     *         200="OK",
     *         403="Forbidden",
     *         404="Not found",
     *         422="Validation failed",
     *     },
     *     requirements={
     *         {
     *             "name"="cart_id",
     *             "dataType"="integer",
     *             "description"="the cart id",
     *             "required"=true,
     *         },
     *         {
     *             "name"="product",
     *             "dataType"="integer",
     *             "description"="the product id",
     *             "required"=true,
     *         },
     *     },
     *     parameters={
     *         {
     *             "name"="quantity",
     *             "dataType"="integer",
     *             "description"="Quantity of products to add to cart. Up to 11 digits.",
     *             "required"=false,
     *         },
     *     },
     * )
     *
     * @Rest\Put("/carts/{cart_id}/update-product/{product_id}")
     * @ParamConverter("cart", class="AppBundle:Cart", options={"id"="cart_id"})
     * @ParamConverter("product", class="AppBundle:Product", options={"id"="product_id"})
     *
     * @param Request   $request
     * @param Cart      $cart
     * @param Product   $product
     *
     * @Security("is_granted('edit')")
     *
     * @return View
     *
     * @throws EntityNotFoundException
     */
    public function updateProductIntoCartAction(Request $request, Cart $cart = null, Product $product = null)
    {
        if (null === $cart) {
            throw new EntityNotFoundException();
        }

        if (null === $product) {
            throw new EntityNotFoundException();
        }

        $cartDetailManager = $this->get('app.manager.cart_detail');

        $cartDetail = $cartDetailManager->findOneBy(
            array('cart' => $cart, 'product' => $product)
        );

        if (null === $cartDetail) {
            $cartDetail = $cartDetailManager->createAndSave(
                array('cart' => $cart, 'product' => $product, 'quantity' => 0)
            );
        }

        $cartDetailManager->updateAndSave(
            $cartDetail,
            array_merge(
                array('cart' => $cart, 'product' => $product),
                $request->request->all()
            )
        );

        $context = new Context();
        $context->setGroups(['Default', 'carts_update']);

        return $this
            ->view($cart, Response::HTTP_OK)
            ->setContext($context);
    }

    /**
     * @ApiDoc(
     *     section="Cart",
     *     description="Delete a cart",
     *     statusCodes={
     *         204="OK",
     *         403="Forbidden",
     *         404="Not found",
     *     },
     *     requirements={
     *         {
     *             "name"="cart_id",
     *             "dataType"="integer",
     *             "description"="the cart id",
     *         }
     *     },
     * )
     *
     * @Rest\Delete("/carts/{cart_id}")
     * @ParamConverter("cart", class="AppBundle:Cart", options={"id"="cart_id"})
     *
     * @param Cart $cart
     *
     * @return View
     *
     * @Security("is_granted('delete')")
     *
     * @throws EntityNotFoundException
     */
    public function deleteAction(Cart $cart = null)
    {
        if (null === $cart) {
            throw new EntityNotFoundException();
        }

        $this->get('app.manager.cart')->delete($cart);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
