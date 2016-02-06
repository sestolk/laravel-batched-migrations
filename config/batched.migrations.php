<?php
	return [

		/*
		|--------------------------------------------------------------------------
		| Batched Migration settings
		|--------------------------------------------------------------------------
		|
		| You can define a few settings here which configure the way
		| migrations will be created.
		|
		*/

		/*
		|
		| The name of the migration may not contain certain words
		|
		 */
		'filter_name' => true,

		/**
		 * The words that are not allowed in the migration name
		 */
		'filtered'    => [

			'table',
			'schema'
		],

		/**
		 * The migration name must be prefixed
		 */
		'prefix_name' => true,

		/**
		 * The migration name prefixes
		 */
		'prefixes'    => [

			'create',
			'update'
		],


	];