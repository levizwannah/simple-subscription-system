<?php

namespace App\Http\Controllers;

use App\Constants\PostConstants;
use App\Jobs\SendSubscriptionEmail;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail|required|max:255|unique:posts',
            'description' => 'required|max:500',
            'content' => 'nullable'
        ]);

       
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content
        ]);

        return Response::json(compact('post'));
    }

    /**
     * Changes the post status from draft to published.
     */
    public function status(int $postId, Request $request){
        $request->validate([
            'status' => "required|in:" . 
            PostConstants::PUBLISHED->value . "," . 
            PostConstants::DRAFT->value
        ]);

        $post = Post::findOrFail($postId);
        $post->status = $request->status;

        $post->save();
        $post->refresh();

        if($post->status == PostConstants::PUBLISHED->value){

            SendSubscriptionEmail::dispatch($post)->onQueue('subscription_emails');

        }

        return Response::json(compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
