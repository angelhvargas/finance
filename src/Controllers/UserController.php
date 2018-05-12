<?php namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index($request, $response)
    {
        $user = new User([
  
            'first_name' => 'Angel',
            'last_name' => 'Vargas',  
            'email' => 'angelvargas@cloudways.com',  
            'date_of_birth' => '1985-08-08',
  
        ]);

        $user->save();

        return $create;
    }
}