<?php
namespace com\appstions\nutrifun\exceptions;

class ServiceException extends \Exception{
	public function __construct($message = null, $code = null){
		parent::__construct($message, $code);
	}
	
}