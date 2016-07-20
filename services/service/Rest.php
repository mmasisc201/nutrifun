<?php
namespace com\appstions\nutrifun\service;

use com\appstions\nutrifun\exceptions\ServiceException;
use com\appstions\nutrifun\helper\Helper;
use com\appstions\nutrifun\helper\TranslateHelper;
use com\appstions\nutrifun\helper\ExceptionHelper;
use com\appstions\nutrifun\entity\JsonUnserializable;

require_once 'exceptions/ServiceException.php';
require_once 'helper/Helper.php';
require_once 'helper/TranslateHelper.php';
require_once 'helper/ExceptionHelper.php';

class Rest{

	const DEFAULT_STATUS =  200;
	const STATUS_BAD_REQUEST =  400;
	const STATUS_UNAUTHORIZED =  401;
	const STATUS_FORBIDEN =  403;
	const STATUS_NOT_FOUND =  404;
	const STATUS_METHOD_NOT_ALLOWED =  405;
	const STATUS_OK = 200;
	const STATUS_ERROR = 500;
	const CUSTOM_ERROR_CODE = 700;
	
	const APPLICATION_JSON = "application/json";
	const DEFAULT_TYPE =  "application/json";
	
	const SUCCESS =  'success';
	const FAIL =  'fail';
	
	const HEADER = "header";
	const BODY = "body";
	const TOKEN = "token";
	const COUNTRY = "country";
	const LANGUAGE = "language";
	const ID_PLAYER = "key";
	const STATUS = "status";
	const ERROR_CODE = "errorCode";
	const ERROR_MESSAGE = "message";
	
	const DISABLE_AUTHENTICATION = TRUE;
	
	const SERVER_ERROR =  "Hubo un error en el sistema";
	const REQUEST_REQUIRED = "Faltan datos";
	
	private $responseContentType = self::DEFAULT_TYPE;
	private $requestBody = array();
	private $responseStatus = self::DEFAULT_STATUS;
	private $lang = NULL;
	private $translate = NULL; 
	
	public function __construct(){
		$this->traslate = new TranslateHelper();
		$this->processInput();
	}
	
	public function sendResponse($data, $status){
		$this->responseStatus = ($status)? $status : self::DEFAULT_STATUS;
		$this->setHeader();
		echo $data;
		
		exit;
	}
	
	public function getRequestBody(){
		return $this->requestBody;
	}
	
	private function setHeader(){
		header("HTTP/1.1 " . $this->responseStatus . " " . $this->getCodeStatus());
		header("Content-Type:" . $this->responseContentType . ';charset=utf-8');
	}
	
	private function cleanInput($data){
		$input = array();
		if(is_array($data)){
			foreach ($data as $key => $value) {
				
				$input[$key] = $this->cleanInput($value);
				
			}
		} else {
			
			if (get_magic_quotes_gpc()){
				//Quitamos las barras de un string con comillas escapadas
				//Aunque actualmente se desaconseja su uso, muchos servidores tienen activada la extensión magic_quotes_gpc.
				//Cuando esta extensión está activada, PHP añade automáticamente caracteres de escape (\) delante de las comillas 
				//que se escriban en un campo de formulario.
				$data = trim(stripslashes($data));
			}
			//eliminamos etiquetas html y php 
			$data = strip_tags($data);
			//Convertimos todos los caracteres aplicables a entidades HTML
			$data = htmlentities($data);
			$input = trim($data);
		}
		return $input;
	}
	
	private function processInput(){
		$method = $_SERVER['REQUEST_METHOD'];
		
		$contentType = NULL;
		$isJsonData = FALSE;
		
		if(isset($_SERVER['CONTENT_TYPE'])){
			$contentType = $_SERVER['CONTENT_TYPE'];
		}
		
		if (strpos($contentType, self::APPLICATION_JSON) !== false) {	
			$isJsonData = TRUE;
		}
		
		switch ($method	) {
			case "GET":
				$this->requestBody = $this->cleanInput($_GET);
			break;
			case "POST":
				
				if ($isJsonData){
					
					$json = file_get_contents("php://input");
					$data = strip_tags($json);
						
					$jsonDecoded = json_decode(trim($data), TRUE);
						
					$this->requestBody = $this->cleanInput($jsonDecoded);
					
				}else {
					$this->requestBody = $this->cleanInput($_POST);
				} 
					
			break;
			case "DELETE":
			case "PUT":
				//php no tiene un método propiamente dicho para leer una petición PUT o DELETE por lo que se usa un "truco":
				//leer el stream de entrada file_get_contents("php://input") que transfiere un fichero a una cadena.
				//Con ello obtenemos una cadena de pares clave valor de variables (variable1=dato1&variable2=data2...)
				//que evidentemente tendremos que transformarla a un array asociativo.
				//Con parse_str meteremos la cadena en un array donde cada par de elementos es un componente del array.
				parse_str(file_get_contents("php://input"), $this->requestBody);
				$this->requestBody = $this->cleanInput($this->requestBody);
			break;
			default:
				$this->response('', self::STATUS_NOT_FOUND);
			break;
		}
	}
	
