<?php
namespace Esiteful\Concrete5\MigrationTool\Entity\Publisher\Log\Object;

use PortlandLabs\Concrete5\MigrationTool\Entity\Publisher\Log\Object\LoggableObject;
use Esiteful\Concrete5\MigrationTool\Publisher\Logger\Formatter\Object\FileSetFormatter;
use PortlandLabs\Concrete5\MigrationTool\Publisher\PublishableInterface;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Validator\AttributeKeyCategoryValidator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="MigrationPublisherLogFileSets")
 */
class FileSet extends LoggableObject
{

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $fsID = 0;

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

    public function getLogFormatter()
    {
        return new FileSetFormatter();
    }

    /**
     * @return mixed
     */
    public function getPublishedFileSetID()
    {
        return $this->fsID;
    }

    /**
     * @param mixed $fsID
     */
    public function setPublishedFileSetID($fsID)
    {
        $this->fsID = $fsID;
    }


}
