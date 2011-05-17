<?php

	namespace Console\Response;

	use Console\Response\Line\Line;
	
	use Console\Request\Request;

	interface Response {

		public function setRequest(Request $request);

		public function hasLines();
		public function getLines();
		public function addLine(Line $line);
		public function setStandardLineClass($className);

		public function getCleanLineObjects();
		
		public function addOutput($output);
		public function getOutput();

		public function newLine($string, array $classes = array());

		public function setHeader(array $additionalHeader = array());

		public function __set($key, $value);
		public function __get($key);

	}