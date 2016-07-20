<?php
namespace com\appstions\nutrifun\helper;

use com\appstions\nutrifun\service\Rest;
use com\appstions\nutrifun\exceptions\ServiceException;
use com\appstions\nutrifun\exceptions\DAOException;
use com\appstions\nutrifun\dataAccess\DAO;

class ExceptionHelper {
	
	//Constante utilizada para el registro de errores
	const ERROR = 1;
	//Constante utilizada para el registro de información
	const INFO = 2;
	//Identifica al archivo de registro de acciones del DAO
	const DAO_LOGGER = 1;
	//Identifica al aSrchivo de registro de acciones del SERVICE
	const SERVICE_LOGGER = 2;
	//Identifica al archivo de registro de acciones del HELPER
	const HELPER_LOGGER = 3;
	
	/**
	 * Método para el registro en bitacora de excepciones
	 * @param string $message
	 * @param string $code
	 * @param string $className
	 * @param string $level
	 * @param string $loggerType
	 */
	public static function log(\Exception $ex, $class) {
		
		$message = $ex->getMessage();
		$code = $ex->getCode(); 
		$className = $ex->getFile(); 
		$line = $ex->getLine();
		$loggerType = NULL; 
		$level = self::ERROR;
		
		if ($ex instanceof ServiceException || $class instanceof Rest){
			$loggerType = self::SERVICE_LOGGER;
			$level = self::INFO;
		}else if ($ex instanceof DAOException || $class instanceof DAO){
			$loggerType = self::DAO_LOGGER;
			$level = self::INFO;
		} else if($class instanceof Helper){
			$loggerType = self::HELPER_LOGGER;
			$level = self::ERROR;
		}
		
		
		// Inicializar la variable
		$log = new \LoggerService ( \LoggerService::DEFAULT_LOGGER );
		// Definir el nombre de la clase
		$className = ($className != NULL) ? $className : "DEFAULT";
		// Definir el log a utilizar
		switch ($loggerType) :
		case self::DAO_LOGGER :
			$log = new \LoggerService ( \LoggerService::DAO_LOGGER );
			break;
				
		case self::SERVICE_LOGGER :
			$log = new \LoggerService ( \LoggerService::SERVICE_LOGGER );
			break;
				
		case self::HELPER_LOGGER :
			$log = new \LoggerService ( \LoggerService::HELPER_LOGGER );
			break;
				
		default :
			$log = new \LoggerService ( \LoggerService::DEFAULT_LOGGER );
			endswitch
			;
			// Definir el nivel que se desea registrar
			switch ($level) :
		case self::ERROR :
				$level = \LoggerLevel::getLevelError ();
				break;
					
		case self::INFO :
			$level = \LoggerLevel::getLevelInfo ();
			break;
			endswitch
			;
	
			// Realizar registro del error
			$log->log($level, $className, $code, $message, $line);
			
	}
	
	public static function throwException(\Exception $ex, $class){
		
		 $message = $ex->getMessage();
		 $code = $ex->getCode();
		
		 if ($ex instanceof ServiceException || $ex instanceof DAOException){
		 	
			 if($class instanceof Rest && $message != NULL){
			 	$message = $class->getTranslate()->translate($message);
			 }
		 	
		 }
		 
		 if ($ex instanceof ServiceException){
		 	throw new ServiceException($message, $code);
		 }else if ($ex instanceof DAOException){
		 	throw new DAOException($message, $code);
		 } else {
		 	throw $ex;	
		 }
		 
	}
	
}