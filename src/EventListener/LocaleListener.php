<?php


namespace App\EventListener;


use Symfony\Component\HttpKernel\Event\RequestEvent;

class LocaleListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // some logic to determine the $locale
        $locale = "ru";
        if ($request->getLocale()) {
            $locale = $request->getLocale();
        }

        $request->setLocale($locale);
    }
}