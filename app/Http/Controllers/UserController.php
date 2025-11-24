<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // In a real application, you would check permissions here
        // $this->authorize('viewAny', User::class); // server-side policy

        $users = User::with(['profile','roles','tenant'])
            ->paginate(15)
            ->appends($request->all());

        return Inertia::render('Users/Index', [
            'filters' => $request->only(['search','role']),
            'users' => $users
        ]);
    }
}