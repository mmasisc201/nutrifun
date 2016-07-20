<?php
namespace com\appstions\nutrifun\helper;

use com\appstions\nutrifun\dataAccess\UserDAO;
use com\appstions\nutrifun\entity\User;
use com\appstions\nutrifun\exceptions\DAOException;
use com\appstions\nutrifun\service\Rest;
use com\appstions\nutrifun\exceptions\ServiceException;
use com\appstions\nutrifun\constant\Constant;
use com\appstions\nutrifun\helper\ExceptionHelper;

require_once 'dataAccess/UserDAO.php';
require_once 'exceptions/DAOException.php';
require_once 'entity/User.php';
require_once 'logger/LoggerService.php';

final class Helper{ 
	
	//Constante para almacenar la ruta en que se guardaran las imagenes de los equipos
	const IMAGE_STORAGE_PATH_EQUIPMENT = "../resources/images/team/";
	//Constante para almacenar la ruta en que se guardaran las imagenes de los jugadores 
	const IMAGE_STORAGE_PATH_PLAYER = "../resources/images/player/";
	//Otorga permisos de escritura para el proceso de almacenamiento de la imagen
	const WRITABLE_PERMISSION = "w";
	
	//Signo de punto 
	const POINT = ".";
	//Raiz para generar el nombre de la imagen del equipo 
	const TEAM_NAME_BASE = "IMGTEAM_";
	//Raiz para generar el nombre de la imagen del jugador
	const PLAYER_NAME_BASE = "IMGPLAYER_";
	//Tipo de imagen para equipos 
	const TEAM_TYPE = "T";
	//Cosntante para el valor mínimo 
	const MIN_VALUE = 1;
	//Constante para el valor máximo
	const MAX_VALUE = 9999999;
	
	const DATE_FORMAT = 'd.m.Y';
	const MYSQL_DATE_FORMAT = 'Y-m-d H:i:s';
	const INDEX_DAY = 0;
	const INDEX_MONTH = 1;
	const INDEX_YEAR = 2;
	
	/**
	 * Valida la existencia de un usuario preregistrado en la base de datos
	 * @param User $user
	 */
	public static function validateExistingUser(User $user){
		$userDAO = new UserDAO();
		//Determina si el usuario a insertar es valido o no
		$isValidUser = TRUE;
		try {
			//Se valida si el usuario existe realizando una busqueda por nombre
			$isValidUserByName = $userDAO->getUserByUserName($user->getUserName());
			//Se valida si el usuario existe realizando una busqueda por email
			$isValidUserByEmail = $userDAO->getUserByEmail($user->getEmail());
			//Validar que el usuario sea nulo
			if(!$isValidUserByName || !$isValidUserByEmail){
				$isValidUser = FALSE;
			}
		} catch ( \Exception $e ) {
			ExceptionHelper::log($e, self);
			ExceptionHelper::throwException($e, self);
		}
	
		return $isValidUser;
	}
	
}
