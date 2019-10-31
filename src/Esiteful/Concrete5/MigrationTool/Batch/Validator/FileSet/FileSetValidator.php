<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\Validator\FileSet;

use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\ValidateProcessor;

defined('C5_EXECUTE') or die("Access Denied.");

class FileSetValidator extends ValidateProcessor
{
    public function getTarget($mixed)
    {
        return new FileSetValidatorTarget($this->getBatch(), $mixed);
    }
}
