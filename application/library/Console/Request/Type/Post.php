<?php

	namespace Console\Request\Type;

	use Console\Request\AbstractRequest;
	
	class Post extends AbstractRequest {

		public function __construct($varName){
			$queryString = isset($_POST[$varName]) ? $_POST[$varName] : '';
			$this->setQueryString($queryString);
		}

	}