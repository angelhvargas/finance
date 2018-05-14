<?php namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\User;

class LenderController extends Controller
{
    public function index(Request $request, Response $response)
    {

    }

    public function find(Request $request, Response $response, $attrs)
    {
        $lender_id = isset($attrs['id']) ? $attrs['id'] : false;

        try {
            
            $lender = User::where('id','=', $lender_id)->with('transactions');
            if (!$lender_id) throw new Exception("id parameter missing");


            var_dump($lender->get()->toArray());

        } catch(\Exception $e) {
            $this->log->error($e->getMessage());
        }
    }
}