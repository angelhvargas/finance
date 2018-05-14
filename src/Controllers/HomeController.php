<?php namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends Controller 
{
    protected $model = null;

    public function index(Request $request, Response $response) 
    {
        
        return $this->app->view->render($response, 'home.twig', [
            'name' => $args['name']
        ]);
    }


}