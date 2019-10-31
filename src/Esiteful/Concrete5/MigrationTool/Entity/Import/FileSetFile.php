<?php
namespace Esiteful\Concrete5\MigrationTool\Entity\Import;
use Doctrine\ORM\Mapping as ORM;
use PortlandLabs\Concrete5\MigrationTool\Publisher\Logger\LoggableInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="MigrationImportFileSetFile")
 */
class FileSetFile
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="FileSet")
     **/
    protected $file_set;

    /**
     * @ORM\Column(type="string")
     */
    protected $file_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $position;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFileId()
    {
        return $this->file_id;
    }

    /**
     * @param mixed $position
     */
    public function setFileId($id)
    {
        $this->file_id = $id;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getFileSet()
    {
        return $this->file_set;
    }

    /**
     * @param mixed $file
     */
    public function setFileSet($fileSet)
    {
        $this->file_set = $fileSet;
    }

}
