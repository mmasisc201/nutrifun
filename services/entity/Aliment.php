<?php
namespace com\appstions\nutrifun\entity;


require_once 'entity/JsonUnserializable.php';
require_once 'entity/Group.php';

/**
 * Clase que contiene el alimento
 * @author CIC
 *
 */
class Aliment implements \JsonSerializable, JsonUnserializable {
	
	private $idAliment;
	private $description;
	private $group;
	private $components;
	private $quantity;
		
	public function __construct(){		
		$this->group = new Group();
		$this->components = array();
		$this->quantity = 0.0;
	}
	
	public function getIdAliment() {
		return $this->idAliment;
	}
	public function setIdAliment($idAliment) {
		$this->idAliment = $idAliment;
		return $this;
	}

	public function getDescription(){
		return $this->description;
	}
	
	public function setDescription($description){
		$this->description = $description;
	}
	
	public function getGroup() {
		return $this->group;
	}
	public function setGroup($group) {
		$this->group = $group;
		return $this;
	}
	public function getComponents() {
		return $this->components;
	}
	public function setComponents($components) {
		$this->components = $components;
		return $this;
	}
	public function getQuantity() {
		return $this->quantity;
	}
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
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