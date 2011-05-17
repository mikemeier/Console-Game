<?php

	namespace Console\Request;

	interface Request {

		public function __construct($queryString);

		public function getQueryString($withCommand = true);
		public function getCommand();
		public function getParameters();
		public function getParameter($number);

		public function setCommand($command);
		public function setParameters(array $parameters);
		public function setQueryString($queryString);

	}