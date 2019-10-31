<?php

	namespace Concrete\Package\MigrationToolFiles;

	use \Loader;
	use Route;
	use \Events;

	use Package;
	use Concrete\Package\MigrationToolFiles\Routing\ToolsRouteList;

	/** 
	 * This is the main controller for the package which controls the functionality like Install/Uninstall etc. 
	 * 
	 * @author Stephen Rushing, eSiteful 
	 */ 
	class Controller extends Package {

	/**
	* Protected data members for controlling the instance of the package 
	*/
	protected $pkgHandle = 'migration_tool_files'; 
	protected $appVersionRequired = '8.2.1';
	protected $pkgVersion = '0.0.2';
	protected $pkgAutoloaderRegistries = array(
        'src/Esiteful/Concrete5/MigrationTool' => '\Esiteful\Concrete5\MigrationTool',
    );

	/**
	 * This function returns the functionality description ofthe package.
	 * 
	 * @param void 
	 * @return string $description
	 * @author Stephen Rushing, eSiteful
	 */
	public function getPackageDescription()
	{
	    return t("Files addon for Migration Tool.");
	}

	/**
	 * This function returns the name of the package.
	 * 
	 * @param void
	 * @return string $name
	 * @author Stephen Rushing, eSiteful
	 */
	public function getPackageName()
	{
	    return t("Migration Tool - Files");
	}

	public function getPackageHelper()
	{
		$pkg = Package::getByHandle($this->getPackageHandle());
		$helper = new PackageHelper($pkg);
		$helper->setApplication($this->app);
		return $helper;
	}


	public function on_start(){

		$this->load_routes();
		$this->load_import_drivers();

	}

	/**
	 * This function is executed during initial installation of the package.
	 * 
	 * @param void
	 * @return void
	 * @author Stephen Rushing, eSiteful
	 */
	public function install()
	{
	    $pkg = parent::install();

	    // Install Package Items
	    // $this->install_something($pkg);
	}

	/**
	 * This function is executed during upgrade of the package.
	 * 
	 * @param void
	 * @return void
	 * @author Stephen Rushing, eSiteful
	 */
	public function upgrade()
	{
		parent::upgrade();
		$pkg = Package::getByHandle($this->getPackageHandle());

	    // Install Package Items	    
	    // $this->install_something($pkg);
	}

	/**
	 * This function is executed during uninstallation of the package.
	 * 
	 * @param void
	 * @return void
	 * @author Stephen Rushing, eSiteful
	 */
	public function uninstall()
	{
	    $pkg = parent::uninstall();
	}

	/**
	 * Load routes used by this package.
	 * 
	 * @param void
	 * @return void
	 * @author Stephen Rushing, eSiteful
	 */
	public function load_routes()
	{
        $router = $this->app->make('router');
        $toolsRouteList = new ToolsRouteList();
	    $toolsRouteList->loadRoutes($router);
	}

	/**
	 * Load import drivers.
	 * 
	 * @param void
	 * @return void
	 * @author Stephen Rushing, eSiteful
	 */
	public function load_import_drivers()
	{
		// Add mapping types
		$importMapper = \Core::make('migration/manager/mapping');
        $importMapper->extend('file_attribute', function () {
        	return new \Esiteful\Concrete5\MigrationTool\Batch\ContentMapper\Type\FileAttribute();
        });
        $importMapper->driver('file_attribute');

        $importMapper = \Core::make('migration/manager/mapping');
        $importMapper->extend('file_set', function () {
        	return new \Esiteful\Concrete5\MigrationTool\Batch\ContentMapper\Type\FileSet();
        });
        $importMapper->driver('file_set');

		// Add import routines
		$importManager = \Core::make('migration/manager/importer/cif');
		$importManager->registerRoutine(new \Esiteful\Concrete5\MigrationTool\Importer\CIF\Element\File);
		$importManager->registerRoutine(new \Esiteful\Concrete5\MigrationTool\Importer\CIF\Element\FileSet);

		// Add transformers
		$importTransformer = \Core::make('migration/manager/import/attribute/value');
        $importTransformer->extend('file_attribute', function () {
        	return new \Esiteful\Concrete5\MigrationTool\Batch\ContentTransformer\Type\FileAttribute();
        });
        $importTransformer->driver('file_attribute');

        // $importTransformer = \Core::make('migration/manager/import');
        // $importTransformer->extend('file_set', function () {
        // 	return new \Esiteful\Concrete5\MigrationTool\Batch\ContentTransformer\Type\FileSet();
        // });
        // $importTransformer->driver('file_set');

		// Add import routines
		$importPublisher = \Core::make('migration/manager/publisher');
		
		$importPublisher->extend('create_files', function() {
			return new \Esiteful\Concrete5\MigrationTool\Publisher\Routine\CreateFilesRoutine();
		});
		$importPublisher->driver('create_files');

		$importPublisher->extend('create_file_sets', function() {
			return new \Esiteful\Concrete5\MigrationTool\Publisher\Routine\CreateFileSetsRoutine();
		});		
		$importPublisher->driver('create_file_sets');


		\Core::bind('migration/batch/file/validator', function ($app, $batch) {
            if (isset($batch[0])) {
                $v = new \Esiteful\Concrete5\MigrationTool\Batch\Validator\File\FileValidator($batch[0]);
                $v->registerTask(new \Esiteful\Concrete5\MigrationTool\Batch\Validator\File\Task\ValidateAttributesTask());
                return $v;
            }
        });

        \Core::bind('migration/batch/file_set/validator', function ($app, $batch) {
            if (isset($batch[0])) {
                $v = new \Esiteful\Concrete5\MigrationTool\Batch\Validator\FileSet\FileSetValidator($batch[0]);
                return $v;
            }
        });
	}
	
}