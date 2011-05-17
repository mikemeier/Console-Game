<?php

	namespace Console\Request\Parser;

	use Console\Request\Request;

	class Parser {

		protected $splitter;

		public function __construct(\Closure $splitter){
			$this->splitter = $splitter;
		}

		public function parse(Request $request){
			$splitter	= $this->splitter;
			$matches	= $splitter($request);
			if(!isset($matches[0]))
				return $request;
			$request->setCommand($matches[0]);
			if(!isset($matches[1]))
				return $request;
			unset($matches[0]);
			$request->setParameters(\array_values($matches));
			return $request;
		}

	}