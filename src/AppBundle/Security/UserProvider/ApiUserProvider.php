<?php

namespace AppBundle\Security\UserProvider;

use GuzzleHttp\Exception\BadResponseException;
use AppBundle\Connector\ApiConnector;
use AppBundle\Security\User;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class ApiUserProvider implements UserProviderInterface
{
    private $connector;

    public function __construct(ApiConnector $connector)
    {
        $this->connector = $connector;
    }

    public function loadUserByUsernameAndPassword($username, $password)
    {
        try {
            $data = $this->connector->request(
                ApiConnector::HTTP_METHOD_POST,
                '/login-check',
                [
                    'body' => [
                        'login' => $username,
                        'password' => $password,
                    ],
                ]
            );
        } catch (BadResponseException $e) {
            throw new AuthenticationException('Identifiant ou mot de passe incorrect');
        }

        return User::fromApiResponse($data);
    }

    public function loadUserByUsername($username)
    {
        throw new \RuntimeException('loadUserByUsername not supported by the API');
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        try {
            $data = $this->connector->request(ApiConnector::HTTP_METHOD_GET, '/me', [
                'headers' => [ApiConnector::TOKEN_NAME => $user->getToken()]
            ]);
        } catch (BadResponseException $e) {
            throw new AuthenticationException();
        }

        $refreshedUser = User::fromApiResponse($data);

        if (!$user->isEqualTo($refreshedUser)) {
            return new User();
        }

        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'AppBundle\Security\User';
    }
}