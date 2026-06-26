<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Public routes (no need authentication)
Route::post('/auth/register', [AuthController::class, 'register']);

// To clear each part:
// + Route = Laravel's Route (build-in class for defining routes)
// + ::post = HTTP method for this route (Post = create, get = read, put/patch = update, delete = delete)
// + /auth/login = URL path
// + [AuthController::class, 'login'] = Controller and method that pointer to AuthController.php, ::class = PHP syntax to get the full class name
// + 'login' = method in AuthController.php that will handle this route (we will create this method in AuthController.php)
Route::post('/auth/login', [AuthController::class, 'login']);


// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);

    Route::get('users', [UserController::class, 'index']);
    Route::apiResource('todos', TodoController::class);
    // ↑ This generates:
    // GET    /api/todos
    // POST   /api/todos
    // GET    /api/todos/{id}
    // PUT    /api/todos/{id}
    // DELETE /api/todos/{id}
});