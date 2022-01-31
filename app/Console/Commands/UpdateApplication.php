<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $request = new \Illuminate\Http\Request();
        (new \App\Http\Controllers\ProductController)->application($request);
        return Command::SUCCESS;

    }
}
