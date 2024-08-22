<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class GenerateDocs extends Command
{
    protected $signature = 'swagger:generate-docs';
    protected $description = 'Generate Swagger documentation from routes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $routes = Route::getRoutes();
        $swaggerDocs = [];

        foreach ($routes as $route) {
            $uri = $route->uri();
            $method = $route->methods()[0];
            $action = $route->getActionName();

            $swaggerDocs[] = [
                'path' => $uri,
                'method' => $method,
                'action' => $action,
            ];
        }

        // Generate Swagger JSON file
        file_put_contents('public/docs/api-docs.json', json_encode($swaggerDocs, JSON_PRETTY_PRINT));

        $this->info('Swagger documentation generated successfully.');
    }
}
