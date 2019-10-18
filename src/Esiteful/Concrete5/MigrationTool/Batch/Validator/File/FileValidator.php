<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\Validator\File;

use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\ValidateProcessor;

defined('C5_EXECUTE') or die("Access Denied.");

class FileValidator extends ValidateProcessor
{
    public function getTarget($mixed)
    {
        return new FileValidatorTarget($this->getBatch(), $mixed);
    }
}
