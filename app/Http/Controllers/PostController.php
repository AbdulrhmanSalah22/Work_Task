<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $posts = Post::query()->orderByDesc('pinned')->get();

        if ($posts)
            return response()->json(['posts' => $posts]);
        else
            return response()->json(['message' => 'No Posts Available']);
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
     * @param  \App\Http\Requests\StoreTagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $image = $request->file('cover_image');
        $path = $image->store('public/cover_images');
        $name = $image->getClientOriginalName();
      $store =  Post::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'body' => $request->body ,
            'cover_image' => $name,
            'path' => $path,
            'pinned' => $request->pinned,
        ]);
        if ($store)
            return response()->json(['message' => 'Post Created Successfully']);
        else
            return response()->json(['created' => false]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if ($post)
            return response()->json(['post' => $post]);
        else
            return response()->json(['post' => 'Not Found']);

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
     * @param  \App\Http\Requests\UpdateTagRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $update = $post->update([
            'title' => $request->title,
            'body' => $request->body ,
            'cover_image' => $request->cover_image ?? $post->cover_image,
            'pinned' => $request->pinned,
        ]);
        if ($update)
            return response()->json(['message' => 'Post Updated Successfully']);
        else
            return response()->json(['updated' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
      $delete =  $post->delete();
        if ($delete)
            return response()->json(['message' => 'Post Softly Deleted Successfully']);
        else
            return response()->json(['deleted' => false]);
    }
    public function viewDeleted()
    {
       $deleted_posts = Post::onlyTrashed()->get();
        if ($deleted_posts)
            return response()->json(['deleted_post' => $deleted_posts]);
        else
            return response()->json(['deleted_post' => 'No Deleted Posts']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function restore($id){
        $restored =  Post::withTrashed()->findOrFail($id)->restore();
        if ($restored)
            return response()->json(['restore' => 'Your Post Restored Successfully']);
        else
            return response()->json(['restore' => false]);
    }
}
