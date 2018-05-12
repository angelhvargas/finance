<?php namespace App\Controllers;

use \Slim\Http\Response;
use \Slim\Http\Request;
abstract class Controller 
{

    protected $log;
    protected $model;
    private $view;
    
    public function __construct(\Monolog\Logger $log, Request $request) 
    {
        $this->log = $log;
        $this->request = $request;
    }

    public function index(Request $request, Response $response, $args = []) 
    {
        $this->log->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        $path = explode('/', $this->request->getUri()->getPath())[1];
        $arrparams = $this->request->getParams();
		return $response->write(json_encode($this->model->getAll($path, $arrparams)));   
    }

    public function get(Request $request, Response $response, $id) 
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        $path = explode('/', $request->getUri()->getPath())[1];
        $result = $this->dataaccess->get($path, $args);
        if ($result == null) {
            return $response ->withStatus(404);
        } else {
            return $response->write(json_encode($result));
        }
    }

    public function create(Request $request, Response $response, $args = []) 
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        $path = explode('/', $request->getUri()->getPath())[1];
        $request_data = $request->getParsedBody();
        $last_inserted_id = $this->dataaccess->add($path, $request_data);
        if ($last_inserted_id > 0) {
            $RequesPort = '';
		    if ($request->getUri()->getPort() !== '') {

                $RequesPort = '.'.$request->getUri()->getPort();
                
		    }
            
            $LocationHeader = $request->getUri()->getScheme().'://'.$request->getUri()->getHost().$RequesPort.$request->getUri()->getPath().'/'.$last_inserted_id;
            
            return $response
                ->withHeader('Location', $LocationHeader)
                ->withStatus(201);
        } else {
            return $response ->withStatus(403);
        }
    }

    public function update(Request $request, Response $response, $id, $args = []) 
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        $path = explode('/', $request->getUri()->getPath())[1];
        $request_data = $request->getParsedBody();
        $isupdated = $this->dataaccess->update($path, $args, $request_data);
        if ($isupdated) {
            return $response ->withStatus(200);
        } else {
            return $response ->withStatus(404);
        }
    }

    public function delete(Request $request, Response $response, $id) 
    {
        $this->logger->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        $path = explode('/', $request->getUri()->getPath())[1];
        $isdeleted = $this->dataaccess->delete($path, $args);
        if ($isdeleted) {
            return $response ->withStatus(204);
        } else {
            return $response ->withStatus(404);
        }
    }

}