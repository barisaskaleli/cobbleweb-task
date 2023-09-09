<?php

namespace App\EventListener;

use App\Exception\AuthException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class AuthenticationListener
{
    /**
     * @var Security $security
     */
    private $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param RequestEvent $event
     * @return void
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $route = $event->getRequest()->attributes->get('_route');

        if ($route === 'api_register') {
            return;
        }

        $user = $this->security->getUser();

        if ($user === null) {
            dd($event->getRequest());
            throw new AccessDeniedException(AuthException::UNAUTHENTICATE_USER);
        }
    }
}