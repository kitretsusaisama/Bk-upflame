<?php

namespace Tests\Unit\ValueObjects;

use Tests\TestCase;
use App\Domain\Shared\ValueObjects\UUID;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid as RamseyUuid;

class UUIDTest extends TestCase
{
    /** @test */
    public function it_can_create_a_uuid()
    {
        $uuidValue = RamseyUuid::uuid4()->toString();
        $uuid = new UUID($uuidValue);
        
        $this->assertInstanceOf(UUID::class, $uuid);
        $this->assertEquals($uuidValue, $uuid->getValue());
    }

    /** @test */
    public function it_throws_exception_for_invalid_uuid()
    {
        $this->expectException(InvalidArgumentException::class);
        
        new UUID('invalid-uuid');
    }

    /** @test */
    public function it_returns_string_representation()
    {
        $uuidValue = RamseyUuid::uuid4()->toString();
        $uuid = new UUID($uuidValue);
        
        $this->assertEquals($uuidValue, (string) $uuid);
    }

    /** @test */
    public function it_can_check_equality()
    {
        $uuidValue = RamseyUuid::uuid4()->toString();
        $uuid1 = new UUID($uuidValue);
        $uuid2 = new UUID($uuidValue);
        $uuid3 = new UUID(RamseyUuid::uuid4()->toString());
        
        $this->assertTrue($uuid1->equals($uuid2));
        $this->assertFalse($uuid1->equals($uuid3));
    }

    /** @test */
    public function it_can_generate_a_new_uuid()
    {
        $uuid = UUID::generate();
        
        $this->assertInstanceOf(UUID::class, $uuid);
        $this->assertTrue(RamseyUuid::isValid($uuid->getValue()));
    }
}