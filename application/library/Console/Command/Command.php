<?php

	namespace Console\Command;

	use Console\Service\Manager;

	use Console\Request\Request;
	use Console\Response\Response;

	interface Command {

		const COMMAND_CHAIN_STOP			= 'stop';
		const COMMAND_STATUS_UNINITIALIZED	= 'uninitialized';
		const COMMAND_EXECUTE				= 'execute';

		public function __construct(Manager $serviceManager = null);
		public function execute(Request $request, Response $response);
		public function getName();

	}