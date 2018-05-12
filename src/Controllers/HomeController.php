<?php namespace App\Controllers;


class HomeController extends Controller 
{
    protected $model = null;

    public function index() 
    {
        return "hello from controller";
    }


}