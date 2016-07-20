<?php
namespace com\appstions\nutrifun\entity;


require_once 'entity/JsonUnserializable.php';
require_once 'entity/Category.php';
require_once 'entity/Measurement.php';

/**
 * Clase que maneja los componentes de los alimentos 
 * @author CIC
 *
 */
class Component implements \JsonSerializable, JsonUnserializable {
	
	private $idComponent;
	private $description;
	private $category;
	private $measurementUnit;
	//Define la cantidad establecida del componente en la tabla, diferente por cada alimento 
	private $quantity;
	//Define el total calculado para el componente asociado a la unidad 
	private $calculatedQuantity;
		
	public function __construct(){
		$this->category = new Category();
		$this->measurementUnit = new Measurement();
		$this->quantity = 0.0;
		$this->calculatedQuantity = 0.0;		
	}
	
	public function getIdComponent() {
		return $this->idComponent;
	}
	public function setIdComponent($idComponent) {
		$this->idComponent = $idComponent;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	public function getCategory() {
		return $this->category;
	}
	public function setCategory($category) {
		$this->category = $category;
		return $this;
	}
	public function getMeasurementUnit() {
		return $this->measurementUnit;
	}
	public function setMeasurementUnit($measurementUnit) {
		$this->measurementUnit = $measurementUnit;
		return $this;
	}
	public function getQuantity() {
		return $this->quantity;
	}
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
		return $this;
	}
	public function getCalculatedQuantity() {
		return $this->calculatedQuantity;
	}
	public function setCalculatedQuantity($calculatedQuantity) {
		$this->calculatedQuantity = $calculatedQuantity;
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