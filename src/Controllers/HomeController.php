<?php namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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