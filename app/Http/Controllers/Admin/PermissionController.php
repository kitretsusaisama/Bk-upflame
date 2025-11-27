<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Access\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions grouped by resource
     */
    public function index()
    {
        if (!auth()->user()->hasPermission('platform.permission.view')) {
            abort(403, 'Unauthorized action');
        }

        $permissions = Permission::all()->groupBy('resource');

        return view('admin.permissions.index', compact('permissions'));
    }
}
