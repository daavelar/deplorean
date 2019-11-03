<?php

namespace Laravocker\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class LaravockerSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravocker:setup {--up}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Laravock';

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
        $basePath = __DIR__ . '/src/';

        $dockerComposeContent = [
            'version'  => config('laravocker::version'),
            'networks' => ['www' => []],
            'services' => [
                'php_fpm' => [
                    'container_name' => 'laravocker_php_fpm',

                    'build'    => [
                        'context' => './php-fpm',
                    ],
                    'volumes'  => [
                        "$basePath:/var/www/html",
                    ],
                    'networks' => ['www'],
                ],
                'nginx'   => [
                    'container_name' => 'laravocker_nginx',
                    'build'          => [
                        'context' => './nginx',
                    ],
                    'ports'          => [
                        '8000:80',
                    ],
                    'volumes'        => [
                        "$basePath:/usr/share/nginx/html",
                    ],
                    'networks'       => ['www'],
                ],
            ],
        ];

        if (config('laravocker::mysql.enabled')) {
            $mysqlParams = [
                'build'          => [
                    'context' => './mysql',
                ],
                'container_name' => 'laravocker_mysql',
                'volumes'        => [
                    '~/.laravocker/' . strtolower(config('app.name')) . '/mysql:/var/lib/mysql',
                ],
                'environment'    => [
                    'MYSQL_ROOT_PASSWORD' => config('laravocker::mysql.rootpass'),
                    'MYSQL_DATABASE'      => config('laravocker::mysql.database'),
                    'MYSQL_USER'          => config('laravocker::mysql.username'),
                    'MYSQL_PASSWORD'      => config('laravocker::mysql.password'),
                ],
                'networks'       => ['www'],
            ];

            $dockerComposeContent['services']['mysql'] = $mysqlParams;

            $this->line('Add these lines to your .env');
            $this->line('DB_DATABASE=' . config('laravocker::mysql.database'));
            $this->line('DB_USERNAME=' . config('laravocker::mysql.username'));
            $this->line('DB_PASSWORD=' . config('laravocker::mysql.password'));
        }

        if (config('laravocker::redis.enabled')) {
            $redisParams = [
                'container_name' => 'laravocker_redis',
                'build'          => [
                    'context' => './redis',
                ],
                'networks'       => ['www'],
            ];

            $dockerComposeContent['services']['redis'] = $redisParams;

            $this->line('Add these lines to your .env');
            $this->line('REDIS_HOST=redis');
            $this->line('REDIS_PORT=6379');
            $this->line('REDIS_PASSWORD=');
        }

        if (config('laravocker::cron.enabled')) {
            $cronParams = [
                'container_name' => 'laravocker_cron',
                'build'          => [
                    'context' => './cron',
                ],
                'networks'       => ['www'],
            ];

            $dockerComposeContent['services']['cron'] = $cronParams;
        }

        if (config('laravocker::horizon.enabled')) {
            $horizonParams = [
                'container_name' => 'laravocker_horizon',
                'build'          => [
                    'context' => './horizon',
                ],
                'volumes'        => [
                    "$basePath:/var/www/html",
                ],
                'networks'       => ['www'],
            ];

            $dockerComposeContent['services']['horizon'] = $horizonParams;

            $this->info('Dont forget to run composer require laravel/horizon');
        }

        $this->info("Starting containers... Please wait");


        file_put_contents(base_path('docker/docker-compose.yml'), Yaml::dump($dockerComposeContent));

        if ($this->option('up')) {
            $this->call('laravocker:up');
        }
        else {
            $this->info('Your docker-compose.yml was created, run php artisan laravocker:up to start your containers');
        }

    }
}
