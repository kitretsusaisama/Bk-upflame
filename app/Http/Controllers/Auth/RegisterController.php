<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\UserProfile;
use App\Domains\Identity\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = $this->create($request->all());

        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }

    /**
     * Get a validator for an incoming registration request
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration
     *
     * @param  array  $data
     * @return \App\Domains\Identity\Models\User
     */
    protected function create(array $data)
    {
        // For simplicity, we're assigning to the first available tenant
        // In a real application, you'd need to handle tenant selection
        $tenantId = $this->getDefaultTenantId();

        $user = User::create([
            'tenant_id' => $tenantId,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'pending',
            'mfa_enabled' => false,
            'email_verified' => false,
        ]);

        $user->profile()->create([
            'tenant_id' => $tenantId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'] ?? null,
        ]);

        return $user;
    }

    /**
     * Get default tenant ID for registration
     *
     * @return string
     */
    protected function getDefaultTenantId()
    {
        // Get the first tenant or create a default one
        $tenant = Tenant::first();
        if (!$tenant) {
            $tenant = Tenant::create([
                'name' => 'Default Tenant',
                'domain' => 'default.local',
                'status' => 'active'
            ]);
        }
        
        return $tenant->id;
    }
}