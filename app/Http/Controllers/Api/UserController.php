<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // to avoid N+1 issue. so we use 'with' 
        $users = User::with('todos')->get();

        return response()->json(
            [
                'data' => $users,
                'count' => $users->count(),
                'total_todos' => $users->sum(fn($user) => $user->todos->count()),
            ]
        );
    }
}
