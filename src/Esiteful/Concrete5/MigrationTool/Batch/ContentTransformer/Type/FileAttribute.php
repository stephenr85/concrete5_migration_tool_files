<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\ContentTransformer\Type;

use PortlandLabs\Concrete5\MigrationTool\Batch\ContentTransformer\Type\Attribute;

class FileAttribute extends Attribute
{
    public function getDriver()
    {
        return 'file_attribute';
    }
}