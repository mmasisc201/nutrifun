<?php
namespace com\appstions\nutrifun\helper;

/**
 * Traductor de lenguajes
 * @author jgarita
 *
 */
class TranslateHelper {
	
	private $lang;
	
	const ROOT_PATH = "config/language/properties.";
	const DEFAULT_FILE = "config/language/properties.es.ini";
	const DEFAULT_LANGUAGE = "es";
	
	public function __construct($lang = NULL){
		if($lang == NULL){
			$lang = self::DEFAULT_LANGUAGE;
		}
		$this->lang = $lang;
	}
	
	public function setLanguage($lang){
		$this->lang = $lang;
	}
	
	public function translate($data){
		
		try {
			
			$filePath = self::ROOT_PATH . $this->lang . ".ini";
			$translation = "";
				
			if (!file_exists($filePath)){
				$filePath = self::DEFAULT_FILE;
			}
				
			$file = parse_ini_file($filePath);
			
			if(array_key_exists($data, $file)){
				
				$translation = $file[$data];
	
			} else {
				
				$file = parse_ini_file(self::DEFAULT_FILE);
	
				if($this->lang != self::DEFAULT_LANGUAGE && array_key_exists($data, $file)){
					$translation = $file[$data];
	
				} else {
					$translation = $data;
				}
			}
			$translation = array_key_exists($data, $file)?$file[$data]:$data;
				
			return $translation;
		} catch (\Exception $e) {
			throw $e;
		}
	
	
	}
}