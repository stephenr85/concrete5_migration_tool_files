<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\Validator\FileSet;

use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\Message;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\MessageCollection;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\ValidatorTarget;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\ValidatorTargetInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;
use Esiteful\Concrete5\MigrationTool\Entity\Import\FileSet;

defined('C5_EXECUTE') or die("Access Denied.");

class FileSetValidatorTarget extends ValidatorTarget
{
    protected $fileSet;

    public function __construct(Batch $batch, FileSet $fileSet)
    {
        parent::__construct($batch);
        $this->fileSet = $fileSet;
    }

    public function getItems()
    {
        return array($this->fileSet);
    }

}
