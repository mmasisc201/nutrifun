<?php

namespace com\appstions\nutrifun\service;

use com\appstions\nutrifun\dataAccess\ComponentDAO;
use com\appstions\nutrifun\entity\Component;
use com\appstions\nutrifun\helper\Helper;
use com\appstions\nutrifun\helper\ExceptionHelper;
use com\appstions\nutrifun\exceptions\ServiceException;
use com\appstions\nutrifun\constant\Constant;
use com\appstions\nutrifun\entity\Measurement;
use com\appstions\nutrifun\entity\Category;

require_once 'service/IComponentService.php';
require_once 'entity/Component.php';
require_once 'entity/Category.php';
require_once 'entity/Measurement.php';
require_once 'constant/Constant.php';
require_once 'dataAccess/ComponentDAO.php';
require_once 'helper/Helper.php';

/**
 * Manejo de componentes
 * @author CIC
 *
 */
class ComponentService extends Rest implements IComponentService {
	
	private $componentDAO;
	
	public function __construct() {
		try {
			parent::__construct ();
			$this->componentDAO = new ComponentDAO ();
		} catch ( \Exception $exc ) {
			ExceptionHelper::log ( $exc, $this );
			ExceptionHelper::throwException ( $exc, $this );
		}
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \com\appstions\nutrifun\service\IComponentService::getCompoents()
	 */
	public function getComponents() {
		try {
			$this->checkPostRequest ( Rest::DISABLE_AUTHENTICATION );
			
			$header = $this->getRequestBody () [Rest::HEADER];
			
			$components = $this->componentDAO->getComponents ();
			
			if (!empty ( $components )) {
				$this->processSuccessResponse ( $components );
			}  {
				throw new ServiceException ( IComponentService::ARRAY_EMPTY, Rest::CUSTOM_ERROR_CODE );
			}
		} catch ( \Exception $e ) {
			ExceptionHelper::log ( $e, $this );
			ExceptionHelper::throwException ( $e, $this );
		}
	}
	/**
	 *
	 * {@inheritdoc}
	 *
	 * @see \com\appstions\nutrifun\service\IComponentService::getComponentsByDescription()
	 */
	public function getComponentsByDescription() {
		try {
			$this->checkPostRequest ( Rest::DISABLE_AUTHENTICATION );
			
			$header = $this->getRequestBody () [Rest::HEADER];
			
			$body = $this->getRequestBody ()[Rest::BODY];
			
			$component = new Component();
			$component->setMeasurementUnit(new Measurement());
			$component->setCategory(new Category());
		
			$this->unserializeBody ( $body, $component );
			
			$components = $this->componentDAO->getComponentsByDescription($component->getDescription());
			
			if (!empty ( $components )) {
				$this->processSuccessResponse ( $components );
			} else {
				throw new ServiceException ( IComponentService::ARRAY_EMPTY, Rest::CUSTOM_ERROR_CODE );
			}
		} catch ( \Exception $e ) {
			ExceptionHelper::log ( $e, $this );
			ExceptionHelper::throwException ( $e, $this );
		}

	}

}