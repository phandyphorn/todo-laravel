<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     * Get /api/todos - Get all user's todos
     * @return \Illuminate\Http\JsonResponse
     */


    // GET /api/todos (we get from route/api.php)
    public function index(Request $request)
    {
        $todos = $request->user()->todos()
            ->orderBy('created_at', 'desc')
            ->get(); // Get all todos for the authenticated user, ordered by creation date descending

        return response()->json([
            'data' => $todos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * /api/todos - Create a new todo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable|boolean',
        ]);

        // Create a new todo for the authenticated user
        // This creates the todo through the relationship-laravel automatically sets user_id for ue.
        $todo = $request->user()->todos()->create($validated);

    
        /**
         * This ways create too, but we need to manually include user_id if needed:
         * $todo = Post::create($request->validated())
         * $todo = Todo::create([
         * ...$validated, 'user_id' => $request->user()->id])
         * 
         */
        

        return response()->json([
            'message' => 'Todo created successfully',
            'data' => $todo,
        ], 201);
    }

    /**
     * Display the specified resource.
     * /api/todos/{id} - Get a specific todo
     */
    public function show(Request $request, Todo $todo)
    {
        $this->authorize('view', $todo); // Ensure the authenticated user can view this todo

        return response()->json([
            'data' => $todo,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * /api/todos/{id} - Update a specific todo
     */
    public function update(Request $request, Todo $todo)
    {

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|required|boolean',
        ]);

        $todo->update($validated);

        return response()->json([
            'message' => 'Todo updated successfully',
            'data' => $todo,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * /api/todos/{id} - Delete a specific todo
     */
    public function destroy(Request $request, Todo $todo)
    {
        if ($todo->user_id !== auth()->id()) {
            return response()->json(['message' => "Forbidden"], 403);
        }

        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully',
        ]);
    }
}
