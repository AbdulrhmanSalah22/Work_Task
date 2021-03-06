<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class forceDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Posts:ForceDelete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete All Posts That Softly Deleted for more than 30 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $posts = Post::whereNotNull('deleted_at')->where(
            'deleted_at', '<=', now()->subDays(30)->toDateTimeString()
        )->get();
        foreach ($posts as $post){
            $post->forceDelete();
        }
    }
}
