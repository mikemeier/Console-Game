<?php

	namespace Console\Response\Line;

	class LinePart {

		protected $string, $classes = array();

		public function __construct($string, array $classes = array()){
			$this->string	= $string;
			$this->classes	= $classes;
		}

		public function getString(){
			return $this->string;
		}

		public function getClasses(){
			return $this->classes;
		}

	}