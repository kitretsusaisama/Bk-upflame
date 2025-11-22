<?php

namespace App\Jobs\Auth;

use App\Domain\Identity\Models\OtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected OtpCode $otpCode;

    /**
     * Create a new job instance.
     *
     * @param  OtpCode  $otpCode
     * @return void
     */
    public function __construct(OtpCode $otpCode)
    {
        $this->otpCode = $otpCode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Logic to send OTP code to user
        // This could be via email, SMS, or other channels
        // based on the OTP code configuration
    }
}