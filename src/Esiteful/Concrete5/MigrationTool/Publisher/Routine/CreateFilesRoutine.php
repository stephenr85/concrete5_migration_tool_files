<?php
namespace Esiteful\Concrete5\MigrationTool\Publisher\Routine;

use PortlandLabs\Concrete5\MigrationTool\Publisher\Routine\RoutineInterface;
use PortlandLabs\Concrete5\MigrationTool\Batch\BatchInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Logger\LoggerInterface;

defined('C5_EXECUTE') or die("Access Denied.");

class CreateFilesRoutine implements RoutineInterface
{
    public function getPublisherRoutineActions(BatchInterface $batch)
    {
        $collection = $batch->getObjectCollection('file');

        if (!$collection) {
            return [];
        }

        $actions = [];
        foreach ($collection->getFiles() as $file) {
            $action = new CreateFileRoutineAction($file);
            $actions[] = $action;
        }

        return $actions;
    }
}
