<?php

	namespace sestolk\BatchedMigrations\Console;

	use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
	use Illuminate\Database\Migrations\MigrationCreator;
	use Illuminate\Filesystem\Filesystem;
	use Illuminate\Support\Composer;
	use Symfony\Component\Console\Exception\LogicException;

	class MakeMigrationCommand extends MigrateMakeCommand
	{
		/**
		 * The console command signature.
		 *
		 * @var string
		 */
		protected $signature = 'make:migration {name : The name of the migration.}
        {--create= : The table to be created.}
        {--table= : The table to migrate.}
        {--path= : The location where the migration file should be created.}
        {--fallback= : Fallback to normal make migration}';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Create a new migration file (batched.migration)';

		/**
		 * The Laravel Filesystem
		 *
		 * @var Filesystem
		 */
		protected $files;

		/**
		 * @var boolean
		 */
		protected $filterName;

		/**
		 * @var boolean
		 */
		protected $prefixName;

		/**
		 * @var array
		 */
		protected $prefixes;

		/**
		 * Create a new command instance.
		 *
		 * @param MigrationCreator $creator
		 * @param Composer $composer
		 * @param Filesystem $files
		 */
		public function __construct( MigrationCreator $creator, Composer $composer, Filesystem $files )
		{
			parent::__construct( $creator, $composer );

			$this->files = $files;

			$this->filterName = config( 'batched.migrations.filter_name' );
			$this->prefixName = config( 'batched.migrations.prefix_name' );
			$this->prefixes   = config( 'batched.migrations.prefixes' );
		}

		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function fire()
		{
			if( $this->input->getOption( 'fallback' ) )
			{
				parent::fire();
				return;
			}

			$name  = $this->validateMigrationName();
			$files = $this->readMigrationFiles();
			$name  = $this->setUniqueMigrationName( $name, $files );

			$table = $this->input->getOption( 'table' );

			$create = $this->input->getOption( 'create' );

			if( !$table && is_string( $create ) )
			{
				$table = $create;
			}

			// Now we are ready to write the migration out to disk. Once we've written
			// the migration out, we will dump-autoload for the entire framework to
			// make sure that the migrations are registered by the class loaders.
			$this->writeMigration( $name, $table, $create );

			$this->composer->dumpAutoloads();
		}

		/**
		 * Make sure the migration name is unique
		 *
		 * @param $name
		 * @param $names
		 *
		 * @return string
		 */
		protected function setUniqueMigrationName( $name, $names )
		{
			foreach( $names as $existing )
			{
				$matches = preg_match_all( '/([0-9]{4}\_[0-9]{2}\_[0-9]{2}\_[0-9]{6})\_([a-zA-Z_]+)([0-9]*)/', $existing, $parts );
				# File is in the right format
				if( $matches == 0 )
				{
					continue;
				}

				$existingName = trim( $parts[2][0], '_' );

				# Compare name to see if we should increment the batch number
				if( $existingName == $name )
				{
					$currentBatch = intval( $parts[3][0] );
					$batch        = $currentBatch + 1;

					return trim( $name, '_' ) . '_' . $batch;
				}
			}

			return trim( $name, '_' );
		}

		/**
		 * Read directory for the migration files
		 *
		 * @return array
		 */
		protected function readMigrationFiles()
		{
			$files = $this->files->glob( base_path( 'database/migrations' ) . '/*_*.php' );

			if( $files === false )
			{
				return [ ];
			}

			# Get the basenames without extension
			$files = array_map( function ( $file )
			{
				return str_replace( '.php', '', basename( $file ) );
			}, $files );

			# Only valid migration names 0000_00_00_000000_name_in_snake_case
			$files = array_filter( $files, function ( $name )
			{
				return preg_match( '/([0-9]{4}\_[0-9]{2}\_[0-9]{2}\_[0-9]{6})\_([a-zA-Z]+)/', $name ) === 1;
			} );

			rsort( $files );

			return $files;
		}

		/**
		 * Check if the migration name contains the prefix
		 *
		 * @param $name
		 *
		 * @return bool
		 */
		protected function nameContainsPrefix( $name )
		{
			foreach( $this->prefixes as $prefix )
			{
				if( starts_with( $name, $prefix ) )
				{
					return true;
				}
			}

			return false;
		}

		/**
		 * Validate the migration name
		 *
		 * @return string
		 * @throws LogicException
		 */
		protected function validateMigrationName()
		{
			$name = strtolower( $this->input->getArgument( 'name' ) );

			# Name must be snake cased
			if( !str_contains( $name, '_' ) )
			{
				throw new LogicException( 'The name for the migration must be in snake case and contain at least two words.' );
			}

			# Migrations should not contain the following words
			if( $this->filterName && str_contains( $name, [ 'table', 'schema' ] ) )
			{
				throw new LogicException( 'The name for the migration should not contain the words [table, schema]' );
			}

			# Migrations should contain one of the following words
			if( $this->prefixName && !$this->nameContainsPrefix( $name ) )
			{
				throw new LogicException( 'The name for the migration must be prefixed with one of the following words [' . implode( ', ', $this->prefixes ) . ']' );
			}

			return $name;
		}
	}
