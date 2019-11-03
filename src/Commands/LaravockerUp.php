<?php

namespace Laravocker\Commands;

use Illuminate\Console\Command;

class LaravockerUp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravocker:up';

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
            $this->error('Run php artisan laravocker:setup to create the yml file');

            exit;
        }
        chdir(base_path('docker'));

        shell_exec("docker-compose down --remove-orphans");
        shell_exec("docker-compose up -d --build");
    }
}
