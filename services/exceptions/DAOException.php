<?php
namespace com\appstions\nutrifun\exceptions;

class DAOException extends \Exception{
	public function __construct($message = null, $code = null){
		parent::__construct($message, $code);
	}
	
}