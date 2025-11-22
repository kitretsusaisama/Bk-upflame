<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Domain\Identity\Models\User;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $tenant = Tenant::factory()->create();
        
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals($tenant->id, $user->tenant_id);
        $this->assertEquals('active', $user->status);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $user = new User();
        
        $expectedFillable = [
            'tenant_id', 'email', 'phone', 'password', 
            'email_verified_at', 'phone_verified_at', 'status',
            'last_login_at'
        ];
        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    /** @test */
    public function it_has_correct_casts()
    {
        $user = new User();
        
        $expectedCasts = [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
        
        foreach ($expectedCasts as $key => $value) {
            $this->assertEquals($value, $user->getCasts()[$key]);
        }
    }

    /** @test */
    public function it_belongs_to_a_tenant()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        $this->assertInstanceOf(Tenant::class, $user->tenant);
        $this->assertEquals($tenant->id, $user->tenant->id);
    }
}