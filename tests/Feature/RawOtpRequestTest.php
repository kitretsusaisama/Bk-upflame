<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class RawOtpRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_insert_an_otp_request_using_raw_query()
    {
        $result = DB::table('otp_requests')->insert([
            'recipient' => 'test@example.com',
            'otp_hash' => bcrypt('123456'),
            'attempts' => 0,
            'used' => false,
            'expires_at' => now()->addMinutes(5),
            'ip' => '127.0.0.1',
            'user_agent' => 'Symfony',
            'id' => '019aa76b-75ca-7267-9a96-1343209556d0',
            'updated_at' => now(),
            'created_at' => now()
        ]);

        $this->assertTrue($result);
    }
}