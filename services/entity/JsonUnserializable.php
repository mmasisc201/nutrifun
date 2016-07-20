<?php

namespace com\appstions\nutrifun\entity;

interface JsonUnserializable {
	/**
	 * 
	 * @param array $array
	 */
	public function jsonUnserialize(array $array);
}