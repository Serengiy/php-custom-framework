<?php

namespace framework\tests;

use PHPUnit\Framework\TestCase;
use Somecode\Framework\Session\Session;

class SessionTest extends TestCase
{
    public function setUp(): void
    {
        unset($_SESSION);
    }

    public function test_set_and_get_flash()
    {
        $session = new Session();
        $session->setFlash('success', 'Успешно!');
        $session->setFlash('error', 'Неуспешно!:(');
        $this->assertTrue($session->hasFlash('success'));
        $this->assertTrue($session->hasFlash('error'));
        $this->assertEquals(['Успешно!'], $session->getFlash('success'));
        $this->assertEquals(['Неуспешно!:('], $session->getFlash('error'));
        $this->assertEquals([], $session->getFlash('warning'));
    }
}
