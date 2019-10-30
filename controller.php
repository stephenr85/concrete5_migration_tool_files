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
	protected $pkgVersion = '0.0.1';
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

		// Add mapping types
		$importMapper = \Core::make('migration/manager/mapping');
        $importMapper->extend('file_attribute', function () {
        	return new \Esiteful\Concrete5\MigrationTool\Batch\ContentMapper\Type\FileAttribute();
        });
        $importMapper->driver('file_attribute');

		// Add import routines
		$importManager = \Core::make('migration/manager/importer/cif');
		$importManager->addRoutine(new \Esiteful\Concrete5\MigrationTool\Importer\CIF\Element\File, 'user');
		//$importManager->addRoutine(new \Esiteful\Concrete5\MigrationTool\Importer\CIF\Element\FileSet, 'file');

		// Add transformers
		$importTransformer = \Core::make('migration/manager/import/attribute/value');
        $importTransformer->extend('file_attribute', function () {
        	return new \Esiteful\Concrete5\MigrationTool\Batch\ContentTransformer\Type\FileAttribute();
        });
        $importTransformer->driver('file_attribute');

		// Add import routines
		$importPublisher = \Core::make('migration/manager/publisher');
		$importPublisher->extend('create_files', function() {
			return new \Esiteful\Concrete5\MigrationTool\Publisher\Routine\CreateFilesRoutine();
		});
		$importPublisher->driver('create_files');


		\Core::bind('migration/batch/file/validator', function ($app, $batch) {
            if (isset($batch[0])) {
                $v = new \Esiteful\Concrete5\MigrationTool\Batch\Validator\File\FileValidator($batch[0]);
                $v->registerTask(new \Esiteful\Concrete5\MigrationTool\Batch\Validator\File\Task\ValidateAttributesTask());
                return $v;
            }
        });

        // Load routes
        $router = $this->app->make('router');
        $toolsRouteList = new ToolsRouteList();
	    $toolsRouteList->loadRoutes($router);
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
	
}