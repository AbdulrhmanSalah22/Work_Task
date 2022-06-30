<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Http:SendRequest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send HTTP Request to https://randomuser.me/api/';

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
      $response =  Http::get('https://randomuser.me/api/');
      if ($response){
          Log::info($response->object());
      }
    }
}
