<?php

	namespace Console\Logger;

	interface Logger {

		const LOG_LEVEL_NOTICE	= 'notice';
		const LOG_LEVEL_WARN	= 'warn';
		const LOG_LEVEL_ERROR	= 'error';

		public function log($message, $level = LOG_LEVEL_NOTICE);

	}