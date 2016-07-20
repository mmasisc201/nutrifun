<?php
use com\appstions\nutrifun\service\Rest;
use com\appstions\nutrifun\exceptions\DAOException;
use com\appstions\nutrifun\exceptions\ServiceException;

require_once 'service/Rest.php';
require_once 'service/UserService.php';
require_once 'service/ComponentService.php';
require_once 'exceptions/ServiceException.php';
require_once 'exceptions/DAOException.php';

class Api {
	
	const NAMESPACE_ROOT = "com\appstions\\nutrifun\service";
	const REQUEST_NOT_FOUND = "petición no encontrada";
	
	private $class;
	private $method;
	private $arguments;
	
	public function __construct() {
	}
	
	private function getClass($className){
		
		$route = NULL;
		
		$routes = array(
                    'user' => self::NAMESPACE_ROOT . '\UserService',
				    'recipe' => self::NAMESPACE_ROOT . '\RecipeService',
					'component' => self::NAMESPACE_ROOT . '\ComponentService'
		);
		
		if(array_key_exists($className, $routes)){
        	$route = ($routes[$className]) ? $routes[$className] : NULL;
		}
		
		return $route;
	}
	
	private function instanceClass($class){
            $newInstance = NULL;
            try {
			
                $className = $this->getClass($class);
                if($className != NULL && ((int) class_exists($className, true) > 0)){
                    $newInstance = new $className();
                }
                return $newInstance;
                
            } catch (\Exception $e) {
                throw $e;
            }
            
	}
	
	public function processRequest() {
	
            try {
                $rest = new Rest();
                if (isset($_REQUEST['url'])) {
                    //si por ejemplo pasamos explode('/','////controller///method////args///') el resultado es un array con elem vacios;
                    //Array ( [0] => [1] => [2] => [3] => [4] => controller [5] => [6] => [7] => method [8] => [9] => [10] => [11] => args [12] => [13] => [14] => )
                    $url = explode('/', trim($_REQUEST['url']));
            
                    //con array_filter() filtramos elementos de un array pasando función callback, que es opcional.
                    //si no le pasamos función callback, los elementos false o vacios del array serán borrados
                    //por lo tanto la entre la anterior función (explode) y esta eliminamos los '/' sobrantes de la URL
                    $url = array_filter($url);
					
                    $this->class = $this->instanceClass(strtolower(array_shift($url)));
                   
                    $this->method = strtolower(array_shift($url));

                    $this->arguments = $url;

                    $func = $this->method;

                    if ( $this->class != NULL && ((int) method_exists($this->class, $func) > 0)) {

                        if (count($this->arguments) > 0) {
                                call_user_func_array(array($this->class, $this->method), $this->arguments);
                        } else {//si no lo llamamos sin argumentos, al metodo del controlador
                                call_user_func(array($this->class, $this->method));
                        }

                    } else {
                        
                        $rest->processErrorResponse(Rest::STATUS_METHOD_NOT_ALLOWED, self::REQUEST_NOT_FOUND);
                    }
				}
				
				$rest->processErrorResponse(Rest::STATUS_METHOD_NOT_ALLOWED, self::REQUEST_NOT_FOUND);
                
            } catch (ServiceException $e) {				
				$rest->processErrorResponse($e->getCode(), $e->getMessage());
			} catch (DAOException $e) {				
				$rest->processErrorResponse($e->getCode(), $e->getMessage());
			} catch (\Exception $exc) {                                
				$rest->processErrorResponse(Rest::STATUS_ERROR, Rest::SERVER_ERROR);
            }          
	}
	
}

$api = new Api();
$api->processRequest();
