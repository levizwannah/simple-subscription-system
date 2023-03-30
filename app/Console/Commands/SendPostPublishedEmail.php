<?php

namespace App\Console\Commands;

use App\Mail\PostPublished;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendPostPublishedEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-post-published-email {post}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email to the user when a post is published';

    /**
     * Execute the console command.
     * Since the job that runs this command is unique,
     * The stories won't be sent twice (duplicate emails)
     */
    public function handle(Mail $mail): void
    {
        $post = Post::find($this->argument('post'));

        foreach($post->website()->subscriptions() as $subscription){
            $user = $subscription->user();
            $mail::to($user)->send(new PostPublished($post));
        }

    }
}
