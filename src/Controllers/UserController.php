<?php namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;

/**
 * UserController class
 */
class UserController extends Controller
{
    /*
     * Resource: return all users
     *
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface $response
     * @return Array ofUsers 
     */
    public function index(Request $request, Response $response)
    {
        $users = User::all();
        return $response->withJson($users);
    }

    /**
     * Resource create a new user
     *
     * @param Psr\Http\Message\ServerRequestInterface $request
     * @param Psr\Http\Message\ResponseInterface  $response
     * @return void
     */
    public function create(Request $request, Response $response, $args) 
    {
        
        if (false) {
            $user = new User([
  
                'first_name' => 'Angel',
                'last_name' => 'Vargas',  
                'email' => 'angelvargas@cloudways.com',  
                'date_of_birth' => '1985-08-08',
      
            ]);
    
            $user->save();
        }
    }

    public function find(Request $request, Response $response, $args) 
    {
        $user_id = isset($args['id']) ? $args['id'] : false;

        try {
            
            if (!$user_id) throw new Exception("id parameter missing");

            $user = User::find($user_id);

        } catch (\Exception $e) {
            $this->log->error($e->getMessage());
        }
        return $response->withJson(
            $user
                ->with('loans')
                ->where('id', $user_id)
                ->get()
                ->toArray()
            );
    }

    public function withLoans(Request $request, Response $response, $params) 
    {
        
        $user_id = isset($args['id']) ? $args['id'] : false;
        
        try {
            if (!$user_id) throw new Exception("id parameter missing");

            $user = User::find($user_id);

        } catch (\Exception $e) {
            $this->log->error('Error trying to fetch user data');
            return $response->withJson(['Error' => $e->getMessage()], 400);
        }
        return $response->withJson(
            $user
                ->with('loans')
                ->where('id', $user_id)
                ->get()
                ->toJson()
        );
    }

    public function allLenders() 
    {

    }

    public function allBorrowes() 
    {

    }
}