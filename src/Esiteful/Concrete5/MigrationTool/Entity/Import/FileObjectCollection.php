<?php
namespace Esiteful\Concrete5\MigrationTool\Entity\Import;

use Doctrine\Common\Collections\ArrayCollection;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Provider\UserProviderInterface;
use Esiteful\Concrete5\MigrationTool\Batch\Formatter\File\TreeJsonFormatter;
use Esiteful\Concrete5\MigrationTool\Batch\Formatter\ObjectCollection\FileFormatter;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\ValidatorInterface;
use Doctrine\ORM\Mapping as ORM;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\ObjectCollection;


/**
 * @ORM\Entity
 */
class FileObjectCollection extends ObjectCollection implements UserProviderInterface
{
    /**
     * @ORM\OneToMany(targetEntity="\Esiteful\Concrete5\MigrationTool\Entity\Import\File", mappedBy="collection", cascade={"persist", "remove"})
     * @ORM\OrderBy({"date_added" = "ASC"})
     **/
    public $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param ArrayCollection $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function getFormatter()
    {
        return new FileFormatter($this);
    }

    public function getType()
    {
        return 'file';
    }

    public function hasRecords()
    {
        return count($this->getFiles());
    }

    public function getRecords()
    {
        return $this->getFiles();
    }

    public function getTreeFormatter()
    {
        return new TreeJsonFormatter($this);
    }

    public function getRecordValidator(ValidatorInterface $batch)
    {
        return \Core::make('migration/batch/file/validator', array($batch));
    }

    public function getUserNames()
    {
        $users = array();
        foreach ($this->getFiles() as $file) {
            $users[] = $file->getAuthor();
        }
        return array_unique($users);
    }
}
