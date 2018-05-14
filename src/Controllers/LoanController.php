<?php namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Models\Loan;

class LoanController extends Controller
{
    public function loansReport(Request $request, Response $response, $params)
    {
        $loans = Loan::with(['user' => function($query) {
            return $query->where('type','=','borrower');
        }])
            ->with(['bids' => function ($query) {
                return $query
                        ->where('amount', '>', 0);
            }]);
        //get query results as array
        $results = $loans->get()->toArray();
        $data = [];
        foreach($results as $result) {
            $data['loans'][] = $result; 
        }
        
        return $response
            ->withStatus(200)
            ->withJson($data);
    }
}