<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutEventSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;
    private $flashBag;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        FlashBagInterface $flashBag
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->flashBag = $flashBag;
    }
    public function onLogoutEvent(LogoutEvent $event)
    {
        $this->flashBag->add('success', 'Logget out successfully');
        $event->setResponse(
            new RedirectResponse($this->urlGenerator->generate('post_index'))
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
