<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function authenticate(string $email, string $password, ?string $tenantId = null): ?User
    {
        $user = $this->userRepository->findByEmail($email, $tenantId);
        
        if (!$user) {
            return null;
        }

        if ($user->isLocked()) {
            throw new \Exception('Account is locked. Please try again later.');
        }
        
        if (!Hash::check($password, $user->password)) {
            $this->handleFailedLogin($user);
            return null;
        }
        
        if (!$user->isActive()) {
            throw new \Exception('Account is not active.');
        }

        $this->handleSuccessfulLogin($user);
        
        return $user;
    }

    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['status'] = $data['status'] ?? 'pending';
        
        return $this->userRepository->create($data);
    }

    protected function handleFailedLogin(User $user): void
    {
        $this->userRepository->incrementFailedAttempts($user->id);
        
        if ($user->failed_login_attempts >= 5) {
            $this->userRepository->lockAccount($user->id);
        }
    }

    protected function handleSuccessfulLogin(User $user): void
    {
        $this->userRepository->resetFailedAttempts($user->id);
        $this->userRepository->update($user->id, ['last_login' => now()]);
    }

    public function createAccessToken(User $user, string $deviceName = 'default'): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }

    public function createSsoToken(User $user, string $deviceName = 'web-session'): string
    {
        $user->tokens()->where('name', $deviceName)->delete();

        return $this->createAccessToken($user, $deviceName);
    }

    public function revokeTokenByName(User $user, string $deviceName): void
    {
        $user->tokens()->where('name', $deviceName)->delete();
    }

    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
