<?php
namespace Esiteful\Concrete5\MigrationTool\Publisher\Logger\Formatter\Object;

use HtmlObject\Link;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Logger\Formatter\Object\AbstractStandardFormatter;

use PortlandLabs\Concrete5\MigrationTool\Entity\Import\BlockType;
use PortlandLabs\Concrete5\MigrationTool\Entity\Publisher\Log\Object\LoggableObject;
use Esiteful\Concrete5\MigrationTool\Entity\Publisher\Log\Object\FileSet;

defined('C5_EXECUTE') or die("Access Denied.");

class FileSetFormatter extends AbstractStandardFormatter
{
    public function getLogItemName($object)
    {
        return $object->getName();
    }

    public function getSkippedDescription(LoggableObject $object)
    {
        return t('File set %s skipped.', $this->getLogItemName($object));
    }

    /**
     * @param FileSet $object
     * @return string
     */
    public function getPublishStartedDescription(LoggableObject $object)
    {
        return t('Publishing file set %s',  $this->getLogItemName($object));
    }

    public function getPublishCompleteDescription(LoggableObject $object)
    {
        return t('File set %s published at ID %s.', $this->getLogItemName($object), $object->getPublishedFileSetID());
    }



}
