<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
                /**
 * @OA\Get(
 *     path="/api/posts",
 *     description="get all posts",
 *     @OA\Response(response="default", description="get all posts")
 * )
 */
    public function index()
    {
        return response(['data' => Post::all()], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
                    /**
 * @OA\Post(
 *     path="/api/create-post/{id}",
 *     description="create posts",
 *     @OA\Response(response="default", description="create posts")
 * )
 */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $post = Post::create([
            'content' => $request['content']
        ]);

        return response(['message' => 'Create successfully!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        return response(['data' => $post], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
                        /**
 * @OA\Post(
 *     path="/api/update-post/{id}",
 *     description="create posts",
 *     @OA\Response(response="default", description="create posts")
 * )
 */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $post = Post::findOrFail($id);

        $post->content = $request['content'];
        $post->save();

        return response(['message' => 'Update successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    /**
 * @OA\Post(
 *     path="/api/delete-post/{id}",
 *     description="delete posts",
 *     @OA\Response(response="default", description="delete posts")
 * )
 */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response(['message' => 'Delete successfully!'], 200);
    }
}
