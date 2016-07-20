<?php
namespace com\appstions\nutrifun\service;

interface IComponentService {
	
	const METHOD_NOT_FOUND = "Peticion no encontrada";
	const NO_CONTENT = "Peticion sin contenido";
	const NOT_AUTHENTICATED = "Email o password incorrectos";
	const ARRAY_EMPTY =  "No se encontraron componentes en la busqueda";
	const REQUIRED = "Faltan datos";


	/**
	 * Obtiene la lista de componentes
	 */
	public function getComponents();
	
	/**
	 * Obtiene la lista de componentes utilizando lo ingresado por el usuario
	 */
	public function getComponentsByDescription();
	
}