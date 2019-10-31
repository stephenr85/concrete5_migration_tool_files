<?php
namespace Esiteful\Concrete5\MigrationTool\Entity\Import;

use Doctrine\Common\Collections\ArrayCollection;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Provider\UserProviderInterface;
use Esiteful\Concrete5\MigrationTool\Batch\Formatter\ObjectCollection\FileSetFormatter;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\ValidatorInterface;
use Doctrine\ORM\Mapping as ORM;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\ObjectCollection;


/**
 * @ORM\Entity
 */
class FileSetObjectCollection extends ObjectCollection
{
    /**
     * @ORM\OneToMany(targetEntity="\Esiteful\Concrete5\MigrationTool\Entity\Import\FileSet", mappedBy="collection", cascade={"persist", "remove"})
     * @ORM\OrderBy({"name" = "ASC"})
     **/
    public $fileSets;

    public function __construct()
    {
        $this->fileSets = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getFileSets()
    {
        return $this->fileSets;
    }

    /**
     * @param ArrayCollection $fileSets
     */
    public function setFileSets($fileSets)
    {
        $this->fileSets = $fileSets;
    }

    public function getFormatter()
    {
        return new FileSetFormatter($this);
    }

    public function getType()
    {
        return 'file_set';
    }

    public function hasRecords()
    {
        return count($this->getFileSets());
    }

    public function getRecords()
    {
        return $this->getFileSets();
    }

    public function getTreeFormatter()
    {
        return null;
    }

    public function getRecordValidator(ValidatorInterface $batch)
    {
        return \Core::make('migration/batch/file_set/validator', array($batch));
    }
}
