<?php

namespace com\appstions\nutrifun\constant;

/**
 * Contiene constantes a utilizar en el proyecto 
 * @author 
 *
 */
class Constant {
	
	/** Mensajes de registro en el log de los Helper**/
	const METHOD_NOT_FOUND = "Peticion no encontrada";
	
	/** Mensajes de registro en el log de los Service**/
	const METHOD_NOTT_FOUND = "Peticion no encontrada";
	
	/** Mensajes de registro en el log de los DAO**/
	const DAO_ERROR = "Error registrado en el DAO";
	
	/** Define un estado activo para el usuario. Si es la primera vez que ingresa a la aplicaciór**/
	const FIRST_TIME_ACTIVE = "A";
	
	/** Define un estado inactivo para el usuario. Si no es la primera vez que ingresa a la aplicación**/
	const FIRST_TIME_INACTIVE = "I";

	/** Define un estado activo para el login**/
	const LOGIN_STATUS_ACTIVE = "A";
	
	/** Define un estado inactivo para el login**/
	const LOGIN_STATUS_INACTIVE = "I";
	
}