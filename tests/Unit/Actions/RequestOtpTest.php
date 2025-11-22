<?php

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Domains\Identity\Actions\RequestOtp;
use App\Domains\Identity\Services\OtpService;
use App\Domains\Identity\Contracts\UserRepositoryInterface;
use Mockery as m;

class RequestOtpTest extends TestCase
{
    /** @test */
    public function it_generates_an_otp_and_returns_success()
    {
        // Create mocks
        $otpService = m::mock(OtpService::class);
        $userRepository = m::mock(UserRepositoryInterface::class);
        
        // Set up expectations
        $userRepository->shouldReceive('findByEmail')->once()->andReturn(null);
        $otpService->shouldReceive('generateOtp')->once()->with('test@example.com', null)->andReturn('123456');
        
        // Create the action
        $action = new RequestOtp($otpService, $userRepository);
        
        // Execute the action
        $result = $action->execute('test@example.com');
        
        // Assert the result
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('OTP sent successfully', $result['message']);
        $this->assertFalse($result['user_exists']);
    }
}