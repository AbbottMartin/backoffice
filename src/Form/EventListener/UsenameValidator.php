<?php

namespace App\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use TypeError;

class UsenameValidator implements EventSubscriberInterface
{
    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();

        if (!\is_string($data)) {
            $event->getForm()->addError( new FormError('Usename must be a character string.'));
        }
        if (!preg_match('/^[A-Za-z.]{3,30}$/', $data)) {
            $event->getForm()->addError( new FormError('Usename allows alphabetic characters only.'));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [FormEvents::PRE_SUBMIT => 'preSubmit'];
    }
}