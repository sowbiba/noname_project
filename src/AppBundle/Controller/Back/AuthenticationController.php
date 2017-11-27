<?php

namespace AppBundle\Controller\Back;

use AppBundle\Entity\User;
use AppBundle\Exception\NotValidPasswordException;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * AuthenticationController is a RESTful controller managing user authentication.
 *
 * @Rest\NamePrefix("authentication_")
 */
class AuthenticationController extends FOSRestController
{
    /**
     * @Route("/login", name="login")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }
        //custom check path by area
        $login_check = 'login_check';

        return $this->render('AppBundle:Back:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(Security::LAST_USERNAME),
            'error' => $error,
            'login_check' => $login_check,
        ));
    }

    /**
     * @Route("/login-check", name="login_check")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function loginCheckAction(Request $request)
    {
        try {
            $user = $this->get('app.manager.user')->loadUserByCredentials(
                $request->request->get('login'),
                $request->request->get('password'),
                $this->get('app.security.token_policy')
            );

            $context = new Context();
            $context->setGroups(['Default', 'authentication']);

            return $this->view($user, Response::HTTP_OK)
                        ->setContext($context);
        } catch (NotValidPasswordException $e) {
            return new JsonResponse('', Response::HTTP_UNAUTHORIZED);
        } catch (UsernameNotFoundException $e) {
            return new JsonResponse('Username not found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @return JsonResponse
     */
    public function meAction()
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return new JsonResponse('User not found', Response::HTTP_NOT_FOUND);
        }

        return $this->view($user, Response::HTTP_OK);
    }
}
