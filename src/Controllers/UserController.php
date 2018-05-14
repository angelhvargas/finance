<?php namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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
    public function create(Request $request, Response $response) 
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

    public function withLoans(Request $request, Response $response, $params) 
    {
        
        var_dump(User::lenders());
        die();
        try {
            if ( isset($params['id']) ) {
                $user_id = $params['id'];
                $user_with_loans = User::find($user_id)->withLoans();
                var_dump($user_with_loans);
            } else {
                throw new \RuntimeException('missing parameter id');
            }

        } catch(\Exception $e) {
            $this->log->error($e->getMessage());
        }
    }

    public function allLenders() 
    {

    }

    public function allBorrowes() 
    {

    }
}