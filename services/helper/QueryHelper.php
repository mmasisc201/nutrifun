<?php
namespace com\appstions\nutrifun\helper;

class QueryHelper {
	
	const ROOT_PATH = "queries/queries.xml";
	
	private $dao;
	private $nodeParent;
	public function __construct($node){
		
		try {
			
			$file = file_get_contents(self::ROOT_PATH);
			$dao = new \SimpleXMLElement($file);
				
			$this->nodeParent = self::findNodeQueries($dao->queries, $node);
				
		} catch (\Exception $e) {
			throw $e;
		}
		
	}
	
	public function getQuery($name){
		$query = '';
		
		try {
						
			if($this->nodeParent != null){
				
				$nodeQuery = self::findNodeQuery($this->nodeParent->query, $name);
				if($nodeQuery != null){
					
					$query = $nodeQuery;
					
				}
			}	
			
		} catch (\Exception $ex) {
			ExceptionHelper::log($ex, self);
		}
		
		return $query;
	}
	
	private function findNodeQueries($queries, $name){
		
		try {
			
			$index = 0;
			$found = false;
			$length = count($queries);
			$node = null;
			
			while ($index < $length && $found == false) {
				if($queries[$index]['name'] == $name){
					$node = $queries[$index];
					$found = true;
				} else {
					$index++;
				}
			}
			return $node;
		} catch (\Exception $ex) {
			throw $ex;
		}
		
	}
	
	private function findNodeQuery($queries, $name){
	
		try {
				
			$index = 0;
			$found = false;
			$length = count($queries);
			$node = null;
				
			while ($index < $length && $found == false) {
				if($queries[$index]['name'] == $name){
					$node = $queries[$index];
					$found = true;
				} else {
					$index++;
				}
			}
			return $node;
		} catch (\Exception $ex) {
			throw $ex;
		}
	
	}
}