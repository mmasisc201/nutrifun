<?php

namespace com\appstions\nutrifun\dataAccess;

use com\appstions\nutrifun\entity\Component;
use com\appstions\nutrifun\helper\Helper;
use com\appstions\nutrifun\constant\Constant;
use com\appstions\nutrifun\helper\ExceptionHelper;
use com\appstions\nutrifun\helper\QueryHelper;
use com\appstions\nutrifun\entity\Category;
use com\appstions\nutrifun\entity\Measurement;

require_once 'dataAccess/DAO.php';
require_once 'helper/Helper.php';
require_once 'constant/Constant.php';

class ComponentDAO extends DAO {
	
	private $dao;
	
	public function __construct() {
		parent::__construct ();
		$this->dao = new QueryHelper ( 'componentQueries' );
	}
	
	/**
	 * Devuelve la información asociada a un usuario
	 * @param unknown $idUser
	 * @return User
	 */
	public function getUser($idUser){
	
		$usuerToReturn = NULL;
	
		try {
	
			$sqlQuery = $this->dao->getQuery('getUser');
	
			$query = $this->getConnection()->prepare($sqlQuery);
	
			$query->bindValue(":id_user", $idUser);
			$query->execute();
	
			if ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
	
				$usuerToReturn = new User();
	
				$usuerToReturn->setIdUser($fila['id_user']);
				$usuerToReturn->setName($fila['name']);
				$usuerToReturn->setLastName($fila['lastname']);
				$usuerToReturn->setEmail($fila['email']);
				$usuerToReturn->setUserName($fila['username']);
			}
	
		} catch (\Exception $e) {
			ExceptionHelper::log($e, $this);
			ExceptionHelper::throwException($e, $this);
		}
			
		return $usuerToReturn;
	}
	
	/**
	 * Obtiene la lista de componentes
	 */
	public function getComponents() {
		
		try {
			
			$components = array ();
			
			$sqlQuery = $this->dao->getQuery('getComponents');
			
			$query = $this->getConnection ()->prepare ( $sqlQuery );
			
			$query->execute();
			
			$rows = $query->fetchAll (\PDO::FETCH_ASSOC );
			
			foreach ( $rows as $row ) {
				
				$component = new Component ();
				$category = new Category ();
				$measurementUnit = new Measurement ();
				//
				$component->setIdComponent ( $row ['id_component'] );
				$component->setDescription ( $row ['description'] );
				// Categoría
				$category->setIdCategory ( $row ['id_category'] );
				$component->setCategory ( $category );
				//
				$measurementUnit->setIdUnit ( $row ['id_unit'] );
				$component->setMeasurementUnit ( $measurementUnit );
				
				array_push ( $components, $component );
			}
		} catch ( \Exception $e ) {
			ExceptionHelper::log ( $e, $this );
			ExceptionHelper::throwException ( $e, $this );
		}
		
		return $components;
	}
	
	/**
	 * Obtiene la lista de componentes utilizando lo ingresado por el usuario 
	 */
	public function getComponentsByDescription($description) {
	
		try {
			
			$description = "%".$description."%";
			
			$components = array ();
				
			$sqlQuery = $this->dao->getQuery('getComponentsByDescription');
				
			$query = $this->getConnection ()->prepare ( $sqlQuery );
			
			$query->bindValue(":description", $description);
				
			$query->execute();
				
			$rows = $query->fetchAll (\PDO::FETCH_ASSOC );
				
			foreach ( $rows as $row ) {
	
				$component = new Component ();
				$category = new Category ();
				$measurementUnit = new Measurement ();
				//
				$component->setIdComponent ( $row ['id_component'] );
				$component->setDescription ( $row ['description'] );
				// Categoría
				$category->setIdCategory ( $row ['id_category'] );
				$component->setCategory ( $category );
				//
				$measurementUnit->setIdUnit ( $row ['id_unit'] );
				$component->setMeasurementUnit ( $measurementUnit );
	
				array_push ( $components, $component );
			}
		} catch ( \Exception $e ) {
			ExceptionHelper::log ( $e, $this );
			ExceptionHelper::throwException ( $e, $this );
		}
	
		return $components;
	}
}