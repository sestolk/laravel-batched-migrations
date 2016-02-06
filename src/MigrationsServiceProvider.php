<?php
	namespace sestolk\BatchedMigrations;

	use Illuminate\Database\MigrationServiceProvider;
	use sestolk\BatchedMigrations\Console\MakeMigrationCommand;

	class MigrationsServiceProvider extends MigrationServiceProvider
	{
		/**
		 * Indicates if loading of the provider is deferred.
		 *
		 * @var bool
		 */
		protected $defer = false;

		/**
		 * The name in the IoC container
		 *
		 * @var string
		 */
		private $name = 'batched.migrations';

		/**
		 * Path to the config of this package
		 *
		 * @var string
		 */
		protected $configPath = __DIR__ . '/../config/batched.migrations.php';

		/**
		 * Create a new service provider instance.
		 *
		 * @param  \Illuminate\Contracts\Foundation\Application $app
		 */
		public function __construct( $app )
		{
			parent::__construct( $app );

			$this->app = $app;
		}

		/**
		 * Register the service provider.
		 *
		 * @return void
		 */
		public function register()
		{
			$this->mergeConfigFrom( $this->configPath, $this->name );

			$this->app[$this->name . '.make.migration'] = $this->app->share( function ( $app )
			{
				return $app->make( MakeMigrationCommand::class );
			} );

			$this->commands( [ $this->name . '.make.migration' ] );
		}

		public function boot()
		{
			$this->publishes( [ $this->configPath => config_path( $this->name . '.php' ) ], 'config' );
		}

		/**
		 * Get the services provided by the provider.
		 *
		 * @return array
		 */
		public function provides()
		{
			return [ $this->name, $this->name . '.make.migration' ];
		}
	}