	private function getCodeStatus() {
		$status = array(
				self::STATUS_OK => 'OK',
				self::STATUS_ERROR => 'ERROR',
				self::STATUS_BAD_REQUEST => 'Bad Request',
				self::STATUS_UNAUTHORIZED => 'Unauthorized',
				self::STATUS_FORBIDEN => 'Forbidden',
				self::STATUS_NOT_FOUND => 'Not Found',
				self::STATUS_METHOD_NOT_ALLOWED => 'Method Not Allowed',
				self::STATUS_ERROR => 'Internal Server Error');
		$response = ($status[$this->responseStatus]) ? $status[$this->responseStatus] : $status[STATUS_INTERNAL_SERVER_ERROR];
		
		return $response;
	}
	
	private function convertToJson($data) {
		return json_encode($data);
	}
	
	/**
	 * Crea la respuesta en estado exitoso
	 * @param string $country Codigo de Pais
	 * @param string $data Datos que se quieren retornar
	 * @param string $token número de token generado 
	 */
	private function createSuccessResponseLogin($country, $data = '', $token){
		$response = array();
		
		$response[self::HEADER][self::TOKEN] = $token;
		$response[self::HEADER][self::COUNTRY] = $country;
		$response[self::HEADER][self::STATUS] = self::SUCCESS;
		$response[self::HEADER][self::ERROR_CODE] = '';
		$response[self::HEADER][self::ERROR_MESSAGE] = '';
		
		$response[self::BODY] = $data;
		return $this->convertToJson($response);
	}
	
	/**
	 * Crea la respuesta en estado exitoso
	 * @param string $country Codigo de Pais
	 * @param string $data Datos que se quieren retornar
	 */
	/*private function createSuccessResponse($country, $data = ''){
		$response = array();
	
		$response[self::HEADER][self::COUNTRY] = $country;
		$response[self::HEADER][self::STATUS] = self::SUCCESS;
		$response[self::HEADER][self::ERROR_CODE] = '';
		$response[self::HEADER][self::ERROR_MESSAGE] = '';
	
		$response[self::BODY] = $data;
		return $this->convertToJson($response);
	}*/
	
	/**
	 * Crea la respuesta en estado exitoso
	 * @param string $country Codigo de Pais
	 * @param string $data Datos que se quieren retornar
	 */
	private function createSuccessResponse($data = ''){
		$response = array();
	
		$response[self::HEADER][self::STATUS] = self::SUCCESS;
		$response[self::HEADER][self::ERROR_CODE] = '';
		$response[self::HEADER][self::ERROR_MESSAGE] = '';
	
		$response[self::BODY] = $data;
		return $this->convertToJson($response);
	}
	
	/**
	 * Crea la respuesta para los errores
	 * @param string $country Codigo de Pais
	 * @param string $errorCode Codigo del error
	 * @param string $message Mensaje del error
	 */
	/*private function createErrorResponse($country, $errorCode = '', $message){
		$response = array();
	
		$response[self::HEADER][self::COUNTRY] = $country;
		$response[self::HEADER][self::STATUS] = self::FAIL;
		$response[self::HEADER][self::ERROR_CODE] = $errorCode;
		$response[self::HEADER][self::ERROR_MESSAGE] = $message;
	
		$response[self::BODY] = '';
		return $this->convertToJson($response);
	}*/
	
	/**
	 * Crea la respuesta para los errores
	 * @param string $country Codigo de Pais
	 * @param string $errorCode Codigo del error
	 * @param string $message Mensaje del error
	 */
	/*private function createErrorResponse($country, $errorCode = '', $message){
		$response = array();
	
		$response[self::HEADER][self::STATUS] = self::FAIL;
		$response[self::HEADER][self::ERROR_CODE] = $errorCode;
		$response[self::HEADER][self::ERROR_MESSAGE] = $message;
	
		$response[self::BODY] = '';
		return $this->convertToJson($response);
	}*/
	
	/**
	 * Crea la respuesta para los errores
	 * @param string $country Codigo de Pais
	 * @param string $errorCode Codigo del error
	 * @param string $message Mensaje del error
	 */
	private function createErrorResponse($errorCode = '', $message){
		$response = array();
	
		$response[self::HEADER][self::STATUS] = self::FAIL;
		$response[self::HEADER][self::ERROR_CODE] = $errorCode;
		$response[self::HEADER][self::ERROR_MESSAGE] = $message;
	
		$response[self::BODY] = '';
		return $this->convertToJson($response);
	}
	
