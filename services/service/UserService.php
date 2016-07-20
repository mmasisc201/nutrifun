<?php
namespace com\appstions\nutrifun\service;

use com\appstions\nutrifun\dataAccess\UserDAO;
use com\appstions\nutrifun\entity\User;
use com\appstions\nutrifun\helper\Helper;
use com\appstions\nutrifun\helper\ExceptionHelper;
use com\appstions\nutrifun\exceptions\ServiceException;
use com\appstions\nutrifun\constant\Constant;

require_once 'service/IUserService.php';
require_once 'entity/User.php';
require_once 'constant/Constant.php';
require_once 'dataAccess/UserDAO.php';
require_once 'helper/Helper.php';

class UserService extends Rest implements IUserService {
	private $userDAO;
	public function __construct() {
		parent::__construct ();
		$this->userDAO = new UserDAO ();
	}
	
	/**
	 * Agrega un usuario al sistema (non-PHPdoc)
	 * 
	 * @see \com\appstions\nutrifun\service\IUserService::addUser()
	 */
	public function addUser() {
		try {
			$this->checkPostRequest ( Rest::DISABLE_AUTHENTICATION );
			
			$header = $this->getRequestBody ()[Rest::HEADER];
			$body = $this->getRequestBody ()[Rest::BODY];
			
			// $countryCode = $header[Rest::COUNTRY];
			
			$user = new User ();
			// Validar que el nombre de usuario y el email no se encunetren registrados en la base
			$this->unserializeBody ( $body, $user );
			if (Helper::validateExistingUser ( $user )) {
				// El constructor del padre ya se encarga de setear los datos de entrada
				$inserted = $this->userDAO->addUser ( $user );
				
				// Se valida el proceso
				if ($inserted == TRUE) {
					$this->processSuccessResponse ( $inserted );
				} else {
					throw new ServiceException ( IUserService::PLAYER_NOT_CREATED, Rest::CUSTOM_ERROR_CODE );
			}
			}else{
				throw new ServiceException(IUserService::PLAYER_EXIST, Rest::CUSTOM_ERROR_CODE);
			}
			
		} catch ( \Exception $e ) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}
	}
	
	/* (non-PHPdoc)
	 * @see \Service\IPlayerService::login()
	*/
	public function login() {
	
		try {
			//Deshabilitar la autenticación
			$this->checkPostRequest(Rest::DISABLE_AUTHENTICATION);
				
			$header = $this->getRequestBody()[Rest::HEADER];
			$body = $this->getRequestBody()[Rest::BODY];
				
			//$countryCode = $header[Rest::COUNTRY];
				
			$user = new User();
			$this->unserializeBody($body, $user);
				
			//Se obtiene la información del usuario
			$data = $this->userDAO->login($user);
				
			if($data != NULL){
				$this->processSuccessResponse($data);
			} else {
				throw new ServiceException(IUserService::NOT_AUTHENTICATED, Rest::CUSTOM_ERROR_CODE);
			}
	
		} catch ( \Exception $e ) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}
	
	}

}