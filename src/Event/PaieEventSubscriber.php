<?php

namespace App\Event;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;

class PaieEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents() : array
    {
        return [FormEvents::POST_SUBMIT  => 'deduction'];
    }

    public function deduction(FormEvent $event) : void
    {
        dump($event->getData());
    }
}