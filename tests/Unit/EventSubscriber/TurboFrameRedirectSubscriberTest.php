<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\TurboFrameRedirectSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Turbo Frame Redirect Subscriber Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class TurboFrameRedirectSubscriberTest extends TestCase
{
    private $kernel;

    /**
     * setup.
     */
    protected function setUp(): void
    {
        $this->kernel = $this->createMock(HttpKernelInterface::class);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGetSubscribedEvents()
    {
        $subscriber = new TurboFrameRedirectSubscriber();

        self::assertIsArray($subscriber::getSubscribedEvents());
        self::assertArrayHasKey(ResponseEvent::class, $subscriber::getSubscribedEvents());
    }

    /**
     * @dataProvider kernelResponseProvider
     *
     * @param int         $responseCode
     * @param string|null $responseLocation
     * @param array       $headers
     * @param bool        $turboLocation
     * @param int         $responseCodeAssertion
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testOnKernelResponse(int $responseCode, ?string $responseLocation, array $headers, bool $turboLocation, int $responseCodeAssertion): void
    {
        $request = new Request();

        foreach ($headers as $name => $value) {
            $request->headers->set($name, $value);
        }

        $response = new Response(null, $responseCode, ['Location' => $responseLocation]);
        $event = new ResponseEvent($this->kernel, $request, HttpKernelInterface::MAIN_REQUEST, $response);
        $subscriber = new TurboFrameRedirectSubscriber();

        $subscriber->onKernelResponse($event);

        self::assertSame($turboLocation, $event->getResponse()->headers->has('Turbo-Location'));
        self::assertSame($responseCodeAssertion, $event->getResponse()->getStatusCode());
    }

    /**
     * @return \Generator
     */
    public function kernelResponseProvider(): \Generator
    {
        yield 'no_redirect_response' => [
            'response_code' => Response::HTTP_OK,
            'response_location' => null,
            'request_headers' => [],
            'has_turbo_location' => false,
            'resulting_response_code' => Response::HTTP_OK,
        ];

        yield 'no_turbo_frame' => [
            'response_code' => Response::HTTP_FOUND,
            'response_location' => null,
            'request_headers' => [],
            'has_turbo_location' => false,
            'resulting_response_code' => Response::HTTP_FOUND,
        ];

        yield 'no_frame_redirect' => [
            'response_code' => Response::HTTP_FOUND,
            'response_location' => null,
            'request_headers' => [
                'Turbo-Frame' => 'foo',
            ],
            'has_turbo_location' => false,
            'resulting_response_code' => Response::HTTP_FOUND,
        ];

        yield 'frame_redirect' => [
            'response_code' => Response::HTTP_FOUND,
            'response_location' => null,
            'request_headers' => [
                'Turbo-Frame' => 'foo',
                'Turbo-Frame-Redirect' => 'true',
            ],
            'has_turbo_location' => true,
            'resulting_response_code' => Response::HTTP_NO_CONTENT,
        ];
    }
}
