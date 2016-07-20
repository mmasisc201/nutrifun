<?php
namespace com\appstions\nutrifun\dataAccess;

use com\appstions\nutrifun\constant\Constant;
use com\appstions\nutrifun\helper\TranslateHelper;
use com\appstions\nutrifun\helper\ExceptionHelper;

require_once 'constant/Constant.php';
require_once 'exceptions/DAOException.php';
require_once 'helper/TranslateHelper.php';
require_once 'logger/LoggerService.php';
require_once 'helper/QueryHelper.php';

class DAO {
	//Conexión para producción
	const SERVER_DB = "OPENSHIFT_MYSQL_DB_HOST";
	const SERVER_PORT = "OPENSHIFT_MYSQL_DB_PORT";
	const USER_DB = "OPENSHIFT_MYSQL_DB_USERNAME";
	const PASSWORD_DB = "OPENSHIFT_MYSQL_DB_PASSWORD";
	const NAME_DB = "nutrifun";
	//Conexión para desarrollo
	const SERVER_DB_LOCAL = "localhost";
	const USER_DB_LOCAL = "root";
	const PASSWORD_DB_LOCAL = "";
	const NAME_DB_LOCAL = "nutrifun";
	
	const PRODUCTION = true;
	const DEVELOP = false;
	
	/**
	 * Conexión de la base de datos
	 * @var \PDO
	 */
	private $connection = NULL;
	private $traslate;
	
	public function __construct() {
            try{            	
            	$this->traslate = new TranslateHelper();
                $this->connectDataBase(self::DEVELOP);      
            } catch (\PDOException $ex) {
                ExceptionHelper::log($ex, $this);
				ExceptionHelper::throwException($ex, $this);
            } catch (\Exception $ex) {
                ExceptionHelper::log($ex, $this);
				ExceptionHelper::throwException($ex, $this);
            }		
	}
	
	/**
	 * Crea lo conexión de la base de datos, ya sea en producción o para desarrollo
	 * @param boolean $isProduction
	 * @throws \Exception
	 */
	private function connectDataBase($isProduction) {
		$dsn = '';
		$userName = '';
		$password = '';
                
        try {
                
			if ($isProduction == self::PRODUCTION){
			
				$dsn = 'mysql:dbname=' . self::NAME_DB . ';host=' . getenv(self::SERVER_DB).':'.getenv(self::SERVER_PORT);
				$userName = getenv(self::USER_DB);
				$password = getenv(self::PASSWORD_DB);
				
			}else{
				
				$dsn = 'mysql:dbname=' . self::NAME_DB_LOCAL . ';host=' . self::SERVER_DB_LOCAL;
				$userName = self::USER_DB_LOCAL;
				$password = self::PASSWORD_DB_LOCAL;
			}				
		
            $this->connection = new \PDO($dsn, $userName, $password, array(\PDO::ATTR_PERSISTENT => true));
                
            $this->connection->exec("SET NAMES utf8");

        } catch (\Exception $e){               
            ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
        }
	}
	
	/**
	 * Obtiene la conexión de la base de datos
	 * @return \PDO Conexión de la base de datos
	 */
	public function getConnection(){
		return $this->connection;
	}
	
	/**
	 * Inicica una transaccion
	 */	
	public function beginTransaction(){
		try {
			$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			//
			$this->connection->beginTransaction();
		} catch (\Exception $e) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}

	}
	
	/**
	 * Realiza el commit para una transaccion
	 */	
	public function commitTransaction(){
		try {
			$this->connection->commit();
		} catch (\Exception $e) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}
	
	}
	
	/**
	 * Realiza el rollback de una transaccion
	 */
	public function rollbackTransaction(){
		$this->connection->rollBack();
	}
	
	/**
	 * Obtiene el traductor
	 * @return TranslateHelper
	 */
	public function getTranslate(){
		return $this->traslate;
	}
	
	public function setLanguage($lang){
		$this->traslate->setLanguage($lang);
	}
}

