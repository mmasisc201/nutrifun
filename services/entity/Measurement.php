<?php
namespace com\appstions\nutrifun\entity;


require_once 'entity/JsonUnserializable.php';

/**
 * Contiene las unidades de medida existentes
 * @author CIC
 *
 */
class Measurement implements \JsonSerializable, JsonUnserializable {
	
	private $idUnit;
	private $description;
	private $symbol;
		
	public function __construct(){		
	}
	
	public function getIdUnit() {
		return $this->idUnit;
	}
	public function setIdUnit($idUnit) {
		$this->idUnit = $idUnit;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	public function getSymbol() {
		return $this->symbol;
	}
	public function setSymbol($symbol) {
		$this->symbol = $symbol;
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