<?php

declare(strict_types=1);

/*
 * This file is part of the paragin assignment.
 * (c) wicliff <wwolda@gmail.com>
 */

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Application Availability Functional Test.
 *
 * @author wicliff <wwolda@gmail.com>
 */
class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     *
     * @param mixed $url
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testPageIsSuccessful($url): void
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * @return \Generator
     */
    public function urlProvider(): \Generator
    {
        yield [
            'homepage' => '/',
        ];
    }
}
