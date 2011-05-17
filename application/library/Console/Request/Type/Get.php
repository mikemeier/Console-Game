<?php

	namespace Console\Request\Type;

	use Console\Request\AbstractRequest;
	
	class Get extends AbstractRequest {

		public function __construct($varName){
			$queryString = isset($_GET[$varName]) ? $_GET[$varName] : '';
			$this->setQueryString($queryString);
		}

	}