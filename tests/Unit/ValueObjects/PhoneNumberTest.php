<?php

namespace Tests\Unit\ValueObjects;

use Tests\TestCase;
use App\Domain\Shared\ValueObjects\PhoneNumber;
use InvalidArgumentException;

class PhoneNumberTest extends TestCase
{
    /** @test */
    public function it_can_create_a_phone_number()
    {
        $phone = new PhoneNumber('+1234567890');
        
        $this->assertInstanceOf(PhoneNumber::class, $phone);
        $this->assertEquals('+1234567890', $phone->getValue());
    }

    /** @test */
    public function it_throws_exception_for_invalid_phone_number()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new PhoneNumber('invalid-phone');
    }

    /** @test */
    public function it_returns_string_representation()
    {
        $phone = new PhoneNumber('+1234567890');
        
        $this->assertEquals('+1234567890', (string) $phone);
    }

    /** @test */
    public function it_can_check_equality()
    {
        $phone1 = new PhoneNumber('+1234567890');
        $phone2 = new PhoneNumber('+1234567890');
        $phone3 = new PhoneNumber('+0987654321');
        
        $this->assertTrue($phone1->equals($phone2));
        $this->assertFalse($phone1->equals($phone3));
    }
}