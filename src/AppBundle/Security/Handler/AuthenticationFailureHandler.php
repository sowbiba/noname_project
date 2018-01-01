<?php

namespace AppBundle\Security\Handler;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\Security\Http\HttpUtils;

final class AuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{
    private $userAccessLogger;

    public function __construct(
        HttpKernelInterface $httpKernel,
        HttpUtils $httpUtils,
        Logger $userAccessLogger,
        array $options = array(),
        LoggerInterface $logger = null
    ) {
        parent::__construct($httpKernel, $httpUtils, $options, $logger);

        $this->userAccessLogger = $userAccessLogger;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->userAccessLogger->log($request, null, $exception);

        return parent::onAuthenticationFailure($request, $exception);
    }
}