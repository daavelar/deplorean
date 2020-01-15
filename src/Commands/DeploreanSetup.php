<?php

namespace deplorean\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class DeploreanSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deplorean:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Deplorean';

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
            'version'  => config('deplorean::version'),
            'networks' => ['www' => []],
            'services' => [
                'php_fpm' => [
                    'container_name' => 'deplorean_php_fpm',

                    'build'    => [
                        'context' => './php-fpm',
                    ],
                    'volumes'  => [
                        "$basePath:/var/www/html",
                    ],
                    'networks' => ['www'],
                ],
                'nginx'   => [
                    'container_name' => 'deplorean_nginx',
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

        if (config('deplorean::mysql.enabled')) {
            $mysqlParams = [
                'build'          => [
                    'context' => './mysql',
                ],
                'container_name' => 'deplorean_mysql',
                'volumes'        => [
                    '~/.deplorean/' . strtolower(config('app.name')) . '/mysql:/var/lib/mysql',
                ],
                'environment'    => [
                    'MYSQL_ROOT_PASSWORD' => config('deplorean::mysql.rootpass'),
                    'MYSQL_DATABASE'      => config('deplorean::mysql.database'),
                    'MYSQL_USER'          => config('deplorean::mysql.username'),
                    'MYSQL_PASSWORD'      => config('deplorean::mysql.password'),
                ],
                'networks'       => ['www'],
            ];

            $dockerComposeContent['services']['mysql'] = $mysqlParams;

            $this->line('Add these lines to your .env');
            $this->line('DB_DATABASE=' . config('deplorean::mysql.database'));
            $this->line('DB_USERNAME=' . config('deplorean::mysql.username'));
            $this->line('DB_PASSWORD=' . config('deplorean::mysql.password'));
        }

        if (config('deplorean::redis.enabled')) {
            $redisParams = [
                'container_name' => 'deplorean_redis',
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

        if (config('deplorean::cron.enabled')) {
            $cronParams = [
                'container_name' => 'deplorean_cron',
                'build'          => [
                    'context' => './cron',
                ],
                'networks'       => ['www'],
            ];

            $dockerComposeContent['services']['cron'] = $cronParams;
        }

        if (config('deplorean::horizon.enabled')) {
            $horizonParams = [
                'container_name' => 'deplorean_horizon',
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
            $this->call('deplorean:up');
        }
        else {
            $this->info('Your docker-compose.yml was created, run php artisan deplorean:up to start your containers');
        }

    }
}
