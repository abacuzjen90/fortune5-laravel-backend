<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    // note : import this 1st word request
    public function store(Request $request)
    {
        $field = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',

        ]);

        $post = $request->user()->posts()->create($field);

        return [$post];
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return [$post];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // note : import to fascade the gate
        Gate::authorize('modify', $post);

        $field = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',

        ]);

        $post->update($field);

        return [$post];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {

        // note : import to fascade the gate
        Gate::authorize('modify', $post);

        $post->delete();

        return ['message' => 'The post was deleted!'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
