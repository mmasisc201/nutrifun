<?php
namespace com\appstions\nutrifun\service;

use com\appstions\nutrifun\dataAccess\RecipeDAO;
use com\appstions\nutrifun\entity\Recipe;
use com\appstions\nutrifun\helper\Helper;
use com\appstions\nutrifun\helper\ExceptionHelper;
use com\appstions\nutrifun\exceptions\ServiceException;
use com\appstions\nutrifun\constant\Constant;

require_once 'service/IRecipeService.php';
require_once 'entity/Recipe.php';
require_once 'constant/Constant.php';
require_once 'dataAccess/RecipeDAO.php';
require_once 'helper/Helper.php';

class RecipeService extends Rest implements IRecipeService {
	private $recipeDAO;
	public function __construct() {
		parent::__construct ();
		$this->recipeDAO = new RecipeDAO();
	}
	
	/**
	 * Agrega un usuario al sistema (non-PHPdoc)
	 * 
	 * @see \com\appstions\nutrifun\service\IRecipeService::addRecipe()
	 */
	public function addRecipe() {
		try {
			$this->checkPostRequest ( Rest::DISABLE_AUTHENTICATION );
			
			$header = $this->getRequestBody ()[Rest::HEADER];
			$body = $this->getRequestBody ()[Rest::BODY];
			
			// $countryCode = $header[Rest::COUNTRY];
			
			$recipe = new Recipe();
			// Validar que el nombre de usuario y el email no se encunetren registrados en la base
			$this->unserializeBody ( $body, $recipe );
			if (Helper::validateExistingUser ( $recipe )) {
				// El constructor del padre ya se encarga de setear los datos de entrada
				$inserted = $this->recipeDAO->addRecipe($recipe);
				
				// Se valida el proceso
				if ($inserted == TRUE) {
					$this->processSuccessResponse ( $inserted );
				} else {
					throw new ServiceException ( IRecipeService::RECIPE_NOT_CREATED, Rest::CUSTOM_ERROR_CODE );
			}
			}else{
				throw new ServiceException(IRecipeService::RECIPE_EXIST, Rest::CUSTOM_ERROR_CODE);
			}
			
		} catch ( \Exception $e ) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}
	}

}