<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Domain\Identity\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $users = User::paginate(15);
        
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\UserResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            // Add other validation rules as needed
        ]);
        
        $user = User::create($data);
        
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\Identity\Models\User  $user
     * @return \App\Http\Resources\UserResource
     */
    public function update(Request $request, User $user)
    {
        $updateData = $request->validate([
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            // Add other validation rules as needed
        ]);
        
        $user->update($updateData);
        
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\Identity\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();
        
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}