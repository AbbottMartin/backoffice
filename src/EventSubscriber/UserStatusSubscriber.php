<?php
// src/EventSubscriber/TokenSubscriber.php
namespace App\EventSubscriber;

use App\Entity\User as AppUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

//This clas will listen to the execution of every controller
class UserStatusSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security, )
    {
        $this->security = $security;
    }

    //Befone the execution, we validate if the user is enabled.
    public function onKernelController(ControllerEvent $event)
    {
        $user = $this->security->getUser();
        if (!$user instanceof AppUser) {
            return;
        }
        
        if (!$user->getEnabled()) {
            throw new AccessDeniedHttpException('Your user account is disabled.');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}