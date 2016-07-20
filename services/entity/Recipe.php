<?php
namespace com\appstions\nutrifun\entity;


require_once 'entity/JsonUnserializable.php';

class Recipe implements \JsonSerializable, JsonUnserializable {
	
	private $idRecipe;
	private $description;
	private $components;
	private $ingredients;
		
	public function __construct(){		
		$this->components = array();
		$this->ingredients = array();
	}
	
	public function setIdRecipe($idRecipe){
		$this->idRecipe = $idRecipe;
	}
	
	public function getIdRecipe(){
		return $this->idRecipe;
	}
	
	public function getDescription(){
		return $this->description;
	}
	
	public function setDescription($description){
		$this->description = $description;
	}
	public function getComponents() {
		return $this->components;
	}
	public function setComponents($components) {
		$this->components = $components;
		return $this;
	}
	public function getIngredients() {
		return $this->ingredients;
	}
	public function setIngredients($ingredients) {
		$this->ingredients = $ingredients;
		return $this;
	}
	

	/**
	 *
	 * {@inheritDoc}
	 *
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize() {
		$json = [];
		$vars = get_class_vars(get_class($this));
		
		foreach ($vars as $key => $value) {
			
			$json[$key] = $this->{$key};
		}
		return $json;
	}

	/**
	 * {@inheritDoc}
	 * @see \com\appstions\yourChallenge\entity\JsonUnserializable::jsonUnserialize()
	 */
	public function jsonUnserialize(array $array) {
		$isValid = true;
	
		foreach ($array as $key => $value) {
			if(property_exists($this, $key)){
				
				if($this->{$key} instanceof JsonUnserializable){
					
					if(is_array($value)){
						call_user_func_array(array($this->{$key}, 'jsonUnserialize'), array($value));
					}else{
						$isValid = false;
					}
	
				}else{
					$this->{$key} = $value;
				}
			} else {
				$isValid = false;
			}
		}
	
		return $isValid;
	
	}

}