<?php namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class Controller 
{
    /**
     * Logger
     *
     * @var \Monolog\Log
     */
    protected $log;
    
    /**
     * Eloquent Model
     *
     * @var \Illuminate\Eloquent
     */
    protected $model;
    
    /**
     * Container application
     *
     * @var \Slim\Container
     */
    protected $app;
    protected $view;
    
    /**
     * Controller constructor
     *
     * @param \Slim\Container $app
     */
    public function __construct(\Slim\Container $app) 
    {
        $this->log = $app['logger'];
        $this->request = $app['request'];
        $this->app = $app;
        $this->view = $app['view'];
    }

    /**
     * Default GET handler
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function index(Request $request, Response $response, $args = []) 
    {
        $this->log->info(substr(strrchr(rtrim(__CLASS__, '\\'), '\\'), 1).': '.__FUNCTION__);
        $path = explode('/', $this->request->getUri()->getPath())[1];
        $arrparams = $this->request->getParams();

		return $response->write(json_encode($this->model->getAll($path, $arrparams)));   
    }

    /**
     * Default Http => GET route/{value} handler
     *
     * @param Request $request
     * @param Response $response
     * @param int $id
     * @return Response
     */
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

    /**
     * Default Http => POST route handler
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
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

    public function __invoke($actionName)
    {
        $app = $this->app;
        $controller = $this;
        $callable = function ($request, $response, $args) use ($app, $controller, $actionName) {
            $container = $app->getContainer();
            if (method_exists($controller, 'setRequest')) {
                $controller->setRequest($request);
            }
            if (method_exists($controller, 'setResponse')) {
                $controller->setResponse($response);
            }
            if (method_exists($controller, 'init')) {
                $controller->init();
            }
            // store the name of the controller and action so we can assert during tests
            $controllerName = get_class($controller); // eg. CrSrc\Controller\Admin\ArticlesController
            $controllerName = strtolower($controllerName); // eg. crsrc\controller\admin\articlescontroller
            $controllerNameParts = explode('\\', $controllerName);
            $controllerName = array_pop($controllerNameParts); // eg. articlescontroller
            preg_match('/(.*)controller$/', $controllerName, $result); // eg. articles?
            $controllerName = $result[1];
            // these values will be useful when testing, but not included with the
            // Slim\Http\Response. Instead use SlimMvc\Http\Response
            if (method_exists($response, 'setControllerName')) {
                $response->setControllerName($controllerName);
            }
            if (method_exists($response, 'setControllerClass')) {
                $response->setControllerClass(get_class($controller));
            }
            if (method_exists($response, 'setActionName')) {
                $response->setActionName($actionName);
            }
            return call_user_func_array(array($controller, $actionName), $args);
        };
        return $callable;
    }
    
    /**
     * Set Slim\Http\Request
     *
     * @param Request $request
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Set Response attribute
     *
     * @param \Slim\Http\Response $response
     * @return void
     */
    public function setResponse(\Slim\Http\Response $response)
    {
        $this->response = $response;
        return $this;
    }
    /**
     * Render the view from within the controller
     * @param string $file Name of the template/ view to render
     * @param array $args Additional variables to pass to the view
     * @param Response?
     * TODO should this be here?
     */
    protected function render($file, $args=array())
    {
        $container = $this->app->getContainer();
        // return $container->renderer->render($this->response, $file, $args);
        return $container->view->render($this->response, $file, $args);
    }
    /**
     * Return true if XHR request
     */
    protected function isXhr()
    {
        return $this->request->isXhr();
    }
    /**
     * Get the POST params
     */
    protected function getPost()
    {
        $post = array_diff_key($this->request->getParams(), array_flip(array(
            '_METHOD',
        )));
        return $post;
    }
    /**
     * Get the POST params
     * @param string $name
     */
    protected function getQueryParam($name, $default=null)
    {
        return $this->request->getQueryParam($name, $default);
    }
    /**
     * Get the POST params
     */
    protected function getQueryParams()
    {
        return $this->request->getQueryParams();
    }
    /**
     * Shorthand method to get dependency from container
     * @param $name
     * @return mixed
     */
    protected function getInstance($name)
    {
        return $this->app->getContainer()->get($name);
    }
    /**
     * Redirect.
     *
     * Note: This method is not part of the PSR-7 standard.
     *
     * This method prepares the response object to return an HTTP Redirect
     * response to the client.
     *
     * @param  string|UriInterface $url    The redirect destination.
     * @param  int                 $status The redirect HTTP status code.
     * @return self
     */
    protected function redirect($url, $status = 302)
    {
        return $this->response->withRedirect($url, $status);
    }

    /**
     * Pass on the control to another action. Of the same class (for now)
     *
     * @param  string $actionName The redirect destination.
     * @param array $data
     * @return Controller
     * @internal param string $status The redirect HTTP status code.
     */
    public function forward($actionName, $data=array())
    {
        // update the action name that was last used
        if (method_exists($this->response, 'setActionName')) {
            $this->response->setActionName($actionName);
        }
        return call_user_func_array(array($this, $actionName), $data);
    }

}