<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Command;
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
 * CommandController is a RESTful controller managing commands CRUD and listing.
 *
 * @Rest\NamePrefix("command_")
 */
class CommandController extends ApiController
{
    /**
     * Gets the list of commands.
     *
     * <hr>
     *
     * After getting the initial list, use the <strong>first, last, next, prev</strong> link relations in the
     * <strong>_links</strong> property to get more commands in the list. Note that <strong>next</strong> will not be
     * available at the end of the list and <strong>prev</strong> will not be available at the start of the list. If
     * the results are exactly one page neither <strong>prev</strong> nor <strong>next</strong> will be available.
     *
     * The <strong>_embedded</strong> embedded command resources key'ed by relation name.
     *
     * <hr>
     *
     * The filters allows you to use the percent sign and underscore wildcards (e.g. name, %name, name%, %name%,
     * na_e, n%e).
     *
     * @ApiDoc(
     *     section="Command",
     *     description="List commands",
     *     statusCodes={
     *         200="OK",
     *         400="Bad request",
     *         403="Forbidden",
     *     },
     *     filters={
     *         {
     *             "name"="id",
     *             "dataType"="integer",
     *             "description"="ID for the command. Up to 11 characters.",
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
    names are id and name. Valid orders are asc and desc. e.g. If you want the commands ordered by name in descending
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
     * @Rest\Get("/commands")
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
            $fields = array_merge(explode(',', $fields), ['commands']);
        }

        $commands = $this->get('app.manager.command')->findAll();

        $context = new Context();
        $context->setGroups(['Default', 'commands_list'])
            ->addExclusionStrategy(
                new FieldsListExclusionStrategy('AppBundle\Entity\Command', $fields)
            );

        return $this
            ->view($commands, Response::HTTP_OK)
            ->setContext($context);
    }

    /**
     * ->add('user',           'AppBundle\Form\SelectorType\UserSelectorType')
    ->add('total',          'Symfony\Component\Form\Extension\Core\Type\IntegerType')
    ->add('deliveryType',   'AppBundle\Form\SelectorType\DeliveryTypeSelectorType')
    ->add('deliveryStatus', 'Symfony\Component\Form\Extension\Core\Type\IntegerType')
    ->add('factureFile',    'Symfony\Component\Form\Extension\Core\Type\TextType')
    ->add('deliveredAt',    'Symfony\Component\Form\Extension\Core\Type\DateType',
    [
    'format' => 'dd/MM/yyyy',
    'widget' => 'single_text',
    ]
    )
    ->add('commandDetails', 'Symfony\Component\Form\Extension\Core\Type\IntegerType')
     */
    /**
     * @ApiDoc(
     *     section="Command",
     *     description="Create new command",
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
     *             "description"="The command user ID. Up to 11 digits.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="deliveryType",
     *             "dataType"="integer",
     *             "description"="The command delivery type ID. Up to 11 digits.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="deliveryStatus",
     *             "dataType"="integer",
     *             "description"="The command delivery status. Choose between ...",
     *             "required"=false,
     *         },
     *         {
     *             "name"="total",
     *             "dataType"="integer",
     *             "description"="The command total cost. Up to 11 digits.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="factureFile",
     *             "dataType"="string",
     *             "description"="Facture file path of the command. Up to 255 characters.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="deliveredAt",
     *             "dataType"="string",
     *             "description"="Command delivery date. Format dd/mm/yy.",
     *             "required"=false,
     *         },
     *     },
     * )
     *
     * @Rest\Post("/commands")
     *
     * @param Request $request
     *
     * @Security("is_granted('create')")
     *
     * @return View
     */
    public function createAction(Request $request)
    {
        $command = $this->get('app.manager.command')->createAndSave($request->request->all());

        $context = new Context();
        $context->setGroups(['Default', 'commands_create']);

        return $this
            ->view($command, Response::HTTP_CREATED)
            ->setContext($context);
    }


    /**
     * @ApiDoc(
     *     section="Command",
     *     description="Retrieve a command",
     *     statusCodes={
     *         200="OK",
     *         403="Forbidden",
     *         404="Not found",
     *     },
     *     requirements={
     *         {
     *             "name"="command_id",
     *             "dataType"="integer",
     *             "description"="the command id",
     *         }
     *     },
     * )
     *
     * @Rest\Get("/commands/{command_id}")
     * @ParamConverter("command", class="AppBundle:Command", options={"id"="command_id"})
     *
     * @param Command $command
     *
     * @return View
     *
     * @Security("is_granted('view')")
     *
     * @throws EntityNotFoundException
     */
    public function readAction(Command $command = null)
    {
        if (null === $command) {
            throw new EntityNotFoundException();
        }

        $context = new Context();
        $context->setGroups(['Default', 'commands_read']);

        return $this
            ->view($command, Response::HTTP_OK)
            ->setContext($context);
    }

    /**
     * @ApiDoc(
     *     section="Command",
     *     description="Update a command",
     *     statusCodes={
     *         200="OK",
     *         403="Forbidden",
     *         404="Not found",
     *         422="Validation failed",
     *     },
     *     requirements={
     *         {
     *             "name"="command_id",
     *             "dataType"="integer",
     *             "description"="the command id",
     *             "required"=true,
     *         }
     *     },
     *     parameters={
     *         {
     *             "name"="name",
     *             "dataType"="string",
     *             "description"="Name for the group. Up to 255 characters.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="description",
     *             "dataType"="text",
     *             "description"="The command description.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="price",
     *             "dataType"="integer",
     *             "description"="The command cost. Up to 11 digits.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="photoFile",
     *             "dataType"="string",
     *             "description"="Photo file path of the command. Up to 255 characters.",
     *             "required"=false,
     *         },
     *         {
     *             "name"="commandType",
     *             "dataType"="integer",
     *             "description"="Command's type id. Up to 11 digits.",
     *             "required"=false,
     *         },
     *     },
     * )
     *
     * @Rest\Put("/commands/{command_id}")
     * @ParamConverter("command", class="AppBundle:Command", options={"id"="command_id"})
     *
     * @param Request $request
     * @param Command   $command
     *
     * @Security("is_granted('edit')")
     *
     * @return View
     *
     * @throws EntityNotFoundException
     */
    public function updateAction(Request $request, Command $command = null)
    {
        if (null === $command) {
            throw new EntityNotFoundException();
        }

        $command = $this->get('app.manager.command')->updateAndSave($command, $request->request->all());

        $context = new Context();
        $context->setGroups(['Default', 'commands_update']);

        return $this
            ->view($command, Response::HTTP_OK)
            ->setContext($context);
    }

    /**
     * @ApiDoc(
     *     section="Command",
     *     description="Delete a command",
     *     statusCodes={
     *         204="OK",
     *         403="Forbidden",
     *         404="Not found",
     *     },
     *     requirements={
     *         {
     *             "name"="command_id",
     *             "dataType"="integer",
     *             "description"="the command id",
     *         }
     *     },
     * )
     *
     * @Rest\Delete("/commands/{command_id}")
     * @ParamConverter("command", class="AppBundle:Command", options={"id"="command_id"})
     *
     * @param Command $command
     *
     * @return View
     *
     * @Security("is_granted('delete')")
     *
     * @throws EntityNotFoundException
     */
    public function deleteAction(Command $command = null)
    {
        if (null === $command) {
            throw new EntityNotFoundException();
        }

        $this->get('app.manager.command')->delete($command);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
