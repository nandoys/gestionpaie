<?php

namespace App\Event;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PaieEventSubscriber implements EventSubscriberInterface
{
    public function __construct(){}

    public static function getSubscribedEvents() : array
    {
        return [FormEvents::PRE_SET_DATA  => 'deduction'];
    }

    public function deduction(FormEvent $event) : void
    {
       
        //$event->setData($event->getData());
    }
}