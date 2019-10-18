<?php
namespace Esiteful\Concrete5\MigrationTool\Batch\Formatter\ObjectCollection;

defined('C5_EXECUTE') or die("Access Denied.");

use PortlandLabs\Concrete5\MigrationTool\Batch\Formatter\ObjectCollection\AbstractFormatter;

class FileFormatter extends AbstractFormatter
{
	protected function getPackageHandle()
    {
        return 'cfisd_upgrade_migration';
    }

    public function getPluralDisplayName()
    {
        return t('Files');
    }
}
