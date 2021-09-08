<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * Turbo Frame Redirect Subscriber.
 *
 * @author wicliff <wwolda@gmail.com>
 */
final class TurboFrameRedirectSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => 'onKernelResponse',
        ];
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$this->shouldWrapRedirect($event->getRequest(), $event->getResponse())) {
            return;
        }

        $response = new Response(null, 204, [
            'Turbo-Location' => $event->getResponse()->headers->get('Location'),
        ]);

        $event->setResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request  $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return bool
     */
    private function shouldWrapRedirect(Request $request, Response $response): bool
    {
        if (false === $response->isRedirect()) {
            return false;
        }

        if (null === $request->headers->get('Turbo-Frame')) {
            return false;
        }

        return $request->headers->has('Turbo-Frame-Redirect');
    }
}
