<?php

return [

	/*
		  |--------------------------------------------------------------------------
		  | Laravel CORS
		  |--------------------------------------------------------------------------
		  |
		  | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
		  | to accept any value.
		  |
 */

	'supportsCredentials' => false,
	'allowedOrigins' => ['*'],
	'allowedOriginsPatterns' => [],
	'allowedHeaders' => ['*'],
	'allowedMethods' => ['*'],
	'exposedHeaders' => ['Content-type', 'Content-Disposition'],
	'maxAge' => 0,

];
