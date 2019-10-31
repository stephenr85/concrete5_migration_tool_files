<?php
namespace Esiteful\Concrete5\MigrationTool\Entity\Import;

use Doctrine\Common\Collections\ArrayCollection;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\Item;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\Attribute\ValidatableAttributesInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Logger\LoggableInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\PublishableInterface;
use Esiteful\Concrete5\MigrationTool\Publisher\Validator\FileSetValidator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MigrationImportFileSets")
 */
class FileSet implements PublishableInterface, LoggableInterface
{
    /**
     * @ORM\Id @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="\Esiteful\Concrete5\MigrationTool\Entity\Import\FileSetObjectCollection")
     **/
    protected $collection;

    /**
     * @ORM\OneToMany(targetEntity="\Esiteful\Concrete5\MigrationTool\Entity\Import\FileSetFile", mappedBy="file_set", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     **/
    public $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param mixed $collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    public function getPublisherValidator()
    {
        return new FileSetValidator($this);
    }

    public function createPublisherLogObject($publishedObject = null)
    {
        $object = new \Esiteful\Concrete5\MigrationTool\Entity\Publisher\Log\Object\FileSet();
        $object->setName($this->getName());
        if (is_object($publishedObject)) {
            $object->setPublishedFileSetID($publishedObject->getFileSetID());
        }
        return $object;
    }
}
