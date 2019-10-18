<?php
namespace Esiteful\Concrete5\MigrationTool\Publisher\Logger\Formatter\Object;

use HtmlObject\Link;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Block;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\BlockType;
use PortlandLabs\Concrete5\MigrationTool\Entity\Publisher\Log\Object\LoggableObject;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Logger\Formatter\Object\AbstractStandardFormatter;
use Esiteful\Concrete5\MigrationTool\Entity\Publisher\Log\Object\File;

defined('C5_EXECUTE') or die("Access Denied.");

class FileAttributeFormatter extends AbstractStandardFormatter
{

    public function getSkippedDescription(LoggableObject $object)
    {
        return false;
    }

    /**
     * @param LoggableObject $object
     * @return string
     */
    public function getPublishStartedDescription(LoggableObject $object)
    {
        return t('Setting attribute %s on page %s', $object->getHandle(), $object->getFile());
    }

    public function getPublishCompleteDescription(LoggableObject $object)
    {
        return t('Setting attribute %s on page %s', $object->getHandle(), $object->getFile());
    }



}
