<?php

namespace Tests\Unit\ValueObjects;

use Tests\TestCase;
use App\Domain\Shared\ValueObjects\Email;
use InvalidArgumentException;

class EmailTest extends TestCase
{
    /** @test */
    public function it_can_create_an_email()
    {
        $email = new Email('test@example.com');
        
        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals('test@example.com', $email->getValue());
    }

    /** @test */
    public function it_throws_exception_for_invalid_email()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new Email('invalid-email');
    }

    /** @test */
    public function it_returns_string_representation()
    {
        $email = new Email('test@example.com');
        
        $this->assertEquals('test@example.com', (string) $email);
    }

    /** @test */
    public function it_can_check_equality()
    {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');
        $email3 = new Email('other@example.com');
        
        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($email3));
    }
}