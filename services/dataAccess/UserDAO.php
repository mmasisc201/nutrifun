<?php

namespace com\appstions\nutrifun\dataAccess;

use com\appstions\nutrifun\entity\User;
use com\appstions\nutrifun\helper\Helper;
use com\appstions\nutrifun\constant\Constant;
use com\appstions\nutrifun\helper\ExceptionHelper;
use com\appstions\nutrifun\helper\QueryHelper;

require_once 'dataAccess/DAO.php';
require_once 'helper/Helper.php';
require_once 'constant/Constant.php';
class UserDAO extends DAO {
	private $dao;
	public function __construct() {
		parent::__construct ();
		$this->dao = new QueryHelper ( 'userQueries' );
	}
	
	/**
	 * Agrega un usuario nuevo al sistema
	 *
	 * @param User $user        	
	 */
	public function addUser(User $user) {
		try {
			
			$sqlQuery = $this->dao->getQuery ( 'addUser' );
			
			$query = $this->getConnection ()->prepare ( $sqlQuery );
			
			$query->bindValue ( ":name", $user->getName () );
			// Llamada al método que encripta el password
			$query->bindValue ( ":lastname", $user->getLastName () );
			$query->bindValue ( ":email", $user->getEmail () );
			$query->bindValue ( ":password", $user->getPassword () );
			$query->bindValue ( ":username", $user->getUserName () );
			
			$query->execute ();
			
			$updatedRows = $query->rowCount ();
			
			return ($updatedRows == 1);
		} catch ( \Exception $e ) {
			ExceptionHelper::log ( $e, $this );
			ExceptionHelper::throwException ( $e, $this );
		}
	}
	
	/**
	 * Realiza el login de un usuario en la aplicación 
	 * @param User $user
	 * @return NULL
	 */
	public function login(User $user) {
		try {
			
			$sqlQuery = $this->dao->getQuery ( 'login' );
			
			$query = $this->getConnection ()->prepare ( $sqlQuery );
			
			$query->bindValue ( ":username", $user->getUserName() );
			$query->bindValue ( ":email", $user->getEmail () );
			$query->bindValue ( ":pwd", $user->getPassword () );
			$query->execute ();
			
			$usuerToReturn = NULL;
			if ($fila = $query->fetch ( \PDO::FETCH_ASSOC )) {
				
				$usuerToReturn = new User();
				
				$usuerToReturn->setIdUser($fila['id_user']);
				$usuerToReturn->setName($fila['name']);
				$usuerToReturn->setLastName($fila['lastname']);
				$usuerToReturn->setEmail($fila['email']);
				$usuerToReturn->setUserName($fila['username']);
		
			}
			
			return $usuerToReturn;
		} catch ( \Exception $e ) {
			ExceptionHelper::log ( $e, $this );
			ExceptionHelper::throwException ( $e, $this );
		}
	}
	
	/**
	 * Devuelve la información asociada a un usuario
	 * @param unknown $idUser
	 * @return User
	 */
	public function getUser($idUser){
		
		$usuerToReturn = NULL;
	
		try {
	
			$sqlQuery = $this->dao->getQuery('getUser');
			 
			$query = $this->getConnection()->prepare($sqlQuery);
	
			$query->bindValue(":id_user", $idUser);
			$query->execute();
	
			if ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
	
				$usuerToReturn = new User();
				
				$usuerToReturn->setIdUser($fila['id_user']);
				$usuerToReturn->setName($fila['name']);
				$usuerToReturn->setLastName($fila['lastname']);
				$usuerToReturn->setEmail($fila['email']);
				$usuerToReturn->setUserName($fila['username']); 		   
			}
	
		} catch (\Exception $e) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}
			
		return $usuerToReturn;
	}
	
	/**
	 * Obtiene los datos del usuario realizando la búsqueda por el nombre de usuario que se envía por parámetro
	 * @param int $userName
	 */
	public function getUserByUserName($userName){
	
		$isValidUser = TRUE;
	
		try {
	
			$sqlQuery = $this->dao->getQuery('getUserByUserName');
	
			$query = $this->getConnection()->prepare($sqlQuery);
	
			$query->bindValue(":username", $userName);
			$query->execute();
	
			if ($query->rowCount() > 0) {
				$isValidUser = FALSE;
					
			}
	
		} catch (\Exception $e) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}
			
		return $isValidUser;
	}
	
	/**
	 * Obtiene los datos del usuario realizando la búsqueda por un email que se envía por parámetro
	 * @param int $email
	 */
	public function getUserByEmail($email){
	
		$isValidUser = TRUE;
	
		try {
	
			$sqlQuery = $this->dao->getQuery('getUserByEmail');
	
			$query = $this->getConnection()->prepare($sqlQuery);
	
			$query->bindValue(":email", $email);
			$query->execute();
	
			if ($query->rowCount() > 0) {
				$isValidUser = FALSE;
					
			}
	
		} catch (\Exception $e) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}
			
		return $isValidUser;
	}
}