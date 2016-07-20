<?php
include_once('Logger.php'); 
include_once('LoggerLevel.php');


/**
 * Clase para llecar el control de los logs de la aplicación 
 */
class LoggerService
{
    /** Variable del log */
    private $log;
    /** Constante para los logs**/
    const DAO_LOGGER = "DAOLogger";
    /** Constante para los logs**/
    const SERVICE_LOGGER = "ServiceLogger";
    /** Constante para los logs**/
    const HELPER_LOGGER = "HelperLogger";
    /** Constante para los logs**/
    const DEFAULT_LOGGER = "DefaultLogger";
    /** Constante con la ruta del archivo de configuración**/
    const CONFIGURATION_FILE = './log-configuration.xml';
 
    /** Constructor de la clase */
    public function __construct($logger)
    {		
    	Logger::configure(self::CONFIGURATION_FILE);
    	$this->log = Logger::getLogger($logger);
    }
    
   /**
    *  Método que realiza el registro de la excepción
    *  
    * @param LoggerLevel $level
    * @param Nombre de la clase $class
    * @param Códgo del error $code
    * @param Mensaje de la excepción $message
    */
    public function log($level, $class, $code, $message, $line)
    {
    	$this->log->log($level, '[Class]:'.$class.'[Code]:'.$code.'[Line]'.$line.'[Message]:'.$message);
    }
}
?>