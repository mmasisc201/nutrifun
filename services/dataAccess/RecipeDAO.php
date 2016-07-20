<?php

namespace com\appstions\nutrifun\dataAccess;

use com\appstions\nutrifun\entity\Recipe;
use com\appstions\nutrifun\helper\Helper;
use com\appstions\nutrifun\constant\Constant;
use com\appstions\nutrifun\helper\ExceptionHelper;
use com\appstions\nutrifun\helper\QueryHelper;

require_once 'dataAccess/DAO.php';
require_once 'helper/Helper.php';
require_once 'constant/Constant.php';
class RecipeDAO extends DAO {
	private $dao;
	public function __construct() {
		parent::__construct ();
		$this->dao = new QueryHelper ( 'recipeQueries' );
	}
	
	/**
	 * Agrega una receta al sistema
	 *
	 * @param User $recipe        	
	 */
	public function addRecipe(Recipe $recipe) {
		try {
			
			$sqlQuery = $this->dao->getQuery ( 'addRecipe' );
			
			$query = $this->getConnection ()->prepare ( $sqlQuery );
			
			$query->bindValue ( ":description", $recipe->getDescription() );
			
			$query->execute ();
			
			$updatedRows = $query->rowCount ();
			
			return ($updatedRows == 1);
		} catch ( \Exception $e ) {
			ExceptionHelper::log ( $e, $this );
			ExceptionHelper::throwException ( $e, $this );
		}
	}
	
}