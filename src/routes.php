<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\HomeController;
use App\Controllers\UserController;
// Routes


$app->group('/api', function() use ($app) {
    
    $app->group('/user/{id:[0-9]+}', function () use ($app) {
        
        $this->map(['GET', 'DELETE', 'PATCH', 'PUT'], '', function ($request, $response, $args) use ($app){

            // Find, delete, patch or replace user identified by $args['id']
        })->setName('user');

        
        //return the user's loans
        $this->get('/loans', UserController::class.':withLoans' )->setName('user-loans');

    });
    
});

$app->get('/', HomeController::class.':index');

// $app->get('/[{name}]', function (Request $request, Response $response, array $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");
    
//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });

//catch all missing request to the 404 page
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});