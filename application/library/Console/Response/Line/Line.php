<?php

	namespace Console\Response\Line;

	class Line {

		protected $parts = array();

		public function __construct($string = null, array $classes = array()){
			if($string)
				$this->addPart(new LinePart($string, $classes));
		}

		public function addPart(LinePart $part){
			$this->parts[] = $part;
		}

		public function getParts(){
			return $this->parts;
		}

	}