	/**
	 * Revisa si los parametros estan bien tipados y si viene por POST
	 * @param boolean $disableAuthentication se modifica a TRUE si se quiere deshabilitar 
	 * la autentificación de usuarios para cuando sea necesario
	 * @throws ServiceException
	 */
	protected function checkPostRequest($disableAuthentication = TRUE){
		if ($_SERVER['REQUEST_METHOD'] != "POST") {
			throw new ServiceException('petición no aceptada', self::STATUS_METHOD_NOT_ALLOWED);
		}
		
		if(!isset($this->requestBody)){
			throw new ServiceException('faltan los parametros', self::STATUS_BAD_REQUEST);
		}
			
		if(!isset($this->requestBody[self::HEADER])){
			throw new ServiceException('falta el header', self::STATUS_BAD_REQUEST);
		}
			
		if(!isset($this->requestBody[self::BODY])){
			throw new ServiceException('falta el body', self::STATUS_BAD_REQUEST);
		}
		
		if (is_array($this->requestBody[self::HEADER]) == FALSE) {
			throw new ServiceException('faltan datos en el header', self::STATUS_BAD_REQUEST);
		}else if ( is_array($this->requestBody[self::BODY]) == FALSE){
			throw new ServiceException('faltan datos en el body', self::STATUS_BAD_REQUEST);
		}
		
		/*if(!isset($this->requestBody[self::HEADER][self::COUNTRY])){
			throw new ServiceException('falta el código de pais', self::STATUS_BAD_REQUEST);
		}*/
		/*
		if(isset($this->requestBody[self::HEADER][self::LANGUAGE])){
			$this->lang = $this->requestBody[self::HEADER][self::LANGUAGE];
		} else {
			$this->lang = TranslateHelper::DEFAULT_LANGUAGE;
		}*/
		$this->lang = TranslateHelper::DEFAULT_LANGUAGE;
		if(!$disableAuthentication){
			$this->validateAuthentication();
		}
			
		
	}
	
	/**
	 * Valida que los datos de login del usuario sean correctos
	 * @throws ServiceException
	 */
	private function validateAuthentication(){
		//Se valida si viene el id del usario
		if(!isset($this->requestBody[self::HEADER][self::ID_PLAYER])){
			throw new ServiceException('No se ha logueado en el sistema', self::STATUS_UNAUTHORIZED);
		}
		//Se valida si viene el token generado para el usuario
		if(!isset($this->requestBody[self::HEADER][self::TOKEN])){
			throw new ServiceException('No se ha logueado en el sistema', self::STATUS_UNAUTHORIZED);
		}
		
		//Valida los datos el id del usuario y el token comparandolos con los registros en la base de datos
		$idPlayer = $this->requestBody[self::HEADER][self::ID_PLAYER];
		$token = $this->requestBody[self::HEADER][self::TOKEN];
		$validated = Helper::validateToken($idPlayer, $token);
			
		if (!$validated){
			throw new ServiceException('No se ha logueado en el sistema', self::STATUS_UNAUTHORIZED);
		}
		
	}
	
	/**
	 * Procesa el mensaje de exito para el login
	 * @param string $countryCode
	 * @param object $data
	 * @param string $token
	 */
	public function processSuccessLogin($countryCode, $data, $token){
		$response = $this->createSuccessResponseLogin($countryCode, $data, $token);
		$this->sendResponse($response, self::STATUS_OK);
	}
	
	/**
	 * Procesa el mensaje de exito
	 * @param string $countryCode
	 * @param object $data
	 */
	/*public function processSuccessResponse($countryCode, $data){
		$response = $this->createSuccessResponse($countryCode, $data);
		$this->sendResponse($response, self::STATUS_OK);
	}*/
	
	/**
	 * Procesa el mensaje de exito
	 * @param string $countryCode
	 * @param object $data
	 */
	public function processSuccessResponse($data){
		$response = $this->createSuccessResponse($data);
		$this->sendResponse($response, self::STATUS_OK);
	}
	
	/**
	 * Procesa el mensaje de error
	 * @param string $country
	 * @param string $errorCode
	 * @param string $message
	 */
	/*public function processErrorResponse($country, $errorCode, $message){
		
		$response = $this->createErrorResponse($country, $errorCode, $message);
		$this->sendResponse($response, self::STATUS_OK);
	}*/
	
	/**
	 * Procesa el mensaje de error
	 * @param string $country
	 * @param string $errorCode
	 * @param string $message
	 */
	public function processErrorResponse($errorCode, $message){
	
		$response = $this->createErrorResponse($errorCode, $message);
		$this->sendResponse($response, self::STATUS_OK);
	}
	
	public function getLanguage(){
		return $this->lang;
	}
	
	/**
	 * Obtiene el traductor
	 * @return TranslateHelper
	 */
	public function getTranslate(){
		return $this->traslate;
	}
	
	/**
	 * Convierte un objeto json a un Entity
	 * @param array $body
	 * @param JsonUnserializable $target
	 * @throws ServiceException
	 */
	public function unserializeBody($body, JsonUnserializable $target){
		
		if($target != NULL){
			if(!$target->jsonUnserialize($body)){	
				throw new ServiceException(self::REQUEST_REQUIRED, self::STATUS_BAD_REQUEST);
			}
		} else {
			throw new ServiceException(self::REQUEST_REQUIRED, self::STATUS_BAD_REQUEST);
		}
	}
}