<?php
namespace com\appstions\nutrifun\service;

interface IRecipeService {
	
	const METHOD_NOT_FOUND = "Peticion no encontrada";
	const NO_CONTENT = "Peticion sin contenido";
	const REQUIRED = "Faltan datos";
	const RECIPE_NOT_CREATED = "Hubo un error al guardar la receta";
	const RECIPE_EXIST = "La receta ya se encuentra registrada en el sistema";
	
	/**
	 * Agrega una receta al sistema 
	 */
	public function addRecipe();

}