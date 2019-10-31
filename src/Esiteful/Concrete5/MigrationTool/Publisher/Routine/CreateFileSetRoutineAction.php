<?php
namespace Esiteful\Concrete5\MigrationTool\Publisher\Routine;

use Concrete\Core\Attribute\Key\Category;
use Concrete\Core\Utility\Service\Identifier;
use PortlandLabs\CalendarImport\Entity\Import\Event;
use PortlandLabs\Concrete5\MigrationTool\Batch\BatchInterface;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\Item as ContentMapperItem;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\MapperManagerInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\IgnoredTargetItem;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\UnmappedTargetItem;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\User;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Logger\LoggerInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Routine\RoutineActionInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Routine\RoutineInterface;
use PortlandLabs\CalendarImport\Entity\Import\BatchSettings;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\TargetItemList;
use Esiteful\Concrete5\MigrationTool\Entity\Import\FileSet;
use Esiteful\Concrete5\MigrationTool\Entity\Publisher\Log\Object\FileSet as LogFileSet;
use Concrete\Core\File\File as CoreFile;
use Concrete\Core\Entity\File\Version as CoreFileVersion;
use Concrete\Core\File\Set\Set as CoreFileSet;

defined('C5_EXECUTE') or die("Access Denied.");

class CreateFileSetRoutineAction implements RoutineActionInterface
{

    protected $fileSet;
    protected $fileSetID;

    public function __construct(FileSet $fileSet)
    {
        $this->fileSet = $fileSet;
        $this->fileSetID = $fileSet->getID();
    }

    public function __sleep()
    {
        return array('fileSetID');
    }

    public function __wakeup()
    {
        $this->populateFileSet($this->fileSetID);
    }

    public function populateFileSet($id)
    {
        $entityManager = \Database::connection()->getEntityManager();
        $r = $entityManager->getRepository(FileSet::class);
        $this->fileSet = $r->findOneById($id);
    }

    public function execute(BatchInterface $batch, LoggerInterface $logger)
    {
        //\Log::addInfo(__CLASS__.'::execute');
        $entityManager = \Database::connection()->getEntityManager();

        $fileSet = $this->fileSet;

        if ($fileSet->getPublisherValidator()->skipItem()) {
            $logger->logSkipped($fileSet);
            return;
        }

        $logger->logPublishStarted($fileSet);

        $publishedFileSet = CoreFileSet::getByName($fileSet->getName());
        if(!is_object($publishedFileSet)) {
            $publishedFileSet = CoreFileSet::createAndGetSet($fileSet->getName(), CoreFileSet::TYPE_PUBLIC);
        }
        // TODO add files to set
        $fIDs = [];
        foreach($fileSet->getFiles() as $fileSetFile) {
            $inspector = \Core::make('import/value_inspector');
            $result = $inspector->inspect($fileSetFile->getFileId());
            $fID = $result->getReplacedValue();

            $coreFile = CoreFile::getByID($fID);
            if(is_object($coreFile)) {
                $fIDs[] = $fID;
                $publishedFileSet->addFileToSet($coreFile);
            }
        }
        $publishedFileSet->updateFileSetDisplayOrder($fIDs);

        $logger->logPublishComplete($fileSet, $publishedFileSet);
    }

}
