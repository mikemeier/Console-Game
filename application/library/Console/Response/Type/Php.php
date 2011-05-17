<?php

	namespace Console\Response\Type;
	
	use Console\Response\AbstractResponse;

	class Php extends AbstractResponse {

		protected $charset;

		public function __construct($charset){
			$this->charset = $charset;
		}

		protected function setResponseHeader(){
			header('Content-Type: text/html; charset='. $this->charset);
		}

		protected function getResponseOutput(array $output){
			return '<pre>'. print_r($output, true) .'</pre>';
		}

	}