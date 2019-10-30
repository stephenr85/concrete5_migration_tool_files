<?php
namespace Esiteful\Concrete5\MigrationTool\Publisher\Logger\Formatter\Object;

use HtmlObject\Link;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Logger\Formatter\Object\AbstractStandardFormatter;

use PortlandLabs\Concrete5\MigrationTool\Entity\Import\BlockType;
use PortlandLabs\Concrete5\MigrationTool\Entity\Publisher\Log\Object\LoggableObject;
use Esiteful\Concrete5\MigrationTool\Entity\Publisher\Log\Object\File;

defined('C5_EXECUTE') or die("Access Denied.");

class FileFormatter extends AbstractStandardFormatter
{
    public function getLogItemName($object)
    {
        return $object->getPrefix() . ':' . $object->getFilename();
    }

    public function getSkippedDescription(LoggableObject $object)
    {
        return t('File %s skipped.', $this->getLogItemName($object));
    }

    /**
     * @param File $object
     * @return string
     */
    public function getPublishStartedDescription(LoggableObject $object)
    {
        return t('Publishing file %s',  $this->getLogItemName($object));
    }

    public function getPublishCompleteDescription(LoggableObject $object)
    {
        return t('File %s published at ID %s.', $this->getLogItemName($object), $object->getPublishedFileID());
    }



}
