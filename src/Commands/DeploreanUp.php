<?php

namespace Deplorean\Commands;

use Illuminate\Console\Command;

class DeploreanUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deplorean:up';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run docker-compose up command';

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
     * @return mixed
     */
    public function handle()
    {
        if(!file_exists(base_path('docker/docker-compose.yml'))) {
            $this->error('Run php artisan deplorean:start to create the yml file');

            exit;
        }
        chdir(base_path('docker'));

        shell_exec("docker-compose down --remove-orphans");
        shell_exec("docker-compose up -d --build");
    }
}
