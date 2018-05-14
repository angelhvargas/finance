<?php

use Slim\Http\Request;
use Slim\Http\Response;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\LoanController;
use App\Controllers\LenderController;
// Routes


$app->group('/api', function() use ($app) {
    
    $app->group('/user', function () use ($app) {
        
        // route group related with a particular user
        $app->group('/{id:[0-9]+}', function() use ($app) {
            $app->get('', UserController::class.':find');    
            $this->get('/loans', UserController::class.':withLoans' )->setName('user-loans');
        });        
        //default endpoint returns all the users
        $app->get('', UserController::class.':index');        
    });

    $app->group('/loan', function() use ($app){
        $this->get('/all', LoanController::class.':loansReport')->setName('loans-report');
        //$this->get('/{id:[0-9]+}', LoanController::class.':find');
        //$this->patch('/{id:[0-9]+}', LoanController::class.':update');
        //$this->delete('/{id:[0-9]+}', LoanController::class.':delete');
        //$this->post('', LoanController::class.':save');
    });

    $app->group('/bid', function() use ($app){
        $this->get('/all', BidController::class.':bidsReport')->setName('bids-report');
        //$this->get('/all/valid', BidController::class.':bidsReportValid')->setName('bids-report-valid');
        //$this->get('/{id:[0-9]+}', BidController::class.':find')->setName('bids-find');
        //$this->patch('/{id:[0-9]+}', BidController::class.':update')->setName('bids-update');
        //$this->delete('/{id:[0-9]+}', BidController::class.':delete')->setName('bids-delete');
        //$this->post('', BidController::class.':save');
    });

    $app->group('/lender', function() use ($app){
        $this->get('/all', LenderController::class.':all')->setName('lenders-report');
        $this->get('/{id:[0-9]+}', LenderController::class.':find')->setName('lenders-find');
        //$this->patch('/{id:[0-9]+}', LenderController::class.':update')->setName('bids-update');
        //$this->delete('/{id:[0-9]+}', LenderController::class.':delete')->setName('bids-delete');
        //$this->post('', LenderController::class.':save');
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