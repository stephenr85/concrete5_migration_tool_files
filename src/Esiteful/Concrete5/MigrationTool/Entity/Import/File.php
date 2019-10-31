<?php
namespace Esiteful\Concrete5\MigrationTool\Entity\Import;

use Doctrine\Common\Collections\ArrayCollection;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\Item;
use PortlandLabs\Concrete5\MigrationTool\Batch\Validator\Attribute\ValidatableAttributesInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Logger\LoggableInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\PublishableInterface;
use Esiteful\Concrete5\MigrationTool\Publisher\Validator\FileValidator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MigrationImportFiles")
 */
class File implements PublishableInterface, ValidatableAttributesInterface, LoggableInterface
{
    /**
     * @ORM\Id @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $date_added;

    /**
     * @ORM\Column(type="string")
     */
    protected $prefix;

    /**
     * @ORM\Column(type="string")
     */
    protected $filename;

    /**
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="\Esiteful\Concrete5\MigrationTool\Entity\Import\FileAttribute", mappedBy="file", cascade={"persist", "remove"})
     **/
    public $attributes;

    /**
     * @ORM\ManyToOne(targetEntity="\Esiteful\Concrete5\MigrationTool\Entity\Import\FileObjectCollection")
     **/
    protected $collection;


    public function __construct()
    {
        $this->attributes = new ArrayCollection();
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param mixed $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * @param mixed $date_added
     */
    public function setDateAdded($date_added)
    {
        $this->date_added = $date_added;
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
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param mixed $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAttributeValidatorDriver()
    {
        return 'file_attribute';
    }

    public function getPublisherValidator()
    {
        return new FileValidator($this);
    }

    public function createPublisherLogObject($publishedObject = null)
    {
        $object = new \Esiteful\Concrete5\MigrationTool\Entity\Publisher\Log\Object\File();
        $object->setName($this->getPrefix().':'.$this->getFilename());
        $object->setFilename($this->getFilename());
        $object->setPrefix($this->getPrefix());
        if (is_object($publishedObject)) {
            $object->setPublishedFileID($publishedObject->getFileID());
        }
        return $object;
    }
}
