<?php
namespace Concrete\Package\MigrationToolFiles\Routing;

use Concrete\Core\Routing\RouteListInterface;
use Concrete\Core\Routing\Router;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Esiteful\Concrete5\MigrationTool\Batch\Formatter\File\TreeFileJsonFormatter;

class ToolsRouteList implements RouteListInterface {
	
	public function loadRoutes(Router $router)
    {
        // Routes and route groups go here.
        $router->get('/tools/packages/migration_tool_files/load_batch_file_data', get_class($this).'::load_batch_file_data');

    }

    public function load_batch_file_data()
    {
        
        $request = Request::getInstance();

        $db = \Core::make('database');
        $entityManager = $db->getEntityManager();

        session_write_close();
        $r = $entityManager->getRepository('\Esiteful\Concrete5\MigrationTool\Entity\Import\File');
        $file = $r->findOneById($request->get('id'));
        if (is_object($file)) {
            $formatter = new TreeFileJsonFormatter($file);

            return new JsonResponse($formatter);
        }
        return new JsonResponse(['id' => $request->get('id')]);
    }

}