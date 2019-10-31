<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\Formatter\ObjectCollection;

defined('C5_EXECUTE') or die("Access Denied.");

use PortlandLabs\Concrete5\MigrationTool\Batch\Formatter\ObjectCollection\AbstractFormatter;

class FileSetFormatter extends AbstractFormatter
{
	protected function getPackageHandle()
    {
        return 'migration_tool_files';
    }

    public function getPluralDisplayName()
    {
        return t('File Sets');
    }
}
