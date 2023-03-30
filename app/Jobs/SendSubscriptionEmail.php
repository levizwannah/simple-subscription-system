<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class SendSubscriptionEmail implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Post $post){
        $this->post = $post->withoutRelations();
    }

    /**
     * Ensures no duplicate emails get sent because this job won't have a duplicate instance.
     */
    public function uniqueId(): string
    {
        return $this->post->id;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call('app:send-post-published-email', [
            'post' => $this->post->id
        ]);
    }
}
