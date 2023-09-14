<?php

namespace App\Tests\Unit\EventSubscriber;

use App\EventSubscriber\UserInfoSubscriber;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UserInfoSubscriberTest extends TestCase
{
    use ProphecyTrait;

    private UserInfoSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->subscriber = new UserInfoSubscriber();
    }

    public function testGetSubscribedEvents(): void
    {
        $events = $this->subscriber::getSubscribedEvents();

        $this->assertArrayHasKey('kernel.request', $events);
        $this->assertSame('onKernelRequest', $events['kernel.request']);

        $this->assertCount(1, $events);
    }

    public function testOnKernelRequest(): void
    {
        $request = new Request();
        $request->headers->add(['User-Agent' => 'Test User-Agent']);
        $request->server->add(['REMOTE_ADDR' => '127.0.0.1']);
        $requestEvent = $this->prophesize(RequestEvent::class);
        $requestEvent->getRequest()->shouldBeCalledTimes(1)->willReturn($request);

        $this->subscriber->onKernelRequest($requestEvent->reveal());

        $this->assertEquals('127.0.0.1', $request->request->get('ip'));
        $this->assertEquals('Test User-Agent', $request->request->get('userAgent'));
    }
}