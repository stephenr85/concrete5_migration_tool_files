<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\Validator\File;

use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\Message;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\MessageCollection;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\ValidatorTarget;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\ValidatorTargetInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Site;
use Esiteful\Concrete5\MigrationTool\Entity\Import\File;

defined('C5_EXECUTE') or die("Access Denied.");

class FileValidatorTarget extends ValidatorTarget
{
    protected $file;

    public function __construct(Batch $batch, File $file)
    {
        parent::__construct($batch);
        $this->file = $file;
    }

    public function getItems()
    {
        return array($this->file);
    }

}
