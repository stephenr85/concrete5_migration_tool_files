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
use Esiteful\Concrete5\MigrationTool\Entity\Import\File;
use Esiteful\Concrete5\MigrationTool\Entity\Publisher\Log\Object\File as LogFile;
use Concrete\Core\File\File as CoreFile;
use Concrete\Core\Entity\File\Version as CoreFileVersion;
use Concrete\Core\User\UserInfo;

defined('C5_EXECUTE') or die("Access Denied.");

class CreateFileRoutineAction implements RoutineActionInterface
{

    protected $file;
    protected $fileID;

    public function __construct(File $file)
    {
        $this->file = $file;
        $this->fileID = $file->getID();
    }

    public function __sleep()
    {
        return array('fileID');
    }

    public function __wakeup()
    {
        $this->populateFile($this->fileID);
    }

    public function populateFile($id)
    {
        $entityManager = \Database::connection()->getEntityManager();
        $r = $entityManager->getRepository(File::class);
        $this->file = $r->findOneById($id);
    }

    public function execute(BatchInterface $batch, LoggerInterface $logger)
    {
        //\Log::addInfo(__CLASS__.'::execute');
        $entityManager = \Database::connection()->getEntityManager();

        $file = $this->file;

        if ($file->getPublisherValidator()->skipItem()) {
            $logger->logSkipped($file);
            return;
        }

        $logger->logPublishStarted($file);

        $title = $file->getTitle();
        $filename = $file->getFilename();
        $prefix = $file->getPrefix();
        
        $fvRepo = $entityManager->getRepository(CoreFileVersion::class);
        $fv = $fvRepo->findOneBy([
            'fvPrefix' => $prefix,
            'fvFilename' => $filename
        ]);

        if(!$fv) {
            $publishedFile = CoreFile::add($filename, $prefix, ['fvTitle' => $title]);
        } else {
            $publishedFile = $fv->getFile();
        }

        $publishedFile->setDateAdded(new \DateTime($file->getDateAdded()));

        $author = UserInfo::getByUsername($file->getAuthor());
        if(is_object($user)) {
            $publishedFile->setUser($author);
        }

        $mappers = \Core::make('migration/manager/mapping');

        // TODO: Set Attributes
        foreach ($file->getAttributes() as $attribute) {
            /*
            $mapper = $mappers->driver('file_attribute');
            $list = $mappers->createTargetItemList($batch, $mapper);
            //\Log::addInfo($file->getID() . '|' . $attribute->getAttribute()->getHandle());
            $item = new ContentMapperItem($file->getID() . '|' . $attribute->getAttribute()->getHandle());
            $targetItem = $list->getSelectedTargetItem($item);
            if (!($targetItem instanceof UnmappedTargetItem || $targetItem instanceof IgnoredTargetItem)) {
                $ak = $mapper->getTargetItemContentObject($targetItem);
                if (is_object($ak)) {
                    $value = $attribute->getAttribute()->getAttributeValue();
                    $publisher = $value->getPublisher();
                    $publisher->publish($batch, $ak, $publishedFile, $value);
                }
            }
            */
            $ak = TargetItemList::getBatchTargetItem($batch, 'file_attribute', $attribute->getAttribute()->getHandle());
            $logInfo = __CLASS__.':'.$attribute->getAttribute()->getHandle();
            $logInfo .= "\n".var_dump_safe($ak, false)."\n";
            if (is_object($ak)) {
                $logInfo .= 'ok';
                $value = $attribute->getAttribute()->getAttributeValue();
                $publisher = $value->getPublisher();
                $publisher->publish($batch, $ak, $publishedFile, $value);
            }
            //\Log::addInfo($logInfo);
        }

        $logger->logPublishComplete($file, $publishedFile);
    }

}
