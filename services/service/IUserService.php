<?php
namespace com\appstions\nutrifun\service;

interface IUserService {
	
	const METHOD_NOT_FOUND = "Peticion no encontrada";
	const NO_CONTENT = "Peticion sin contenido";
	const NOT_AUTHENTICATED = "Email o password incorrectos";
	const REQUIRED = "Faltan datos";
	const PLAYER_NOT_UPDATED = "Hubo un error a la hora de actualizar los datos del usuario";
	const PLAYER_NOT_CREATED = "Hubo un error a la hora de crear el usuario";
	const PLAYER_NOT_DELETED = "Hubo un error a la hora de borrar el usuario";
	const PLAYER_EXIST = "EL usuario ya existe";
	const PLAYER_NOT_EXIST = "El jugador no existe";
	

	/**
	 * Agrega un usuario
	 */
	public function addUser();
	
	/**
	 * Realiza el proceso de login de un usuario en el sistema
	 */
	public function login();
}